<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\GeoGouvServiceInterface;
use App\Models\City;
use App\Models\Department;
use App\Traits\TracksApiCost;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeoGouvService implements GeoGouvServiceInterface
{
    use TracksApiCost;

    public function searchDepartments(?string $query = null, int $limit = 20): Collection
    {
        $normalizedQuery = trim((string) $query);
        $cacheKey = 'geo:departments:'.md5($normalizedQuery.'|'.$limit);

        return Cache::remember(
            $cacheKey,
            now()->addDays(30),
            function () use ($normalizedQuery, $limit): Collection {
                $response = Http::baseUrl(config('services.geo_gouv.base_url'))
                    ->timeout((int) config('services.geo_gouv.timeout', 20))
                    ->get('/departements', [
                        'fields' => 'nom,code,codeRegion',
                        'format' => 'json',
                    ])
                    ->throw()
                    ->json();

                return collect($response)
                    ->filter(function (array $department) use ($normalizedQuery): bool {
                        if ($normalizedQuery === '') {
                            return true;
                        }

                        $name = mb_strtolower((string) ($department['nom'] ?? ''));
                        $code = mb_strtolower((string) ($department['code'] ?? ''));
                        $query = mb_strtolower($normalizedQuery);

                        return str_contains($name, $query) || str_contains($code, $query);
                    })
                    ->sortBy(fn (array $department): string => (string) ($department['code'] ?? ''))
                    ->take($limit)
                    ->map(fn (array $department): array => [
                        'code' => (string) ($department['code'] ?? ''),
                        'name' => (string) ($department['nom'] ?? ''),
                        'region_code' => (string) ($department['codeRegion'] ?? ''),
                    ])
                    ->values();
            }
        );
    }

    public function getCitiesByDepartment(string $deptCode): Collection
    {
        return Cache::remember(
            "geo:dept:{$deptCode}",
            now()->addDays(30),
            function () use ($deptCode): Collection {
                $response = Http::baseUrl(config('services.geo_gouv.base_url'))
                    ->timeout((int) config('services.geo_gouv.timeout', 20))
                    ->get("/departements/{$deptCode}/communes", [
                        'fields' => 'nom,code,codesPostaux,centre,population,surface',
                        'format' => 'json',
                    ])
                    ->throw()
                    ->json();

                return collect($response)->map(fn (array $city): array => $this->normalizeCityData($city, $deptCode));
            }
        );
    }

    public function getCityByInseeCode(string $inseeCode): ?array
    {
        return Cache::remember(
            "geo:city:{$inseeCode}",
            now()->addDays(30),
            function () use ($inseeCode): ?array {
                $response = Http::baseUrl(config('services.geo_gouv.base_url'))
                    ->timeout((int) config('services.geo_gouv.timeout', 20))
                    ->get("/communes/{$inseeCode}", [
                        'fields' => 'nom,code,codesPostaux,centre,population,surface,codeDepartement',
                        'format' => 'json',
                    ])
                    ->throw()
                    ->json();

                return is_array($response) ? $this->normalizeCityData($response, (string) ($response['codeDepartement'] ?? '')) : null;
            }
        );
    }

    public function searchByPostalCode(string $postalCode): Collection
    {
        return Cache::remember(
            "geo:postal:{$postalCode}",
            now()->addDays(7),
            function () use ($postalCode): Collection {
                $response = Http::baseUrl(config('services.geo_gouv.base_url'))
                    ->timeout((int) config('services.geo_gouv.timeout', 20))
                    ->get('/communes', [
                        'codePostal' => $postalCode,
                        'fields' => 'nom,code,codesPostaux,centre,population,surface,codeDepartement',
                        'format' => 'json',
                    ])
                    ->throw()
                    ->json();

                return collect($response)->map(fn (array $city): array => $this->normalizeCityData($city, (string) ($city['codeDepartement'] ?? '')));
            }
        );
    }

    public function searchByName(string $name, ?string $deptCode = null): Collection
    {
        $parameters = [
            'nom' => $name,
            'boost' => 'population',
            'fields' => 'nom,code,codesPostaux,centre,population,surface,codeDepartement',
            'format' => 'json',
        ];

        if ($deptCode !== null) {
            $parameters['codeDepartement'] = $deptCode;
        }

        $response = Http::baseUrl(config('services.geo_gouv.base_url'))
            ->timeout((int) config('services.geo_gouv.timeout', 20))
            ->get('/communes', $parameters)
            ->throw()
            ->json();

        return collect($response)->map(fn (array $city): array => $this->normalizeCityData($city, (string) ($city['codeDepartement'] ?? $deptCode)));
    }

    public function importDepartment(string $deptCode): int
    {
        $departmentMeta = Http::baseUrl(config('services.geo_gouv.base_url'))
            ->timeout((int) config('services.geo_gouv.timeout', 20))
            ->get("/departements/{$deptCode}", [
                'fields' => 'nom,code,codeRegion,nomRegion',
                'format' => 'json',
            ])
            ->throw()
            ->json();

        Department::query()->updateOrCreate(
            ['code' => $deptCode],
            [
                'name' => $departmentMeta['nom'] ?? $deptCode,
                'region_code' => (string) ($departmentMeta['codeRegion'] ?? ''),
                'region_name' => (string) ($departmentMeta['nomRegion'] ?? ''),
            ]
        );

        $cities = $this->getCitiesByDepartment($deptCode)->values();

        foreach ($cities as $city) {
            $nearbyCities = $cities
                ->filter(function (array $candidate) use ($city): bool {
                    if ($candidate['code_insee'] === $city['code_insee']) {
                        return false;
                    }

                    if ($candidate['lat'] === null || $candidate['lon'] === null || $city['lat'] === null || $city['lon'] === null) {
                        return false;
                    }

                    return $this->haversineDistance(
                        (float) $city['lat'],
                        (float) $city['lon'],
                        (float) $candidate['lat'],
                        (float) $candidate['lon'],
                    ) <= 30.0;
                })
                ->sortByDesc('population')
                ->take(10)
                ->values()
                ->map(fn (array $candidate): array => [
                    'code_insee' => $candidate['code_insee'],
                    'name' => $candidate['name'],
                    'slug' => $candidate['slug'],
                ])
                ->all();

            $population = (int) $city['population'];
            $priority = match (true) {
                $population > 50000 => 10,
                $population > 20000 => 9,
                $population > 10000 => 8,
                $population > 5000 => 7,
                $population > 2000 => 6,
                $population > 1000 => 5,
                $population > 500 => 4,
                default => 2,
            };

            City::query()->updateOrCreate(
                ['code_insee' => $city['code_insee']],
                [
                    'name' => $city['name'],
                    'slug' => $city['slug'],
                    'department_code' => $deptCode,
                    'postal_code' => $city['postal_code'],
                    'population' => $population,
                    'lat' => $city['lat'],
                    'lon' => $city['lon'],
                    'surface' => $city['surface'],
                    'seo_priority' => $priority,
                    'nearby_cities' => $nearbyCities,
                    'is_active' => $priority > 2,
                ]
            );
        }

        return $cities->count();
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }

    private function normalizeCityData(array $raw, string $departmentCode): array
    {
        return [
            'code_insee' => (string) ($raw['code'] ?? ''),
            'name' => (string) ($raw['nom'] ?? ''),
            'slug' => Str::slug((string) ($raw['nom'] ?? '')),
            'department_code' => $departmentCode,
            'postal_code' => collect($raw['codesPostaux'] ?? [])->filter()->first(),
            'population' => (int) ($raw['population'] ?? 0),
            'lat' => data_get($raw, 'centre.coordinates.1'),
            'lon' => data_get($raw, 'centre.coordinates.0'),
            'surface' => $raw['surface'] ?? null,
        ];
    }
}
