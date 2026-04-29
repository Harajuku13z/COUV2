<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\LeadServiceInterface;
use App\Models\Lead;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class LeadService implements LeadServiceInterface
{
    public function createFromRequest(Request $request, Page $page): Lead
    {
        $data = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'city_label' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'service_requested' => ['nullable', 'string', 'max:255'],
            'urgency_level' => ['nullable', 'in:low,medium,high,emergency'],
            'message' => ['nullable', 'string'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $lead = Lead::query()->create([
            ...$data,
            'page_id' => $page->id,
            'city_id' => $page->city_id,
            'service_id' => $page->service_id,
            'source_url' => $request->fullUrl(),
            'keyword_targeted' => $page->seoKeywords()->where('type', 'primary')->value('keyword'),
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'uploaded_files' => [],
            'status' => 'new',
        ]);

        $page->increment('lead_count');

        $jobClass = 'App\\Jobs\\SendLeadNotificationJob';

        if (class_exists($jobClass)) {
            $jobClass::dispatch($lead->id);
        }

        return $lead;
    }

    public function getStatsByPeriod(string $period = '30d'): array
    {
        $days = (int) preg_replace('/[^0-9]/', '', $period);
        $from = Carbon::now()->subDays(max($days, 1));

        $query = Lead::query()->where('created_at', '>=', $from);

        return [
            'total' => (clone $query)->count(),
            'by_city' => (clone $query)->selectRaw('city_label, count(*) as total')->groupBy('city_label')->pluck('total', 'city_label')->all(),
            'by_service' => (clone $query)->selectRaw('service_requested, count(*) as total')->groupBy('service_requested')->pluck('total', 'service_requested')->all(),
            'by_status' => (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
            'conversion_rate' => $this->calculateConversionRate((clone $query)->get()),
        ];
    }

    public function updateStatus(Lead $lead, string $status, string $notes = ''): Lead
    {
        $lead->update([
            'status' => $status,
            'notes' => $notes !== '' ? trim(($lead->notes ?? '')."\n".$notes) : $lead->notes,
            'contacted_at' => in_array($status, ['contacted', 'quoted', 'won', 'lost'], true) ? now() : $lead->contacted_at,
        ]);

        return $lead->fresh();
    }

    private function calculateConversionRate($leads): float
    {
        $total = $leads->count();

        if ($total === 0) {
            return 0.0;
        }

        $won = $leads->where('status', 'won')->count();

        return round(($won / $total) * 100, 2);
    }
}
