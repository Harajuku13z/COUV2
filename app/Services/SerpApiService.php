<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SerpApiServiceInterface;
use App\Models\CompetitorResult;
use App\Models\LocalPackResult;
use App\Models\Page;
use App\Models\PeopleAlsoAsk;
use App\Models\RelatedSearch;
use App\Models\SeoKeyword;
use App\Models\SerpResult;
use App\Traits\TracksApiCost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class SerpApiService implements SerpApiServiceInterface
{
    use TracksApiCost;

    public function googleSearch(string $keyword, string $location): array
    {
        return Cache::remember(
            'serp:google:'.md5($keyword.'|'.$location),
            now()->addDays(7),
            fn (): array => $this->makeRequest([
                'engine' => 'google',
                'q' => $keyword,
                'location' => "{$location}, France",
                'device' => 'mobile',
            ])
        );
    }

    public function googleLocalPack(string $query, string $location): array
    {
        return Cache::remember(
            'serp:local:'.md5($query.'|'.$location),
            now()->addDays(7),
            fn (): array => $this->makeRequest([
                'engine' => 'google_local',
                'q' => $query,
                'location' => "{$location}, France",
            ])
        );
    }

    public function googleAutocomplete(string $query): array
    {
        $response = Cache::remember(
            'serp:autocomplete:'.md5($query),
            now()->addDays(7),
            fn (): array => $this->makeRequest([
                'engine' => 'google_autocomplete',
                'q' => $query,
            ])
        );

        return collect($response['suggestions'] ?? [])->map(function ($suggestion): string {
            return is_array($suggestion) ? (string) ($suggestion['value'] ?? '') : (string) $suggestion;
        })->filter()->values()->all();
    }

    public function getPeopleAlsoAsk(string $keyword, string $location): array
    {
        return Cache::remember(
            'serp:paa:'.md5($keyword.'|'.$location),
            now()->addDays(30),
            function () use ($keyword, $location): array {
                $search = $this->googleSearch($keyword, $location);

                return collect($search['related_questions'] ?? [])->map(fn (array $item): array => [
                    'question' => (string) ($item['question'] ?? ''),
                    'snippet' => (string) ($item['snippet'] ?? ''),
                    'url' => (string) ($item['link'] ?? ''),
                ])->filter(fn (array $item): bool => $item['question'] !== '')->values()->all();
            }
        );
    }

    public function googleMaps(string $query, float $lat, float $lon): array
    {
        return Cache::remember(
            'serp:maps:'.md5($query.'|'.$lat.'|'.$lon),
            now()->addDays(7),
            fn (): array => $this->makeRequest([
                'engine' => 'google_maps',
                'q' => $query,
                'll' => "@{$lat},{$lon},14z",
                'type' => 'search',
            ])
        );
    }

    public function fullAnalysis(int $pageId, string $service, string $city, string $department): void
    {
        $page = Page::query()->with(['city', 'service'])->findOrFail($pageId);
        $location = "{$city}, {$department}";
        $primaryKeyword = "{$service} {$city}";

        $search = $this->googleSearch($primaryKeyword, $location);
        $local = $this->googleLocalPack($primaryKeyword, $location);
        $autocomplete = $this->googleAutocomplete($primaryKeyword);
        $paa = $this->getPeopleAlsoAsk($primaryKeyword, $location);
        $maps = $page->city?->lat !== null && $page->city?->lon !== null
            ? $this->googleMaps($primaryKeyword, (float) $page->city->lat, (float) $page->city->lon)
            : [];

        $results = [
            'google' => $search,
            'google_local' => $local,
            'google_autocomplete' => ['suggestions' => $autocomplete],
            'google_paa' => ['related_questions' => $paa],
            'google_maps' => $maps,
        ];

        foreach ($results as $engine => $payload) {
            $serpResult = SerpResult::query()->create([
                'query' => $primaryKeyword,
                'city_name' => $city,
                'service_name' => $service,
                'engine' => $engine,
                'raw_response' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'analyzed_at' => now(),
                'expires_at' => now()->addDays($engine === 'google_paa' ? 30 : 7),
            ]);

            if ($engine === 'google') {
                foreach (collect($payload['organic_results'] ?? [])->take(10) as $index => $organicResult) {
                    CompetitorResult::query()->create([
                        'serp_result_id' => $serpResult->id,
                        'rank' => $index + 1,
                        'name' => (string) ($organicResult['source'] ?? $organicResult['title'] ?? 'Competitor'),
                        'url' => (string) ($organicResult['link'] ?? ''),
                        'title' => (string) ($organicResult['title'] ?? ''),
                        'meta_description' => $organicResult['snippet'] ?? null,
                    ]);
                }
            }

            if (in_array($engine, ['google_local', 'google_maps'], true)) {
                foreach (collect($payload['local_results'] ?? $payload['place_results'] ?? [])->take(20) as $index => $result) {
                    LocalPackResult::query()->create([
                        'serp_result_id' => $serpResult->id,
                        'position' => $index + 1,
                        'name' => (string) ($result['title'] ?? $result['name'] ?? 'Business'),
                        'address' => $result['address'] ?? null,
                        'rating' => $result['rating'] ?? null,
                        'review_count' => $result['reviews'] ?? $result['reviews_original'] ?? null,
                        'url' => $result['website'] ?? $result['link'] ?? null,
                        'maps_url' => $result['gps_coordinates'] ? null : ($result['place_id_search'] ?? null),
                        'phone' => $result['phone'] ?? null,
                        'lat' => data_get($result, 'gps_coordinates.latitude'),
                        'lon' => data_get($result, 'gps_coordinates.longitude'),
                        'category' => $result['type'] ?? null,
                    ]);
                }
            }

            if ($engine === 'google_paa') {
                foreach ($payload['related_questions'] ?? [] as $question) {
                    PeopleAlsoAsk::query()->create([
                        'serp_result_id' => $serpResult->id,
                        'question' => (string) ($question['question'] ?? ''),
                        'answer_snippet' => $question['snippet'] ?? null,
                        'source_url' => $question['url'] ?? null,
                    ]);
                }
            }

            if ($engine === 'google') {
                foreach ($payload['related_searches'] ?? [] as $related) {
                    RelatedSearch::query()->create([
                        'serp_result_id' => $serpResult->id,
                        'query' => (string) ($related['query'] ?? ''),
                    ]);
                }
            }
        }

        SeoKeyword::query()->where('page_id', $page->id)->delete();
        SeoKeyword::query()->create([
            'page_id' => $page->id,
            'keyword' => $primaryKeyword,
            'type' => 'primary',
        ]);

        foreach (collect($autocomplete)->take(5) as $keyword) {
            SeoKeyword::query()->create([
                'page_id' => $page->id,
                'keyword' => $keyword,
                'type' => 'secondary',
            ]);
        }

        foreach (collect($search['related_searches'] ?? [])->take(5) as $related) {
            SeoKeyword::query()->create([
                'page_id' => $page->id,
                'keyword' => (string) ($related['query'] ?? ''),
                'type' => 'lsi',
            ]);
        }
    }

    private function makeRequest(array $params): array
    {
        $startedAt = microtime(true);
        $basePayload = array_merge([
            'api_key' => config('services.serpapi.key'),
            'google_domain' => config('services.serpapi.google_domain'),
            'gl' => config('services.serpapi.gl'),
            'hl' => config('services.serpapi.hl'),
            'no_cache' => config('services.serpapi.no_cache', false) ? 'true' : 'false',
        ], $params);

        if (! RateLimiter::attempt('serpapi', 60, static fn (): bool => true, 60)) {
            throw new \RuntimeException('SerpAPI rate limit exceeded.');
        }

        try {
            $response = Http::timeout((int) config('services.serpapi.timeout', 60))
                ->retry([100, 200, 400], throw: true)
                ->get((string) config('services.serpapi.base_url'), $basePayload)
                ->throw()
                ->json();

            $this->logApiCall('serpapi', (string) config('services.serpapi.base_url'), $basePayload, ['ok' => true], (int) ((microtime(true) - $startedAt) * 1000));

            return is_array($response) ? $response : [];
        } catch (\Throwable $exception) {
            $this->logApiCall('serpapi', (string) config('services.serpapi.base_url'), $basePayload, null, (int) ((microtime(true) - $startedAt) * 1000), $exception);
            throw $exception;
        }
    }
}
