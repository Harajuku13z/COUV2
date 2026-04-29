<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\LeadServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function __construct(private readonly LeadServiceInterface $leadService)
    {
    }

    public function index(Request $request)
    {
        $leads = Lead::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('city'), fn ($query) => $query->where('city_label', $request->string('city')))
            ->when($request->filled('service'), fn ($query) => $query->where('service_requested', $request->string('service')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.leads.index', compact('leads'));
    }

    public function show(int $id)
    {
        $lead = Lead::query()->with(['page', 'city', 'service'])->findOrFail($id);

        return view('admin.leads.show', compact('lead'));
    }

    public function updateStatus(int $id, Request $request)
    {
        $lead = Lead::query()->findOrFail($id);
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,quoted,won,lost'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->leadService->updateStatus($lead, $validated['status'], $validated['notes'] ?? '');

        return back()->with('status', 'Lead updated.');
    }

    public function export(Request $request): StreamedResponse
    {
        $leads = Lead::query()->latest()->get();

        return response()->streamDownload(function () use ($leads): void {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['date', 'name', 'phone', 'email', 'city', 'service', 'status']);
            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->created_at,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->city_label,
                    $lead->service_requested,
                    $lead->status,
                ]);
            }
            fclose($handle);
        }, 'leads.csv');
    }

    public function destroy(int $id)
    {
        Lead::query()->findOrFail($id)->delete();

        return back()->with('status', 'Lead deleted.');
    }
}
