<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PageController;
use App\Livewire\Onboarding\OnboardingWizard;
use App\Support\InstallationState;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains', []) as $domain) {
    Route::domain($domain)->middleware('web')->group(function (): void {
        Route::get('/', function (InstallationState $installationState) {
            return $installationState->isConfigured()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('onboarding');
        })->name('central.dashboard');

        Route::get('/healthz', function () {
            return response()->json([
                'status' => 'healthy',
            ]);
        })->name('central.health');

        Route::get('/onboarding', OnboardingWizard::class)->name('onboarding');

        Route::prefix('admin')->as('admin.')->group(function (): void {
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
        });
    });
}
