@extends('layouts.admin')
@section('title', 'Ajouter un témoignage')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('admin.testimonials.index') }}"
           class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-2">
            &larr; Retour aux témoignages
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Ajouter un témoignage</h1>
    </div>

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

    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf

        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="author_name" class="block text-sm font-medium text-slate-700 mb-1">Nom de l'auteur <span class="text-red-500">*</span></label>
                    <input type="text" id="author_name" name="author_name"
                           value="{{ old('author_name') }}"
                           placeholder="Jean Dupont"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="author_city" class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                    <input type="text" id="author_city" name="author_city"
                           value="{{ old('author_city') }}"
                           placeholder="Paris"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                </div>

                <div>
                    <label for="service_label" class="block text-sm font-medium text-slate-700 mb-1">Service concerné</label>
                    <input type="text" id="service_label" name="service_label"
                           value="{{ old('service_label') }}"
                           placeholder="Remplacement de tuiles"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-slate-700 mb-1">Note <span class="text-red-500">*</span></label>
                    <select id="rating" name="rating"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        <option value="">— Choisir —</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating') == $i)>
                                {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="source" class="block text-sm font-medium text-slate-700 mb-1">Source <span class="text-red-500">*</span></label>
                    <select id="source" name="source"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        <option value="manual" @selected(old('source') === 'manual')>Manuel</option>
                        <option value="google" @selected(old('source') === 'google')>Google</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Contenu du témoignage <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="5" maxlength="1000"
                          placeholder="Écrivez ici le témoignage du client…"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                          required>{{ old('content') }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Max 1000 caractères</p>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.testimonials.index') }}"
               class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="rounded-2xl bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition-colors">
                Ajouter le témoignage
            </button>
        </div>
    </form>

</div>
@endsection
