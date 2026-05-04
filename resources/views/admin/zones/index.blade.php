@extends('layouts.admin')
@section('title', 'Zones géographiques')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Zones géographiques</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez les départements et villes couverts par votre activité.</p>
        </div>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
    </div>

    {{-- Import form --}}
    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Importer un département</h2>
        <form action="{{ route('admin.zones.import') }}" method="POST" class="flex items-end gap-3">
            @csrf
            <div class="flex-1 max-w-xs">
                <label for="dept_code_top" class="block text-sm font-medium text-slate-700 mb-1">Code département</label>
                <input type="text" id="dept_code_top" name="dept_code"
                       placeholder="Ex : 75, 13, 2A…"
                       maxlength="3"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required>
            </div>
            <button type="submit"
                    class="rounded-2xl bg-slate-900 text-white px-5 py-3 text-sm font-medium hover:bg-slate-800 transition-colors whitespace-nowrap">
                Lancer l'import
            </button>
        </form>
    </div>

    {{-- Departments table --}}
    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Départements importés</h2>

        @if ($departments->isEmpty())
            <p class="text-sm text-slate-500 text-center py-8">Aucun département importé pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-medium text-slate-500">Code</th>
                            <th class="pb-3 font-medium text-slate-500">Nom</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Villes total</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Villes actives</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($departments as $dept)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 font-mono font-medium text-slate-700">{{ $dept->code }}</td>
                                <td class="py-3 text-slate-900">{{ $dept->name }}</td>
                                <td class="py-3 text-right text-slate-600">{{ number_format($dept->cities_count) }}</td>
                                <td class="py-3 text-right">
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                        {{ number_format($dept->active_cities_count) }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.zones.cities', $dept->code) }}"
                                           class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                            Voir villes
                                        </a>
                                        <form action="{{ route('admin.zones.import') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="dept_code" value="{{ $dept->code }}">
                                            <button type="submit"
                                                    class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                                Actualiser
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
@endsection
