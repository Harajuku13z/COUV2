<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Concerns\LogsFailures;
use App\Models\Page;
use App\Models\SerpResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class RefreshSerpDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    public int $maxExceptions = 3;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(): void
    {
        $expiringResults = SerpResult::query()
            ->whereDate('expires_at', '<=', now()->addDays(7))
            ->get(['city_name', 'service_name']);

        foreach ($expiringResults as $result) {
            $page = Page::query()
                ->whereHas('city', fn ($query) => $query->where('name', $result->city_name))
                ->whereHas('service', fn ($query) => $query->where('name', $result->service_name))
                ->first();

            if ($page !== null) {
                AnalyzeKeywordWithSerpApiJob::dispatch($page->id);
            }
        }
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
