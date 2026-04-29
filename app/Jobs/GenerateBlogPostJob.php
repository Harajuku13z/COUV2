<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\AiContentGeneratorServiceInterface;
use App\Jobs\Concerns\LogsFailures;
use App\Models\Company;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateBlogPostJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public int $maxExceptions = 3;

    public function __construct(
        public readonly string $topic,
        public readonly int $serviceId,
    ) {
        $this->onQueue('ai-generation');
    }

    public function handle(AiContentGeneratorServiceInterface $generatorService): void
    {
        $service = Service::query()->findOrFail($this->serviceId);
        $company = Company::query()->firstOrFail();

        $generatorService->generateBlogPost($this->topic, $service->name, $company);
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception, ['topic' => $this->topic, 'service_id' => $this->serviceId]);
    }
}
