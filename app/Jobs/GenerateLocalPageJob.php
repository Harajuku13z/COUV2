<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\AiContentGeneratorServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\Company;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateLocalPageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public int $maxExceptions = 3;

    public function __construct(
        public readonly int $pageId,
        public readonly bool $forceRegenerate = false,
    ) {
        $this->onQueue('ai-generation');
    }

    public function handle(AiContentGeneratorServiceInterface $generatorService): void
    {
        $page = Page::query()->with(['city', 'service'])->findOrFail($this->pageId);
        $company = Company::query()->firstOrFail();

        if (! $this->forceRegenerate && $page->content !== null) {
            return;
        }

        $generatorService->generatePage($page, $company);
        Cache::flush();

        BuildInternalLinksJob::dispatch($page->id);
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping("genpage:{$this->pageId}"))->expireAfter(600)];
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception, ['page_id' => $this->pageId, 'force_regenerate' => $this->forceRegenerate]);
    }
}
