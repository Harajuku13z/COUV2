@extends('layouts.admin')
@section('title', 'Villes — ' . $department->name)

@section('content')
<div class="space-y-8">
    <section class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.15fr_0.85fr] lg:px-8">
            <div>
                <a href="{{ route('admin.zones.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white/70 no-underline transition hover:text-white">
                    &larr; Retour aux zones
                </a>
                <p class="admin-kicker mt-4" style="color:rgba(255,244,232,0.72);">Département {{ $department->code }}</p>
                <h1 class="admin-page-title" style="color:#fff7ed;">{{ $department->name }}</h1>
                <p class="admin-page-copy" style="color:rgba(248,244,236,0.78);">
                    Active, priorise, marque en favori ou retire les villes de ce département à partir d’une vue beaucoup plus opérationnelle.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                    <p class="text-sm font-semibold text-white/70">Villes actives</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-white">{{ number_format($cityStats['active']) }}</p>
                </div>
                <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                    <p class="text-sm font-semibold text-white/70">Villes favorites</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-white">{{ number_format($cityStats['favorites']) }}</p>
                </div>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-stat-grid md:grid-cols-2 xl:grid-cols-4">
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Villes importées</p>
            <p class="admin-stat-value">{{ number_format($cityStats['total']) }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Actives</p>
            <p class="admin-stat-value">{{ number_format($cityStats['active']) }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Favorites</p>
            <p class="admin-stat-value">{{ number_format($cityStats['favorites']) }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Priorité SEO 8+</p>
            <p class="admin-stat-value">{{ number_format($cityStats['priority']) }}</p>
        </article>
    </div>

    <section class="admin-panel admin-panel-strong p-6">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">Filtres</h2>
                <p class="admin-section-copy">Affiche uniquement les villes qui méritent ton attention immédiate.</p>
            </div>
            <div class="admin-actions">
                <form action="{{ route('admin.zones.import') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dept_code" value="{{ $department->code }}">
                    <button type="submit" class="admin-btn admin-btn-secondary">Réimporter le département</button>
                </form>
                <form action="{{ route('admin.zones.toggle', $department->code) }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-warning">
                        {{ $cityStats['active'] > 0 ? 'Tout désactiver' : 'Tout activer' }}
                    </button>
                </form>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.zones.cities', $department->code) }}" class="grid gap-4 xl:grid-cols-[1.4fr_0.85fr_0.85fr_0.85fr_auto]">
            <div>
                <label for="q" class="mb-2 block text-sm font-semibold text-slate-700">Recherche</label>
                <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Ville, code postal ou code INSEE">
            </div>
            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Statut</label>
                <select id="status" name="status">
                    <option value="all" @selected(request('status', 'all') === 'all')>Toutes</option>
                    <option value="active" @selected(request('status') === 'active')>Actives</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactives</option>
                </select>
            </div>
            <div>
                <label for="priority" class="mb-2 block text-sm font-semibold text-slate-700">Priorité min</label>
                <select id="priority" name="priority">
                    <option value="">Toutes</option>
                    @for ($i = 10; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((string) request('priority') === (string) $i)>{{ $i }}+</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="sort" class="mb-2 block text-sm font-semibold text-slate-700">Tri</label>
                <select id="sort" name="sort">
                    <option value="priority" @selected(request('sort', 'priority') === 'priority')>Priorité SEO</option>
                    <option value="population" @selected(request('sort') === 'population')>Population</option>
                    <option value="name" @selected(request('sort') === 'name')>Nom</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="admin-btn admin-btn-primary w-full">Filtrer</button>
            </div>
            <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                <input type="checkbox" name="favorites_only" value="1" @checked(request()->boolean('favorites_only'))
                       class="rounded border-slate-300 text-slate-900">
                Favoris uniquement
            </label>
        </form>
    </section>

    <form action="{{ route('admin.zones.cities.bulk', $department->code) }}" method="POST">
        @csrf
        <section class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Gestion des villes</h2>
                    <p class="admin-section-copy">Travaille à la ville ou en lot selon la pression opérationnelle du moment.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <select name="action" required style="min-width:220px;">
                        <option value="">Action groupée</option>
                        <option value="activate">Activer</option>
                        <option value="deactivate">Désactiver</option>
                        <option value="favorite">Ajouter aux favorites</option>
                        <option value="unfavorite">Retirer des favorites</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button type="submit" onclick="return confirm('Appliquer cette action aux villes sélectionnées ?');" class="admin-btn admin-btn-secondary">
                        Appliquer
                    </button>
                </div>
            </div>

            @if ($cities->isEmpty())
                <p class="py-8 text-center text-sm text-slate-500">Aucune ville trouvée pour ces filtres.</p>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-cities" class="rounded border-slate-300 text-slate-900"></th>
                                <th>Ville</th>
                                <th>Codes</th>
                                <th class="text-right">Population</th>
                                <th class="text-center">Priorité</th>
                                <th class="text-center">Favori</th>
                                <th class="text-center">Statut</th>
                                <th class="text-right">Pages</th>
                                <th class="text-right">Leads</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $city)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="city_ids[]" value="{{ $city->id }}" class="city-checkbox rounded border-slate-300 text-slate-900">
                                    </td>
                                    <td>
                                        <p class="font-semibold text-slate-900">{{ $city->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $city->slug }}</p>
                                    </td>
                                    <td>
                                        <p class="font-mono text-xs text-slate-600">INSEE {{ $city->insee_code ?? '—' }}</p>
                                        <p class="font-mono text-xs text-slate-500">CP {{ $city->postal_code ?? '—' }}</p>
                                    </td>
                                    <td class="text-right font-semibold text-slate-700">
                                        {{ $city->population ? number_format($city->population) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.zones.cities.priority', $city->id) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            <select name="seo_priority" style="width:auto;min-width:72px;padding:10px 12px;border-radius:14px;">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" @selected((int) ($city->seo_priority ?? 5) === $i)>{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <button type="submit" class="admin-btn admin-btn-secondary" style="padding:10px 14px;">OK</button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.zones.cities.favorite', $city->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition {{ in_array($city->id, $favoriteCityIds, true) ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-slate-300 bg-white text-slate-400 hover:bg-slate-50' }}"
                                                    title="{{ in_array($city->id, $favoriteCityIds, true) ? 'Retirer des favorites' : 'Ajouter aux favorites' }}">
                                                ★
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        @if ($city->is_active)
                                            <span class="admin-badge admin-badge-success">Active</span>
                                        @else
                                            <span class="admin-badge admin-badge-muted">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($city->pages_count) }}</td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($city->leads_count) }}</td>
                                    <td>
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <form action="{{ route('admin.zones.cities.toggle', $city->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-secondary">
                                                    {{ $city->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.zones.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Supprimer cette ville ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-danger">Supprimer</button>
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
        </section>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all-cities');
    const checkboxes = Array.from(document.querySelectorAll('.city-checkbox'));

    if (!selectAll || checkboxes.length === 0) {
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
