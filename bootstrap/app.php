<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->job(new \App\Jobs\RefreshWeatherDataJob())->everySixHours();
        $schedule->job(new \App\Jobs\RefreshSerpDataJob())->weekly();
        $schedule->job(new \App\Jobs\CheckSimilarityJob())->monthly();
        $schedule->job(new \App\Jobs\GenerateSitemapJob())->daily();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
