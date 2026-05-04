<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Realization;
use App\Services\ImageOptimizationService;
use App\Support\CentralAppUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RealizationController extends Controller
{
    public function __construct(private readonly ImageOptimizationService $imageOptimizationService)
    {
    }

    public function index(): View
    {
        $realizations = Realization::query()
            ->with('media')
            ->withCount('media')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest('completed_at')
            ->latest('id')
            ->get();

        $stats = [
            'realizations' => $realizations->count(),
            'featured' => $realizations->where('is_featured', true)->count(),
            'photos' => $realizations->sum('media_count'),
        ];

        return view('admin.realizations.index', compact('realizations', 'stats'));
    }

    public function create(): View
    {
        return view('admin.realizations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $realization = Realization::query()->create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'description' => $validated['description'] ?? null,
            'city_label' => $validated['city_label'] ?? null,
            'completed_at' => $validated['completed_at'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        $this->syncPhotos($realization, $request);

        return redirect()->to(CentralAppUrl::admin('realizations'))->with('status', 'Réalisation ajoutée.');
    }

    public function edit(int $id): View
    {
        $realization = Realization::query()->with('media')->findOrFail($id);

        return view('admin.realizations.edit', compact('realization'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $realization = Realization::query()->with('media')->findOrFail($id);
        $realization->update([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], $realization->id),
            'description' => $validated['description'] ?? null,
            'city_label' => $validated['city_label'] ?? null,
            'completed_at' => $validated['completed_at'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        $this->syncPhotos($realization, $request);

        return redirect()->to(CentralAppUrl::admin('realizations/'.$realization->id.'/edit'))->with('status', 'Réalisation mise à jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $realization = Realization::query()->with('media')->findOrFail($id);

        foreach ($realization->media as $media) {
            $this->deleteMediaFiles($media);
            $media->delete();
        }

        $realization->delete();

        return redirect()->to(CentralAppUrl::admin('realizations'))->with('status', 'Réalisation supprimée.');
    }

    public function destroyPhoto(int $realizationId, int $mediaId): RedirectResponse
    {
        $realization = Realization::query()->findOrFail($realizationId);
        $media = Media::query()
            ->where('id', $mediaId)
            ->where('mediable_type', Realization::class)
            ->where('mediable_id', $realization->id)
            ->firstOrFail();

        $this->deleteMediaFiles($media);
        $media->delete();

        return redirect()->to(CentralAppUrl::admin('realizations/'.$realization->id.'/edit'))->with('status', 'Photo supprimée.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'city_label' => ['nullable', 'string', 'max:120'],
            'completed_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_featured' => ['nullable', 'boolean'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:5120'],
        ]);
    }

    private function syncPhotos(Realization $realization, Request $request): void
    {
        $photos = $request->file('photos', []);

        if (! is_array($photos) || $photos === []) {
            return;
        }

        $currentSort = (int) $realization->media()->max('sort_order');

        foreach ($photos as $index => $photo) {
            $optimized = $this->imageOptimizationService->optimizePhoto($photo);
            $path = $optimized['webp'] ?? $optimized['jpeg'];

            $realization->media()->create([
                'type' => 'ambiance',
                'disk' => 'public',
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'alt_text' => $realization->title.' - photo '.($currentSort + $index + 1),
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

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base !== '' ? $base : 'realisation';
        $suffix = 2;

        while (
            Realization::query()
                ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = ($base !== '' ? $base : 'realisation').'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
