@extends('layouts.admin')
@section('title', 'Zones géographiques')

@section('content')
@php($zonesBaseUrl = \App\Support\CentralAppUrl::admin('zones'))
<div class="space-y-8">
    <section class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div>
                <p class="admin-kicker" style="color:rgba(255,244,232,0.72);">Couverture locale</p>
                <h1 class="admin-page-title" style="color:#fff7ed;">Pilote tes départements comme un portefeuille stratégique.</h1>
                <p class="admin-page-copy" style="color:rgba(248,244,236,0.78);">
                    Active les bonnes zones, renforce les villes prioritaires et nettoie rapidement les départements inutiles sans perdre en lisibilité.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="admin-pill" style="background:rgba(255,255,255,0.08);color:#fff7ed;border-color:rgba(255,255,255,0.08);">
                        {{ number_format($stats['departments']) }} départements suivis
                    </span>
                    <span class="admin-pill" style="background:rgba(255,255,255,0.08);color:#fff7ed;border-color:rgba(255,255,255,0.08);">
                        {{ number_format($stats['favorite_cities']) }} villes favorites
                    </span>
                </div>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/6 p-5">
                <p class="text-sm font-semibold text-white/70">Accès rapide</p>
                <p class="mt-2 text-sm leading-6 text-white/65">
                    Ouvre directement une liste de villes pour affiner les statuts, les priorités et les favoris.
                </p>
                <div class="mt-5">
                    <a href="{{ \App\Support\CentralAppUrl::admin('zones/'.(request('dept', $departments->first()?->code ?? '75').'/cities')) }}"
                       class="admin-link-btn admin-btn-primary w-full">
                        Ouvrir la gestion détaillée des villes
                    </a>
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
            <p class="admin-stat-label">Total villes</p>
            <p class="admin-stat-value">{{ number_format($stats['total_cities']) }}</p>
            <p class="admin-stat-note">Base géographique actuellement importée.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Villes actives</p>
            <p class="admin-stat-value">{{ number_format($stats['active_cities']) }}</p>
            <p class="admin-stat-note">Communes réellement prises en compte par la plateforme.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Départements</p>
            <p class="admin-stat-value">{{ $stats['departments'] }}</p>
            <p class="admin-stat-note">Périmètres départementaux actuellement gérés.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Villes favorites</p>
            <p class="admin-stat-value">{{ number_format($stats['favorite_cities']) }}</p>
            <p class="admin-stat-note">Villes mises en avant pour le pilotage prioritaire.</p>
        </article>
    </div>

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.55fr]">
        <section class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Importer ou réimporter</h2>
                    <p class="admin-section-copy">Sélectionne un département officiel puis lance l’actualisation de ses communes.</p>
                </div>
            </div>

            <form action="{{ \App\Support\CentralAppUrl::admin('zones/import') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="dept_code_top" class="mb-2 block text-sm font-semibold text-slate-700">Département</label>
                    <select id="dept_code_top" name="dept_code" required>
                        <option value="">Choisir un département</option>
                        @foreach ($availableDepartments as $department)
                            <option value="{{ $department['code'] }}">
                                {{ $department['code'] }} - {{ $department['name'] }}{{ $department['is_imported'] ? ' (déjà importé)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-btn admin-btn-primary w-full">Lancer l'import</button>
            </form>

            <div class="admin-note mt-6">
                <p class="m-0 font-semibold">Ce que tu peux faire ici</p>
                <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-600">
                    <li>Réimporter un département depuis l’API du gouvernement</li>
                    <li>Activer ou désactiver toutes ses villes en un clic</li>
                    <li>Supprimer un département et ses communes si besoin</li>
                    <li>Basculer ensuite en vue villes pour travailler les favoris</li>
                </ul>
            </div>
        </section>

        <section class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Départements importés</h2>
                    <p class="admin-section-copy">Une lecture plus propre des volumes, des zones actives et des priorités locales.</p>
                </div>
            </div>

            @if ($departments->isEmpty())
                <p class="py-8 text-center text-sm text-slate-500">Aucun département importé pour le moment.</p>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Département</th>
                                <th class="text-right">Villes</th>
                                <th class="text-right">Actives</th>
                                <th class="text-right">Favorites</th>
                                <th class="text-right">Priorité 8+</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $dept)
                                <tr>
                                    <td>
                                        <p class="font-mono text-xs text-slate-500">{{ $dept->code }}</p>
                                        <p class="font-semibold text-slate-900">{{ $dept->name }}</p>
                                        @if ($dept->region_name)
                                            <p class="mt-1 text-xs text-slate-500">{{ $dept->region_name }}</p>
                                        @endif
                                    </td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($dept->cities_count) }}</td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($dept->active_cities_count) }}</td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($dept->favorite_cities_count) }}</td>
                                    <td class="text-right font-semibold text-slate-700">{{ number_format($dept->priority_cities_count) }}</td>
                                    <td>
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ \App\Support\CentralAppUrl::admin('zones/'.$dept->code.'/cities') }}" class="admin-link-btn admin-btn-secondary">Voir villes</a>
                                            <form action="{{ \App\Support\CentralAppUrl::admin('zones/import') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="dept_code" value="{{ $dept->code }}">
                                                <button type="submit" class="admin-btn admin-btn-secondary">Réimporter</button>
                                            </form>
                                            <form action="{{ \App\Support\CentralAppUrl::admin('zones/'.$dept->code.'/toggle') }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-warning">
                                                    {{ $dept->active_cities_count > 0 ? 'Tout désactiver' : 'Tout activer' }}
                                                </button>
                                            </form>
                                            <form action="{{ \App\Support\CentralAppUrl::admin('zones/'.$dept->code) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce département et toutes ses villes ?');">
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
            @endif
        </section>
    </div>
</div>
@endsection
