<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportDepartmentCitiesJob;
use App\Models\City;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZonesController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()
            ->withCount(['cities', 'cities as active_cities_count' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('code')
            ->get();

        $stats = [
            'total_cities'  => City::query()->count(),
            'active_cities' => City::query()->where('is_active', true)->count(),
            'departments'   => $departments->count(),
        ];

        return view('admin.zones.index', compact('departments', 'stats'));
    }

    public function cities(string $deptCode): View
    {
        $department = Department::query()->where('code', $deptCode)->firstOrFail();
        $cities = City::query()
            ->where('department_code', $deptCode)
            ->orderByDesc('seo_priority')
            ->orderByDesc('population')
            ->paginate(50);

        return view('admin.zones.cities', compact('department', 'cities'));
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['dept_code' => ['required', 'string', 'max:3']]);
        ImportDepartmentCitiesJob::dispatch($request->dept_code);
        return back()->with('status', "Import du département {$request->dept_code} lancé en arrière-plan.");
    }

    public function toggleCity(int $id): RedirectResponse
    {
        $city = City::query()->findOrFail($id);
        $city->update(['is_active' => ! $city->is_active]);
        return back()->with('status', "Ville {$city->name} " . ($city->is_active ? 'activée' : 'désactivée') . '.');
    }

    public function updateCityPriority(Request $request, int $id): RedirectResponse
    {
        $request->validate(['seo_priority' => ['required', 'integer', 'min:1', 'max:10']]);
        City::query()->findOrFail($id)->update(['seo_priority' => $request->seo_priority]);
        return back()->with('status', 'Priorité SEO mise à jour.');
    }
}
