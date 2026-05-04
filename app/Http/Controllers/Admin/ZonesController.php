<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GeoGouvServiceInterface;
use App\Http\Controllers\Controller;
use App\Jobs\ImportDepartmentCitiesJob;
use App\Models\City;
use App\Models\Department;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ZonesController extends Controller
{
    public function __construct(private readonly GeoGouvServiceInterface $geoGouvService)
    {
    }

    public function index(): View
    {
        $favoriteCityIds = $this->favoriteCityIds();
        $departments = Department::query()
            ->withCount([
                'cities',
                'cities as active_cities_count' => fn ($q) => $q->where('is_active', true),
                'cities as favorite_cities_count' => fn ($q) => $q->whereIn('id', $favoriteCityIds),
                'cities as priority_cities_count' => fn ($q) => $q->where('seo_priority', '>=', 8),
            ])
            ->orderBy('code')
            ->get();

        $availableDepartments = $this->geoGouvService
            ->searchDepartments(null, 120)
            ->map(fn (array $department): array => [
                'code' => $department['code'],
                'name' => $department['name'],
                'is_imported' => $departments->contains('code', $department['code']),
            ]);

        $stats = [
            'total_cities' => City::query()->count(),
            'active_cities' => City::query()->where('is_active', true)->count(),
            'favorite_cities' => count($favoriteCityIds),
            'departments' => $departments->count(),
        ];

        return view('admin.zones.index', compact('departments', 'stats', 'availableDepartments'));
    }

    public function cities(Request $request, string $deptCode): View
    {
        $department = Department::query()->where('code', $deptCode)->firstOrFail();
        $favoriteCityIds = $this->favoriteCityIds();

        $citiesQuery = City::query()
            ->where('department_code', $deptCode)
            ->withCount(['pages', 'leads']);

        if ($search = trim((string) $request->string('q'))) {
            $citiesQuery->where(function ($query) use ($search): void {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('postal_code', 'like', "%{$search}%")
                    ->orWhere('code_insee', 'like', "%{$search}%");
            });
        }

        $status = (string) $request->string('status', 'all');
        if ($status === 'active') {
            $citiesQuery->where('is_active', true);
        } elseif ($status === 'inactive') {
            $citiesQuery->where('is_active', false);
        }

        $priority = (string) $request->string('priority', '');
        if ($priority !== '' && is_numeric($priority)) {
            $citiesQuery->where('seo_priority', '>=', (int) $priority);
        }

        if ($request->boolean('favorites_only')) {
            $citiesQuery->whereIn('id', $favoriteCityIds === [] ? [0] : $favoriteCityIds);
        }

        $sort = (string) $request->string('sort', 'priority');
        match ($sort) {
            'name' => $citiesQuery->orderBy('name'),
            'population' => $citiesQuery->orderByDesc('population'),
            default => $citiesQuery->orderByDesc('seo_priority')->orderByDesc('population'),
        };

        $cities = $citiesQuery
            ->paginate(50);

        $cityStats = [
            'total' => City::query()->where('department_code', $deptCode)->count(),
            'active' => City::query()->where('department_code', $deptCode)->where('is_active', true)->count(),
            'favorites' => City::query()->where('department_code', $deptCode)->whereIn('id', $favoriteCityIds === [] ? [0] : $favoriteCityIds)->count(),
            'priority' => City::query()->where('department_code', $deptCode)->where('seo_priority', '>=', 8)->count(),
        ];

        return view('admin.zones.cities', compact('department', 'cities', 'favoriteCityIds', 'cityStats'));
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

    public function toggleDepartment(string $deptCode): RedirectResponse
    {
        $department = Department::query()->where('code', $deptCode)->firstOrFail();
        $hasActiveCities = $department->cities()->where('is_active', true)->exists();

        $department->cities()->update(['is_active' => ! $hasActiveCities]);

        return back()->with('status', $hasActiveCities
            ? "Toutes les villes du département {$department->name} ont été désactivées."
            : "Toutes les villes du département {$department->name} ont été activées.");
    }

    public function destroyDepartment(string $deptCode): RedirectResponse
    {
        $department = Department::query()->where('code', $deptCode)->firstOrFail();
        $favoriteCityIds = collect($this->favoriteCityIds());
        $departmentCityIds = $department->cities()->pluck('id');

        DB::transaction(function () use ($department, $favoriteCityIds, $departmentCityIds): void {
            $this->saveFavoriteCityIds($favoriteCityIds->diff($departmentCityIds)->values()->all());
            $department->cities()->delete();
            $department->delete();
        });

        return redirect()->route('admin.zones.index')->with('status', "Département {$department->code} supprimé avec ses villes.");
    }

    public function updateCityPriority(Request $request, int $id): RedirectResponse
    {
        $request->validate(['seo_priority' => ['required', 'integer', 'min:1', 'max:10']]);
        City::query()->findOrFail($id)->update(['seo_priority' => $request->seo_priority]);
        return back()->with('status', 'Priorité SEO mise à jour.');
    }

    public function toggleCityFavorite(int $id): RedirectResponse
    {
        $city = City::query()->findOrFail($id);
        $favoriteCityIds = collect($this->favoriteCityIds());

        $updatedIds = $favoriteCityIds->contains($city->id)
            ? $favoriteCityIds->reject(fn (int $cityId): bool => $cityId === $city->id)->values()->all()
            : $favoriteCityIds->push($city->id)->unique()->values()->all();

        $this->saveFavoriteCityIds($updatedIds);

        return back()->with('status', $favoriteCityIds->contains($city->id)
            ? "Ville {$city->name} retirée des favorites."
            : "Ville {$city->name} ajoutée aux favorites.");
    }

    public function destroyCity(int $id): RedirectResponse
    {
        $city = City::query()->findOrFail($id);
        $favoriteCityIds = collect($this->favoriteCityIds())
            ->reject(fn (int $cityId): bool => $cityId === $city->id)
            ->values()
            ->all();

        $this->saveFavoriteCityIds($favoriteCityIds);
        $city->delete();

        return back()->with('status', "Ville {$city->name} supprimée.");
    }

    public function bulkUpdateCities(Request $request, string $deptCode): RedirectResponse
    {
        $validated = $request->validate([
            'city_ids' => ['required', 'array', 'min:1'],
            'city_ids.*' => ['integer', 'exists:cities,id'],
            'action' => ['required', 'string', 'in:activate,deactivate,favorite,unfavorite,delete'],
        ]);

        $cities = City::query()
            ->where('department_code', $deptCode)
            ->whereIn('id', $validated['city_ids'])
            ->get();

        if ($cities->isEmpty()) {
            return back()->withErrors(['city_ids' => 'Aucune ville valide sélectionnée.']);
        }

        $action = $validated['action'];
        $favoriteCityIds = collect($this->favoriteCityIds());

        match ($action) {
            'activate' => City::query()->whereIn('id', $cities->pluck('id'))->update(['is_active' => true]),
            'deactivate' => City::query()->whereIn('id', $cities->pluck('id'))->update(['is_active' => false]),
            'favorite' => $favoriteCityIds = $favoriteCityIds->merge($cities->pluck('id'))->unique()->values(),
            'unfavorite' => $favoriteCityIds = $favoriteCityIds->reject(fn (int $cityId): bool => $cities->pluck('id')->contains($cityId))->values(),
            'delete' => City::query()->whereIn('id', $cities->pluck('id'))->delete(),
        };

        if (in_array($action, ['favorite', 'unfavorite', 'delete'], true)) {
            if ($action === 'delete') {
                $favoriteCityIds = $favoriteCityIds->reject(fn (int $cityId): bool => $cities->pluck('id')->contains($cityId))->values();
            }

            $this->saveFavoriteCityIds($favoriteCityIds->all());
        }

        $messages = [
            'activate' => 'Villes activées.',
            'deactivate' => 'Villes désactivées.',
            'favorite' => 'Villes ajoutées aux favorites.',
            'unfavorite' => 'Villes retirées des favorites.',
            'delete' => 'Villes supprimées.',
        ];

        return back()->with('status', $messages[$action]);
    }

    private function favoriteCityIds(): array
    {
        $rawValue = Setting::query()->where('key', 'favorite_city_ids')->value('value');

        if (! is_string($rawValue) || trim($rawValue) === '') {
            return [];
        }

        $decoded = json_decode($rawValue, true);

        return collect(is_array($decoded) ? $decoded : [])
            ->map(fn ($value): int => (int) $value)
            ->filter(fn (int $value): bool => $value > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function saveFavoriteCityIds(array $cityIds): void
    {
        Setting::query()->updateOrCreate(
            ['key' => 'favorite_city_ids'],
            [
                'group' => 'zones',
                'value' => json_encode(array_values(array_unique(array_map('intval', $cityIds))), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}
