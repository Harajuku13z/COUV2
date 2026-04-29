<?php

declare(strict_types=1);

use App\Http\Middleware\CachePublicPages;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ThrottleLeadForm;
use App\Http\Middleware\ValidateFileUpload;
use App\Http\Middleware\VerifyHoneypot;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->append(SecurityHeaders::class);
        $middleware->alias([
            'cache.public.pages' => CachePublicPages::class,
            'throttle.lead-form' => ThrottleLeadForm::class,
            'validate.file-upload' => ValidateFileUpload::class,
            'verify.honeypot' => VerifyHoneypot::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
