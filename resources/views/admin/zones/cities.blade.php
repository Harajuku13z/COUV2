@extends('layouts.admin')
@section('title', 'Villes — ' . $department->name)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.zones.index') }}"
               class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-2">
                &larr; Retour aux zones
            </a>
            <h1 class="text-2xl font-bold text-slate-900">
                Département {{ $department->code }} — {{ $department->name }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                {{ $cities->total() }} ville(s) dans ce département.
            </p>
        </div>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Cities table --}}
    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        @if ($cities->isEmpty())
            <p class="text-sm text-slate-500 text-center py-8">Aucune ville pour ce département.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-medium text-slate-500">Ville</th>
                            <th class="pb-3 font-medium text-slate-500">Code INSEE</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Population</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Priorité SEO</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Statut</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($cities as $city)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 font-medium text-slate-900">{{ $city->name }}</td>
                                <td class="py-3 font-mono text-slate-600">{{ $city->insee_code ?? '—' }}</td>
                                <td class="py-3 text-right text-slate-600">
                                    {{ $city->population ? number_format($city->population) : '—' }}
                                </td>

                                {{-- Priority update form --}}
                                <td class="py-3 text-center">
                                    <form action="{{ route('admin.zones.city.priority', $city->id) }}" method="POST"
                                          class="inline-flex items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="seo_priority"
                                                class="rounded-xl border border-slate-200 px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-slate-300">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" @selected(($city->seo_priority ?? 5) === $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <button type="submit"
                                                class="rounded-xl border border-slate-300 px-2 py-1 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                                            OK
                                        </button>
                                    </form>
                                </td>

                                {{-- Status badge --}}
                                <td class="py-3 text-center">
                                    @if ($city->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                            Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">
                                            Inactif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.zones.city.toggle', $city->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                            {{ $city->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($cities->hasPages())
                <div class="mt-6">
                    {{ $cities->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
