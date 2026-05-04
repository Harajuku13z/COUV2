@extends('layouts.admin')
@section('title', 'Villes — ' . $department->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('admin.zones.index') }}"
               class="mb-2 inline-flex items-center gap-1 text-sm text-slate-500 transition hover:text-slate-700">
                &larr; Retour aux zones
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Département {{ $department->code }} — {{ $department->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Active, priorise, marque en favori ou supprime les villes de ce département.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('admin.zones.import') }}" method="POST">
                @csrf
                <input type="hidden" name="dept_code" value="{{ $department->code }}">
                <button type="submit"
                        class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Réimporter le département
                </button>
            </form>
            <form action="{{ route('admin.zones.toggle', $department->code) }}" method="POST">
                @csrf
                <button type="submit"
                        class="rounded-2xl border border-amber-300 px-4 py-2 text-sm font-medium text-amber-700 transition hover:bg-amber-50">
                    {{ $cityStats['active'] > 0 ? 'Tout désactiver' : 'Tout activer' }}
                </button>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Villes importées</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($cityStats['total']) }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Actives</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($cityStats['active']) }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Favorites</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($cityStats['favorites']) }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Priorité SEO 8+</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($cityStats['priority']) }}</p>
        </div>
    </div>

    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.zones.cities', $department->code) }}" class="grid gap-4 lg:grid-cols-[2fr_1fr_1fr_1fr_auto]">
            <div>
                <label for="q" class="mb-1 block text-sm font-medium text-slate-700">Recherche</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}"
                       placeholder="Ville, code postal ou code INSEE"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
            </div>
            <div>
                <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Statut</label>
                <select id="status" name="status"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <option value="all" @selected(request('status', 'all') === 'all')>Toutes</option>
                    <option value="active" @selected(request('status') === 'active')>Actives</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactives</option>
                </select>
            </div>
            <div>
                <label for="priority" class="mb-1 block text-sm font-medium text-slate-700">Priorité min</label>
                <select id="priority" name="priority"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <option value="">Toutes</option>
                    @for ($i = 10; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((string) request('priority') === (string) $i)>{{ $i }}+</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="sort" class="mb-1 block text-sm font-medium text-slate-700">Tri</label>
                <select id="sort" name="sort"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <option value="priority" @selected(request('sort', 'priority') === 'priority')>Priorité SEO</option>
                    <option value="population" @selected(request('sort') === 'population')>Population</option>
                    <option value="name" @selected(request('sort') === 'name')>Nom</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
                    Filtrer
                </button>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="favorites_only" value="1" @checked(request()->boolean('favorites_only'))
                       class="rounded border-slate-300 text-slate-900">
                Favoris uniquement
            </label>
        </form>
    </div>

    <form action="{{ route('admin.zones.cities.bulk', $department->code) }}" method="POST" class="space-y-4">
        @csrf

        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Gestion des villes</h2>
                    <p class="mt-1 text-sm text-slate-500">Tu peux traiter plusieurs villes d’un coup ou intervenir ligne par ligne.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <select name="action"
                            class="rounded-2xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        <option value="">Action groupée</option>
                        <option value="activate">Activer</option>
                        <option value="deactivate">Désactiver</option>
                        <option value="favorite">Ajouter aux favorites</option>
                        <option value="unfavorite">Retirer des favorites</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button type="submit"
                            onclick="return confirm('Appliquer cette action aux villes sélectionnées ?');"
                            class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Appliquer
                    </button>
                </div>
            </div>

            @if ($cities->isEmpty())
                <p class="py-8 text-center text-sm text-slate-500">Aucune ville trouvée pour ces filtres.</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-3">
                                    <input type="checkbox" id="select-all-cities" class="rounded border-slate-300 text-slate-900">
                                </th>
                                <th class="pb-3 font-medium text-slate-500">Ville</th>
                                <th class="pb-3 font-medium text-slate-500">Codes</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Population</th>
                                <th class="pb-3 font-medium text-slate-500 text-center">Priorité</th>
                                <th class="pb-3 font-medium text-slate-500 text-center">Favori</th>
                                <th class="pb-3 font-medium text-slate-500 text-center">Statut</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Pages</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Leads</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($cities as $city)
                                <tr class="align-top transition-colors hover:bg-slate-50/50">
                                    <td class="py-4">
                                        <input type="checkbox" name="city_ids[]" value="{{ $city->id }}"
                                               class="city-checkbox rounded border-slate-300 text-slate-900">
                                    </td>
                                    <td class="py-4">
                                        <p class="font-semibold text-slate-900">{{ $city->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $city->slug }}</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-mono text-xs text-slate-600">INSEE {{ $city->insee_code ?? '—' }}</p>
                                        <p class="font-mono text-xs text-slate-500">CP {{ $city->postal_code ?? '—' }}</p>
                                    </td>
                                    <td class="py-4 text-right text-slate-700">
                                        {{ $city->population ? number_format($city->population) : '—' }}
                                    </td>
                                    <td class="py-4 text-center">
                                        <form action="{{ route('admin.zones.cities.priority', $city->id) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            <select name="seo_priority"
                                                    class="rounded-xl border border-slate-200 px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-slate-300">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" @selected((int) ($city->seo_priority ?? 5) === $i)>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <button type="submit"
                                                    class="rounded-xl border border-slate-300 px-2 py-1 text-xs text-slate-600 transition hover:bg-slate-50">
                                                OK
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-4 text-center">
                                        <form action="{{ route('admin.zones.cities.favorite', $city->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border transition {{ in_array($city->id, $favoriteCityIds, true) ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-300 text-slate-400 hover:bg-slate-50' }}"
                                                    title="{{ in_array($city->id, $favoriteCityIds, true) ? 'Retirer des favorites' : 'Ajouter aux favorites' }}">
                                                ★
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-4 text-center">
                                        @if ($city->is_active)
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($city->pages_count) }}</td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($city->leads_count) }}</td>
                                    <td class="py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <form action="{{ route('admin.zones.cities.toggle', $city->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="rounded-2xl border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50">
                                                    {{ $city->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.zones.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Supprimer cette ville ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-2xl border border-red-300 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-50">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($cities->hasPages())
                    <div class="mt-6">
                        {{ $cities->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all-cities');
    const checkboxes = Array.from(document.querySelectorAll('.city-checkbox'));

    if (! selectAll || checkboxes.length === 0) {
        return;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
    });
});
</script>
@endpush
