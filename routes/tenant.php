<?php

declare(strict_types=1);

use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\LeadPublicController;
use App\Http\Controllers\PublicSite\LocalPageController;
use App\Http\Controllers\PublicSite\SeoController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('tenant.home');

    Route::get('/healthz', function () {
        return response()->json([
            'status' => 'healthy',
            'tenant_id' => tenant('id'),
        ]);
    })->name('tenant.health');

    Route::post('/leads/devis', [LeadPublicController::class, 'storeDevis'])->name('public.leads.devis');
    Route::post('/leads/urgence', [LeadPublicController::class, 'storeUrgence'])->name('public.leads.urgence');
    Route::post('/leads/contact', [LeadPublicController::class, 'storeContact'])->name('public.leads.contact');

    Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('public.seo.sitemap');
    Route::get('/robots.txt', [SeoController::class, 'robots'])->name('public.seo.robots');

    Route::get('/{slug}', [LocalPageController::class, 'show'])->name('public.pages.show');
});
