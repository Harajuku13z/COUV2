<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\WebsiteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->with('websiteServices')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('admin.services.index', compact('services'));
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
        return redirect()->route('admin.services.index')->with('status', 'Service créé.');
    }

    public function edit(int $id): View
    {
        $service = Service::query()->with('websiteServices')->findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'is_emergency' => ['boolean'],
        ]);
        $validated['is_emergency'] = $request->boolean('is_emergency');
        Service::query()->findOrFail($id)->update($validated);
        return back()->with('status', 'Service mis à jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Service::query()->findOrFail($id)->delete();
        return redirect()->route('admin.services.index')->with('status', 'Service supprimé.');
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
}
