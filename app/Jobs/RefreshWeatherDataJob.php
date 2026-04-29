<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\WeatherServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class RefreshWeatherDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public int $maxExceptions = 3;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(WeatherServiceInterface $weatherService): void
    {
        $weatherService->refreshAllActiveCities();
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception);
    }
}
