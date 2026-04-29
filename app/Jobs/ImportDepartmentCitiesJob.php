<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\GeoGouvServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\City;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ImportDepartmentCitiesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public int $maxExceptions = 3;

    public function __construct(public readonly string $deptCode)
    {
        $this->onQueue('imports');
    }

    public function handle(GeoGouvServiceInterface $geoGouvService): void
    {
        $geoGouvService->importDepartment($this->deptCode);

        $cityIds = City::query()
            ->active()
            ->byDepartment($this->deptCode)
            ->orderByDesc('population')
            ->take(50)
            ->pluck('id');

        Page::query()
            ->whereIn('city_id', $cityIds)
            ->pluck('id')
            ->each(fn (int $pageId): AnalyzeKeywordWithSerpApiJob => AnalyzeKeywordWithSerpApiJob::dispatch($pageId));
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
