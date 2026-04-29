<?php

declare(strict_types=1);

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
    Route::get('/', function () {
        return response()->json([
            'application' => config('app.name'),
            'context' => 'tenant',
            'tenant_id' => tenant('id'),
        ]);
    })->name('tenant.home');

    Route::get('/healthz', function () {
        return response()->json([
            'status' => 'healthy',
            'tenant_id' => tenant('id'),
        ]);
    })->name('tenant.health');
});
