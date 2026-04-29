<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\InternalLinkingServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class BuildInternalLinksJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;
    public int $maxExceptions = 2;

    public function __construct(public readonly int $pageId)
    {
        $this->onQueue('maintenance');
    }

    public function handle(InternalLinkingServiceInterface $internalLinkingService): void
    {
        $page = Page::query()->findOrFail($this->pageId);
        $internalLinkingService->buildLinksForPage($page);
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
