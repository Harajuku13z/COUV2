@extends('layouts.admin')
@section('title', 'Modifier le service')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('admin.services.index') }}"
           class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-2">
            &larr; Retour aux services
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Modifier : {{ $service->name }}</h1>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nom du service <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $service->name) }}"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                <input type="text" id="category" name="category"
                       value="{{ old('category', $service->category) }}"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">{{ old('description', $service->description) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_emergency" value="0">
                    <input type="checkbox" name="is_emergency" value="1"
                           @checked(old('is_emergency', $service->is_emergency))
                           class="rounded border-slate-300 text-slate-900">
                    <span class="text-sm font-medium text-slate-700">Service d'urgence</span>
                </label>
                <p class="mt-1 ml-6 text-xs text-slate-400">Cochez si ce service est proposé en intervention urgente.</p>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-between">
            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement ce service ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-2xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                    Supprimer ce service
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.services.index') }}"
                   class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="rounded-2xl bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition-colors">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
