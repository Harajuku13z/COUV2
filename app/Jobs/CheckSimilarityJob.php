<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\PageGenerationServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CheckSimilarityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;
    public int $timeout = 600;
    public int $maxExceptions = 1;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(PageGenerationServiceInterface $pageGenerationService): void
    {
        Page::query()
            ->where('similarity_score', '>', 0.70)
            ->each(fn (Page $page) => $pageGenerationService->regeneratePage($page));
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
