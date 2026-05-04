<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Realization;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\WeatherEvent;
use App\Services\HomePageComposerService;

class HomeController extends Controller
{
    public function __construct(private readonly HomePageComposerService $homePageComposer)
    {
    }

    public function index()
    {
        $company = Company::query()->firstOrFail();
        $services = Service::query()->whereHas('websiteServices', fn ($query) => $query->where('is_active', true))->with('websiteServices')->take(6)->get();
        $testimonials = Testimonial::query()->active()->latest()->take(3)->get();
        $realizations = Realization::query()->featured()->with('media')->orderBy('sort_order')->latest('completed_at')->take(3)->get();
        $latestPosts = BlogPost::query()->published()->latest('published_at')->take(3)->get();
        $weatherAlert = WeatherEvent::query()->whereIn('intensity', ['high', 'extreme'])->where('created_at', '>=', now()->subDay())->latest()->first();
        $homepage = $this->homePageComposer->resolvedSettings();
        $departmentCodes = $this->decodeArray(Setting::query()->where('key', 'department_codes')->value('value'));

        $seo = [
            'title' => $company->name.' | Artisan local et interventions rapides',
            'description' => $company->offer_text ?? 'Interventions rapides, devis precis et accompagnement local pour vos besoins.',
            'canonical' => url('/'),
            'type' => 'website',
            'image' => $company->logo_path ? asset('storage/'.$company->logo_path) : null,
            'robots' => 'index,follow',
        ];
        $schema = [$company->schemaArray()];

        return view('public.home', compact('company', 'services', 'testimonials', 'realizations', 'latestPosts', 'weatherAlert', 'seo', 'schema', 'homepage', 'departmentCodes'));
    }

    private function decodeArray(mixed $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? array_values($decoded) : [];
    }
}
