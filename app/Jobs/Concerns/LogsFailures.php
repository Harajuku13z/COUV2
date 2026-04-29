<?php

declare(strict_types=1);

namespace App\Jobs\Concerns;

use App\Models\ApiErrorLog;
use Throwable;

trait LogsFailures
{
    protected function logFailure(Throwable $exception, array $context = []): void
    {
        ApiErrorLog::query()->create([
            'service' => 'job',
            'method' => static::class,
            'endpoint' => static::class,
            'status_code' => null,
            'error_message' => $exception->getMessage(),
            'exception_class' => $exception::class,
            'request_payload' => $context,
            'response_payload' => null,
            'duration_ms' => null,
            'context' => $context,
            'occurred_at' => now(),
        ]);
    }
}
