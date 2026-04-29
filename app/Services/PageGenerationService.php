<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PageGenerationServiceInterface;
use App\Models\City;
use App\Models\Page;
use App\Models\Service;
use App\Models\WeatherEvent;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class PageGenerationService implements PageGenerationServiceInterface
{
    public function generatePageTypesForCity(City $city, Service $service): array
    {
        $types = ['service_city', 'devis'];

        if ($service->is_emergency) {
            $types[] = 'urgence';
        }

        if ($city->population > 5000) {
            $types[] = 'city';
        }

        if (WeatherEvent::query()->where('city_id', $city->id)->whereDate('event_date', '>=', now()->subDays(7))->exists()) {
            $types[] = 'meteo';
        }

        $pages = [];

        foreach (array_unique($types) as $type) {
            $slug = $this->buildSlug($type, $city, $service);

            $pages[] = Page::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'city_id' => $city->id,
                    'service_id' => $service->id,
                    'page_type' => $type,
                    'status' => 'draft',
                ]
            );
        }

        $jobClass = 'App\\Jobs\\GenerateLocalPageJob';

        if (class_exists($jobClass)) {
            foreach ($pages as $page) {
                $jobClass::dispatch($page->id);
            }
        }

        return $pages;
    }

    public function generateAllPagesForDepartment(string $deptCode): int
    {
        $cities = City::query()->active()->byDepartment($deptCode)->get();
        $services = Service::query()->get();

        $pageCount = 0;
        $jobs = [];
        $jobClass = 'App\\Jobs\\GenerateLocalPageJob';

        foreach ($cities as $city) {
            foreach ($services as $service) {
                $pages = $this->generatePageTypesForCity($city, $service);
                $pageCount += count($pages);

                if (class_exists($jobClass)) {
                    foreach ($pages as $page) {
                        $jobs[] = new $jobClass($page->id);
                    }
                }
            }
        }

        if ($jobs !== []) {
            collect($jobs)->chunk(50)->each(fn ($chunk): Batch => Bus::batch($chunk->all())->dispatch());
        }

        return $pageCount;
    }

    public function regeneratePage(Page $page): void
    {
        $page->update([
            'similarity_score' => null,
            'last_generated_at' => null,
        ]);

        $jobClass = 'App\\Jobs\\GenerateLocalPageJob';

        if (class_exists($jobClass)) {
            $jobClass::dispatch($page->id, true);
        }
    }

    private function buildSlug(string $type, City $city, Service $service): string
    {
        return match ($type) {
            'devis' => Str::slug("devis {$service->slug} {$city->slug}"),
            'urgence' => Str::slug("urgence {$service->slug} {$city->slug}"),
            'city' => Str::slug("zone {$service->slug} {$city->slug}"),
            'meteo' => Str::slug("meteo {$service->slug} {$city->slug}"),
            default => Str::slug("{$service->slug} {$city->slug}"),
        };
    }
}
