<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\PageGenerationServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateAllPagesForDepartmentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    public int $maxExceptions = 3;

    public function __construct(public readonly string $deptCode)
    {
        $this->onQueue('maintenance');
    }

    public function handle(PageGenerationServiceInterface $pageGenerationService): void
    {
        $pageGenerationService->generateAllPagesForDepartment($this->deptCode);
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception, ['dept_code' => $this->deptCode]);
    }
}
