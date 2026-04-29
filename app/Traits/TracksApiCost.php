<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\AiGeneration;
use App\Models\ApiErrorLog;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

trait TracksApiCost
{
    protected function logApiCall(
        string $service,
        string $endpoint,
        array $payload = [],
        ?array $response = null,
        ?int $durationMs = null,
        ?\Throwable $exception = null,
    ): void {
        Log::info('External API call', [
            'service' => $service,
            'endpoint' => $endpoint,
            'duration_ms' => $durationMs,
            'success' => $exception === null,
        ]);

        if ($exception === null) {
            return;
        }

        ApiErrorLog::query()->create([
            'service' => $service,
            'endpoint' => $endpoint,
            'error_message' => $exception->getMessage(),
            'exception_class' => $exception::class,
            'request_payload' => $payload,
            'response_payload' => $response !== null ? json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'duration_ms' => $durationMs,
            'occurred_at' => now(),
        ]);
    }

    protected function totalCostUsd(?CarbonImmutable $from = null, ?CarbonImmutable $to = null): float
    {
        $query = AiGeneration::query();

        if ($from !== null) {
            $query->where('created_at', '>=', $from);
        }

        if ($to !== null) {
            $query->where('created_at', '<=', $to);
        }

        return (float) $query->sum('cost_usd');
    }

    protected function monthlyBudget(?CarbonImmutable $month = null): float
    {
        $month ??= CarbonImmutable::now();

        return $this->totalCostUsd($month->startOfMonth(), $month->endOfMonth());
    }
}
