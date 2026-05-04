<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\TestimonialsController;
use App\Http\Controllers\Admin\ZonesController;
use App\Http\Controllers\SetupController;
use App\Livewire\Onboarding\OnboardingWizard;
use App\Support\CentralAppUrl;
use App\Support\InstallationState;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains', []) as $domain) {
    Route::domain($domain)->middleware([
        'web',
        \App\Http\Middleware\EnsureCanonicalCentralDomain::class,
    ])->group(function (): void {
        Route::get('/', function (InstallationState $installationState) {
            return $installationState->isConfigured()
                ? redirect()->to(CentralAppUrl::admin())
                : redirect()->to(CentralAppUrl::app('onboarding'));
        })->name('central.dashboard');


        Route::get('/healthz', function () {
            return response()->json([
                'status' => 'healthy',
            ]);
        })->name('central.health');

        Route::get('/onboarding', SetupController::class)->name('onboarding');

        Route::prefix('admin')->as('admin.')->middleware(\App\Http\Middleware\RequiresSetup::class)->group(function (): void {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [AuthController::class, 'login'])->name('login.post');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });

        Route::prefix('admin')->as('admin.')->middleware([
            \App\Http\Middleware\RequiresSetup::class,
            \App\Http\Middleware\RequiresAdminAuth::class,
        ])->group(function (): void {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('/dashboard/sitemap', [DashboardController::class, 'regenerateSitemap'])->name('dashboard.sitemap');
            Route::post('/dashboard/weather', [DashboardController::class, 'refreshWeather'])->name('dashboard.weather');

            Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
            Route::post('/pages/generate-all', [PageController::class, 'generateAll'])->name('pages.generate-all');
            Route::post('/pages/bulk-action', [PageController::class, 'bulkAction'])->name('pages.bulk-action');
            Route::get('/pages/{id}', [PageController::class, 'show'])->name('pages.show');
            Route::post('/pages/{id}/regenerate', [PageController::class, 'regenerate'])->name('pages.regenerate');
            Route::post('/pages/{id}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

            Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
            Route::get('/leads/export', [LeadController::class, 'export'])->name('leads.export');
            Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');
            Route::post('/leads/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
            Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');

            Route::get('/branding', [BrandingController::class, 'edit'])->name('branding.edit');
            Route::post('/branding', [BrandingController::class, 'update'])->name('branding.update');
            Route::post('/branding/logo', [BrandingController::class, 'uploadLogo'])->name('branding.upload-logo');
            Route::post('/branding/favicon', [BrandingController::class, 'uploadFavicon'])->name('branding.upload-favicon');
            Route::get('/branding/preview', [BrandingController::class, 'preview'])->name('branding.preview');

            Route::get('/api-settings', [ApiSettingsController::class, 'edit'])->name('api-settings.edit');
            Route::post('/api-settings', [ApiSettingsController::class, 'update'])->name('api-settings.update');
            Route::post('/api-settings/test-openai', [ApiSettingsController::class, 'testOpenAi'])->name('api-settings.test-openai');
            Route::post('/api-settings/test-serpapi', [ApiSettingsController::class, 'testSerpApi'])->name('api-settings.test-serpapi');
            Route::post('/api-settings/test-weather', [ApiSettingsController::class, 'testWeather'])->name('api-settings.test-weather');

            // Company
            Route::get('/company', [CompanyController::class, 'edit'])->name('company.edit');
            Route::post('/company', [CompanyController::class, 'update'])->name('company.update');

            // Zones & Cities
            Route::get('/zones', [ZonesController::class, 'index'])->name('zones.index');
            Route::post('/zones/import', [ZonesController::class, 'import'])->name('zones.import');
            Route::post('/zones/{deptCode}/toggle', [ZonesController::class, 'toggleDepartment'])->name('zones.toggle');
            Route::delete('/zones/{deptCode}', [ZonesController::class, 'destroyDepartment'])->name('zones.destroy');
            Route::get('/zones/{deptCode}/cities', [ZonesController::class, 'cities'])->name('zones.cities');
            Route::post('/zones/{deptCode}/cities/bulk', [ZonesController::class, 'bulkUpdateCities'])->name('zones.cities.bulk');
            Route::post('/zones/cities/{id}/toggle', [ZonesController::class, 'toggleCity'])->name('zones.cities.toggle');
            Route::post('/zones/cities/{id}/priority', [ZonesController::class, 'updateCityPriority'])->name('zones.cities.priority');
            Route::post('/zones/cities/{id}/favorite', [ZonesController::class, 'toggleCityFavorite'])->name('zones.cities.favorite');
            Route::delete('/zones/cities/{id}', [ZonesController::class, 'destroyCity'])->name('zones.cities.destroy');

            // Services
            Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
            Route::post('/services/generate-all-pages', [ServicesController::class, 'generateAllPages'])->name('services.generate-all-pages');
            Route::get('/services/create', [ServicesController::class, 'create'])->name('services.create');
            Route::post('/services', [ServicesController::class, 'store'])->name('services.store');
            Route::get('/services/{id}/edit', [ServicesController::class, 'edit'])->name('services.edit');
            Route::put('/services/{id}', [ServicesController::class, 'update'])->name('services.update');
            Route::delete('/services/{id}', [ServicesController::class, 'destroy'])->name('services.destroy');
            Route::post('/services/{id}/toggle', [ServicesController::class, 'toggleActive'])->name('services.toggle');
            Route::post('/services/{id}/generate-pages', [ServicesController::class, 'generatePages'])->name('services.generate-pages');

            // Testimonials
            Route::get('/testimonials', [TestimonialsController::class, 'index'])->name('testimonials.index');
            Route::get('/testimonials/create', [TestimonialsController::class, 'create'])->name('testimonials.create');
            Route::post('/testimonials', [TestimonialsController::class, 'store'])->name('testimonials.store');
            Route::get('/testimonials/{id}/edit', [TestimonialsController::class, 'edit'])->name('testimonials.edit');
            Route::put('/testimonials/{id}', [TestimonialsController::class, 'update'])->name('testimonials.update');
            Route::post('/testimonials/{id}/toggle', [TestimonialsController::class, 'toggleVisible'])->name('testimonials.toggle');
            Route::delete('/testimonials/{id}', [TestimonialsController::class, 'destroy'])->name('testimonials.destroy');

            // Blog
            Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
            Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
            Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
            Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
            Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');
            Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
        });
    });
}
