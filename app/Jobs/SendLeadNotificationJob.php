<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Concerns\LogsFailures;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendLeadNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use LogsFailures;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;
    public int $timeout = 30;
    public int $maxExceptions = 5;

    public function __construct(public readonly int $leadId)
    {
        $this->onQueue('notifications');
    }

    public function handle(): void
    {
        $lead = Lead::query()->with(['page', 'city', 'service'])->findOrFail($this->leadId);

        $recipient = config('mail.from.address');

        Mail::send('emails.new-lead', ['lead' => $lead], function ($message) use ($lead, $recipient): void {
            $message
                ->to($recipient)
                ->subject('Nouveau lead - '.($lead->service_requested ?? $lead->service?->name ?? 'Demande'));
        });
    }

    public function backoff(): array
    {
        return [30, 120, 600];
    }

    public function failed(Throwable $exception): void
    {
        $this->logFailure($exception, ['lead_id' => $this->leadId]);
    }
}
