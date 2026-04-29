<?php

use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains', []) as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/', function () {
            return response()->json([
                'application' => config('app.name'),
                'context' => 'central',
                'status' => 'ok',
            ]);
        })->name('central.dashboard');

        Route::get('/healthz', function () {
            return response()->json([
                'status' => 'healthy',
            ]);
        })->name('central.health');
    });
}
