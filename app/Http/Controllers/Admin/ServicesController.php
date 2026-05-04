<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\PageGenerationServiceInterface;
use App\Contracts\AiContentGeneratorServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Media;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Models\WebsiteService;
use App\Services\ImageOptimizationService;
use App\Support\CentralAppUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function __construct(
        private readonly PageGenerationServiceInterface $pageGenerationService,
        private readonly AiContentGeneratorServiceInterface $aiContentGenerator,
        private readonly ImageOptimizationService $imageOptimizationService,
    ) {}

    public function index(): View
    {
        $websiteServices = WebsiteService::query()
            ->active()
            ->with(['service' => fn ($query) => $query->withCount(['pages', 'media'])])
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
        $service = Service::query()->with(['websiteService', 'media'])->findOrFail($id);
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
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:5120'],
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
        $this->syncPhotos($service, $request);
        return back()->with('status', 'Service mis à jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $service = Service::query()->with('media')->findOrFail($id);
        foreach ($service->media as $media) {
            $this->deleteMediaFiles($media);
            $media->delete();
        }
        $service->delete();
        return redirect()->to(CentralAppUrl::admin('services'))->with('status', 'Service supprimé.');
    }

    public function destroyPhoto(int $id, int $mediaId): RedirectResponse
    {
        $service = Service::query()->findOrFail($id);
        $media = Media::query()
            ->where('id', $mediaId)
            ->where('mediable_type', Service::class)
            ->where('mediable_id', $service->id)
            ->firstOrFail();

        $this->deleteMediaFiles($media);
        $media->delete();

        return redirect()->to(CentralAppUrl::admin('services/'.$service->id.'/edit'))->with('status', 'Photo du service supprimée.');
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

        $generatedContentCount = $this->generateContentForServices($departmentCodes, [$service->id]);

        return back()->with('status', "{$pageCount} pages ont été préparées et {$generatedContentCount} contenus IA générés pour {$service->name} sur ".count($departmentCodes).' département(s).');
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

        $generatedContentCount = $this->generateContentForServices($departmentCodes, $activeServiceIds);

        return back()->with('status', "{$pageCount} pages ont été préparées et {$generatedContentCount} contenus IA générés pour les services actifs.");
    }

    private function generateContentForServices(array $departmentCodes, array $serviceIds): int
    {
        $company = Company::query()->firstOrFail();

        $pages = Page::query()
            ->with(['city', 'service', 'content'])
            ->whereIn('service_id', $serviceIds)
            ->whereHas('city', fn ($query) => $query->whereIn('department_code', $departmentCodes))
            ->orderBy('id')
            ->get();

        $count = 0;

        foreach ($pages as $page) {
            $this->aiContentGenerator->generatePage($page, $company);
            $count++;
        }

        return $count;
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

    private function syncPhotos(Service $service, Request $request): void
    {
        $photos = $request->file('photos', []);

        if (! is_array($photos) || $photos === []) {
            return;
        }

        $currentSort = (int) $service->media()->max('sort_order');

        foreach ($photos as $index => $photo) {
            $optimized = $this->imageOptimizationService->optimizePhoto($photo);
            $path = $optimized['webp'] ?? $optimized['jpeg'];

            $service->media()->create([
                'type' => 'ambiance',
                'disk' => 'public',
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'alt_text' => $service->name.' - photo '.($currentSort + $index + 1),
                'size' => Storage::disk('public')->size($path),
                'sort_order' => $currentSort + $index + 1,
            ]);
        }
    }

    private function deleteMediaFiles(Media $media): void
    {
        $paths = array_unique(array_filter([
            $media->path,
            preg_replace('/\.webp$/', '.jpg', $media->path ?? ''),
            preg_replace('/\.(webp|jpg|jpeg)$/', '-placeholder.jpg', $media->path ?? ''),
        ]));

        foreach ($paths as $path) {
            if (is_string($path) && $path !== '' && Storage::disk($media->disk ?: 'public')->exists($path)) {
                Storage::disk($media->disk ?: 'public')->delete($path);
            }
        }
    }
}
