@extends('layouts.admin')
@section('title', 'Zones géographiques')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Zones géographiques</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez vos départements, vos villes actives et vos villes favorites depuis un seul endroit.</p>
        </div>
        <a href="{{ route('admin.zones.cities', request('dept', $departments->first()?->code ?? '75')) }}"
           class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            Ouvrir une liste de villes
        </a>
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

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total villes</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['total_cities']) }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Villes actives</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['active_cities']) }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Départements</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['departments'] }}</p>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Villes favorites</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($stats['favorite_cities']) }}</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.1fr_1.9fr]">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-base font-semibold text-slate-800">Importer ou réimporter un département</h2>
            <p class="mb-5 text-sm text-slate-500">La liste provient de l’API officielle. Tu peux lancer un nouvel import ou réactualiser un département déjà présent.</p>
            <form action="{{ route('admin.zones.import') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="dept_code_top" class="mb-1 block text-sm font-medium text-slate-700">Département</label>
                    <select id="dept_code_top" name="dept_code"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        <option value="">Choisir un département</option>
                        @foreach ($availableDepartments as $department)
                            <option value="{{ $department['code'] }}">
                                {{ $department['code'] }} - {{ $department['name'] }}{{ $department['is_imported'] ? ' (déjà importé)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-slate-800">
                    Lancer l'import
                </button>
            </form>

            <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                <p class="font-medium text-slate-800">Gestion disponible</p>
                <ul class="mt-2 space-y-1">
                    <li>Activation ou désactivation complète d’un département</li>
                    <li>Réimport des données officielles</li>
                    <li>Suppression d’un département et de ses villes</li>
                    <li>Gestion détaillée des villes favorites</li>
                </ul>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Départements importés</h2>
                    <p class="mt-1 text-sm text-slate-500">Chaque ligne donne accès à la gestion complète des villes du département.</p>
                </div>
            </div>

            @if ($departments->isEmpty())
                <p class="py-8 text-center text-sm text-slate-500">Aucun département importé pour le moment.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-3 font-medium text-slate-500">Département</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Villes</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Actives</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Favorites</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Priorité haute</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($departments as $dept)
                                <tr class="align-top transition-colors hover:bg-slate-50/50">
                                    <td class="py-4">
                                        <p class="font-mono text-xs text-slate-500">{{ $dept->code }}</p>
                                        <p class="font-semibold text-slate-900">{{ $dept->name }}</p>
                                        @if ($dept->region_name)
                                            <p class="mt-1 text-xs text-slate-500">{{ $dept->region_name }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($dept->cities_count) }}</td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($dept->active_cities_count) }}</td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($dept->favorite_cities_count) }}</td>
                                    <td class="py-4 text-right text-slate-700">{{ number_format($dept->priority_cities_count) }}</td>
                                    <td class="py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.zones.cities', $dept->code) }}"
                                               class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                                Voir villes
                                            </a>
                                            <form action="{{ route('admin.zones.import') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="dept_code" value="{{ $dept->code }}">
                                                <button type="submit"
                                                        class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                                    Réimporter
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.zones.toggle', $dept->code) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="rounded-2xl border border-amber-300 px-4 py-2 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-50">
                                                    {{ $dept->active_cities_count > 0 ? 'Tout désactiver' : 'Tout activer' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.zones.destroy', $dept->code) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce département et toutes ses villes ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-2xl border border-red-300 px-4 py-2 text-xs font-medium text-red-700 transition-colors hover:bg-red-50">
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
            @endif
        </div>
    </div>
</div>
@endsection
