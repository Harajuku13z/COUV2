<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Page;
use App\Models\WeatherEvent;
use Illuminate\Support\Facades\Cache;

class LocalPageController extends Controller
{
    public function show(string $slug)
    {
        $payload = Cache::remember("page:{$slug}", now()->addDay(), function () use ($slug): array {
            $page = Page::query()
                ->with(['content', 'city', 'service', 'seoKeywords', 'outgoingInternalLinks.toPage'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();

            $company = Company::query()->firstOrFail();
            $weatherAlerts = WeatherEvent::query()->where('city_id', $page->city_id)->whereIn('intensity', ['high', 'extreme'])->latest()->get();

            $page->increment('view_count');

            return compact('page', 'company', 'weatherAlerts');
        });

        extract($payload);

        $view = match ($page->page_type) {
            'service_city' => 'public.service-city',
            'urgence' => 'public.urgence',
            'blog' => 'public.blog-show',
            'devis' => 'public.devis',
            default => 'public.page',
        };

        return view($view, compact('page', 'company', 'weatherAlerts'));
    }
}
