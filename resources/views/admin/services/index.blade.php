@extends('layouts.admin')
@section('title', 'Services')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Services</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez les services proposés sur votre site.</p>
        </div>
        <a href="{{ route('admin.services.create') }}"
           class="rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition-colors">
            + Ajouter un service
        </a>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Services grouped by category --}}
    @if ($services->isEmpty())
        <div class="rounded-[2rem] bg-white p-12 shadow-sm text-center">
            <p class="text-slate-500 text-sm">Aucun service pour le moment.</p>
            <a href="{{ route('admin.services.create') }}"
               class="mt-4 inline-block rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition-colors">
                Créer le premier service
            </a>
        </div>
    @else
        @foreach ($services as $category => $categoryServices)
            <div class="rounded-[2rem] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-800 capitalize border-b border-slate-100 pb-3 mb-4">
                    {{ $category ?: 'Sans catégorie' }}
                    <span class="ml-2 text-xs font-normal text-slate-400">({{ $categoryServices->count() }})</span>
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-3 font-medium text-slate-500">Nom</th>
                                <th class="pb-3 font-medium text-slate-500">Catégorie</th>
                                <th class="pb-3 font-medium text-slate-500 text-center">Urgence</th>
                                <th class="pb-3 font-medium text-slate-500 text-center">Site web</th>
                                <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($categoryServices as $service)
                                @php
                                    $ws = $service->websiteServices->first();
                                    $isActive = $ws?->is_active ?? false;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3 font-medium text-slate-900">{{ $service->name }}</td>
                                    <td class="py-3 text-slate-600">{{ $service->category }}</td>
                                    <td class="py-3 text-center">
                                        @if ($service->is_emergency)
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700">
                                                Urgence
                                            </span>
                                        @else
                                            <span class="text-slate-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <form action="{{ route('admin.services.toggle', $service->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium transition-colors
                                                           {{ $isActive ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                                {{ $isActive ? 'Actif' : 'Inactif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.services.edit', $service->id) }}"
                                               class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Supprimer ce service ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-2xl border border-red-200 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
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
            </div>
        @endforeach
    @endif

</div>
@endsection
