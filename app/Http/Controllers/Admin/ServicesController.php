<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\PageGenerationServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use App\Models\WebsiteService;
use App\Support\CentralAppUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function __construct(private readonly PageGenerationServiceInterface $pageGenerationService)
    {
    }

    public function index(): View
    {
        $websiteServices = WebsiteService::query()
            ->active()
            ->with(['service' => fn ($query) => $query->withCount('pages')])
            ->whereHas('service')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (WebsiteService $websiteService): bool => $websiteService->service !== null)
            ->sortBy([
                fn (WebsiteService $websiteService): string => (string) ($websiteService->service?->category ?? ''),
                fn (WebsiteService $websiteService): string => (string) ($websiteService->service?->name ?? ''),
            ])
            ->values();

        $services = $websiteServices->groupBy(fn (WebsiteService $websiteService): string => (string) ($websiteService->service?->category ?? 'Sans catégorie'));

        $departmentCodes = $this->departmentCodes();
        $stats = [
            'services' => $websiteServices->count(),
            'emergency' => $websiteServices->filter(fn (WebsiteService $websiteService): bool => (bool) $websiteService->service?->is_emergency)->count(),
            'pages' => $websiteServices->sum(fn (WebsiteService $websiteService): int => (int) ($websiteService->service?->pages_count ?? 0)),
            'departments' => count($departmentCodes),
        ];

        return view('admin.services.index', compact('services', 'stats', 'departmentCodes'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'is_emergency' => ['boolean'],
        ]);
        $validated['is_emergency'] = $request->boolean('is_emergency');
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        Service::query()->create($validated);
        return redirect()->to(CentralAppUrl::admin('services'))->with('status', 'Service créé.');
    }

    public function edit(int $id): View
    {
        $service = Service::query()->with('websiteService')->findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'is_emergency' => ['boolean'],
            'custom_description' => ['nullable', 'string'],
            'custom_price' => ['nullable', 'string', 'max:50'],
            'keyword_focus' => ['nullable', 'string'],
            'photo_brief' => ['nullable', 'string'],
        ]);
        $validated['is_emergency'] = $request->boolean('is_emergency');
        $service = Service::query()->findOrFail($id);
        $service->update(collect($validated)->only(['name', 'category', 'description', 'is_emergency'])->all());
        WebsiteService::query()->updateOrCreate(
            ['service_id' => $service->id],
            [
                'is_active' => true,
                'custom_description' => $validated['custom_description'] ?? null,
                'custom_price' => $validated['custom_price'] ?? null,
                'keyword_focus' => $validated['keyword_focus'] ?? null,
                'photo_brief' => $validated['photo_brief'] ?? null,
            ]
        );
        return back()->with('status', 'Service mis à jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Service::query()->findOrFail($id)->delete();
        return redirect()->to(CentralAppUrl::admin('services'))->with('status', 'Service supprimé.');
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $ws = WebsiteService::query()->where('service_id', $id)->first();
        if ($ws) {
            $ws->update(['is_active' => ! $ws->is_active]);
        } else {
            WebsiteService::query()->create(['service_id' => $id, 'is_active' => true]);
        }
        return back()->with('status', 'Statut du service mis à jour.');
    }

    public function generatePages(int $id): RedirectResponse
    {
        $service = Service::query()->findOrFail($id);
        $departmentCodes = $this->departmentCodes();

        if ($departmentCodes === []) {
            return back()->withErrors(['service_generation' => 'Aucun département actif n’est configuré pour la génération.']);
        }

        $pageCount = 0;
        foreach ($departmentCodes as $departmentCode) {
            $pageCount += $this->pageGenerationService->generateAllPagesForDepartment($departmentCode, [$service->id]);
        }

        return back()->with('status', "{$pageCount} pages ont été préparées pour {$service->name} sur ".count($departmentCodes).' département(s).');
    }

    public function generateAllPages(): RedirectResponse
    {
        $departmentCodes = $this->departmentCodes();
        $activeServiceIds = WebsiteService::query()->active()->pluck('service_id')->map(fn ($id): int => (int) $id)->filter()->unique()->values()->all();

        if ($departmentCodes === []) {
            return back()->withErrors(['service_generation' => 'Aucun département actif n’est configuré pour la génération.']);
        }

        if ($activeServiceIds === []) {
            return back()->withErrors(['service_generation' => 'Aucun service actif n’est disponible pour la génération.']);
        }

        $pageCount = 0;
        foreach ($departmentCodes as $departmentCode) {
            $pageCount += $this->pageGenerationService->generateAllPagesForDepartment($departmentCode, $activeServiceIds);
        }

        return back()->with('status', "{$pageCount} pages ont été préparées pour les services actifs.");
    }

    private function departmentCodes(): array
    {
        $rawCodes = Setting::query()->where('key', 'department_codes')->value('value');

        if (! is_string($rawCodes) || trim($rawCodes) === '') {
            $legacyCode = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');
            return $legacyCode !== '' ? [$legacyCode] : [];
        }

        $decoded = json_decode($rawCodes, true);

        return collect(is_array($decoded) ? $decoded : [])
            ->map(fn ($value): string => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
