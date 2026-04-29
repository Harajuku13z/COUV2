<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\SeoServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateSitemapJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;
    public int $maxExceptions = 2;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(SeoServiceInterface $seoService): void
    {
        $seoService->generateSitemap();
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
