<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Contracts\SeoServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Page;
use App\Models\WeatherEvent;
use Illuminate\Support\Facades\Cache;

class LocalPageController extends Controller
{
    public function __construct(private readonly SeoServiceInterface $seoService)
    {
    }

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

        $seo = [
            'title' => $page->content?->meta_title ?? $page->slug,
            'description' => $page->content?->meta_description ?? $company->offer_text,
            'canonical' => $this->seoService->generateCanonicalUrl($page),
            'type' => in_array($page->page_type, ['blog', 'meteo'], true) ? 'article' : 'website',
            'image' => $company->logo_path ? asset('storage/'.$company->logo_path) : null,
            'robots' => $page->status === 'published' ? 'index,follow' : 'noindex,nofollow',
        ];

        $breadcrumbs = [
            ['name' => 'Accueil', 'url' => url('/')],
            ['name' => $page->service?->name ?? 'Service', 'url' => null],
            ['name' => $page->city?->name ?? $page->slug, 'url' => null],
        ];

        $schema = array_values(array_filter([
            $company->schemaArray(),
            $page->content?->schema_local_business,
            $page->content?->schema_service,
            $page->content?->schema_faq,
        ]));

        $view = match ($page->page_type) {
            'service_city' => 'public.service-city',
            'urgence' => 'public.urgence',
            'devis' => 'public.devis',
            default => 'public.page',
        };

        return view($view, compact('page', 'company', 'weatherAlerts', 'seo', 'breadcrumbs', 'schema'));
    }
}
