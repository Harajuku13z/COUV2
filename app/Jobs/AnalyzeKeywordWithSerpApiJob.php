<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\SerpApiServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Throwable;

class AnalyzeKeywordWithSerpApiJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public int $maxExceptions = 3;

    public function __construct(public readonly int $pageId)
    {
        $this->onQueue('serp');
    }

    public function handle(SerpApiServiceInterface $serpApiService): void
    {
        $page = Page::query()->with(['city', 'service'])->findOrFail($this->pageId);

        if ($page->city === null || $page->service === null) {
            return;
        }

        $serpApiService->fullAnalysis(
            $page->id,
            $page->service->name,
            $page->city->name,
            $page->city->department_code,
        );
    }

    public function middleware(): array
    {
        return [(new RateLimited('serpapi'))->dontRelease()];
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception, ['page_id' => $this->pageId]);
    }
}
