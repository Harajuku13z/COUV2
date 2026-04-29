<?php

namespace App\Providers;

use App\Contracts\AiContentGeneratorServiceInterface;
use App\Contracts\GeoGouvServiceInterface;
use App\Contracts\InternalLinkingServiceInterface;
use App\Contracts\LeadServiceInterface;
use App\Contracts\OpenAiServiceInterface;
use App\Contracts\PageGenerationServiceInterface;
use App\Contracts\SeoServiceInterface;
use App\Contracts\SerpApiServiceInterface;
use App\Contracts\WeatherServiceInterface;
use App\Services\AiContentGeneratorService;
use App\Services\GeoGouvService;
use App\Services\InternalLinkingService;
use App\Services\LeadService;
use App\Services\OpenAiService;
use App\Services\PageGenerationService;
use App\Services\SeoService;
use App\Services\SerpApiService;
use App\Services\WeatherService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeoGouvServiceInterface::class, GeoGouvService::class);
        $this->app->bind(SerpApiServiceInterface::class, SerpApiService::class);
        $this->app->bind(OpenAiServiceInterface::class, OpenAiService::class);
        $this->app->bind(AiContentGeneratorServiceInterface::class, AiContentGeneratorService::class);
        $this->app->bind(WeatherServiceInterface::class, WeatherService::class);
        $this->app->bind(PageGenerationServiceInterface::class, PageGenerationService::class);
        $this->app->bind(SeoServiceInterface::class, SeoService::class);
        $this->app->bind(InternalLinkingServiceInterface::class, InternalLinkingService::class);
        $this->app->bind(LeadServiceInterface::class, LeadService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
