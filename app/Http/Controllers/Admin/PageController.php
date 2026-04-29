<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAllPagesForDepartmentJob;
use App\Jobs\GenerateLocalPageJob;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()
            ->with(['city', 'service', 'content'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('type'), fn ($query) => $query->where('page_type', $request->string('type')))
            ->when($request->filled('similarity'), fn ($query) => $query->where('similarity_score', '>=', (float) $request->string('similarity')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function show(int $id)
    {
        $page = Page::query()->with(['city', 'service', 'content', 'seoKeywords', 'outgoingInternalLinks.toPage'])->findOrFail($id);

        return view('admin.pages.show', compact('page'));
    }

    public function regenerate(int $id): RedirectResponse
    {
        GenerateLocalPageJob::dispatch($id, true);

        return back()->with('status', 'Regeneration queued.');
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $page = Page::query()->findOrFail($id);
        $page->update([
            'status' => $page->status === 'published' ? 'draft' : 'published',
            'published_at' => $page->status === 'published' ? null : now(),
        ]);

        return back()->with('status', 'Page status updated.');
    }

    public function generateAll(): RedirectResponse
    {
        $deptCode = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');

        if ($deptCode !== '') {
            GenerateAllPagesForDepartmentJob::dispatch($deptCode);
        }

        return back()->with('status', 'Department page generation queued.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'action' => ['required', 'in:publish,unpublish,delete'],
        ]);

        $pages = Page::query()->whereIn('id', $validated['ids']);

        match ($validated['action']) {
            'publish' => $pages->update(['status' => 'published', 'published_at' => now()]),
            'unpublish' => $pages->update(['status' => 'draft', 'published_at' => null]),
            'delete' => $pages->delete(),
        };

        return back()->with('status', 'Bulk action applied.');
    }
}
