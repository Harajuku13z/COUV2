@extends('layouts.admin')
@section('title', 'Modifier le témoignage')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('admin.testimonials.index') }}"
           class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-2">
            &larr; Retour aux témoignages
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Modifier le témoignage de {{ $testimonial->author_name }}</h1>
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

    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="author_name" class="block text-sm font-medium text-slate-700 mb-1">Nom de l'auteur <span class="text-red-500">*</span></label>
                    <input type="text" id="author_name" name="author_name"
                           value="{{ old('author_name', $testimonial->author_name) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="author_city" class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                    <input type="text" id="author_city" name="author_city"
                           value="{{ old('author_city', $testimonial->author_city ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                </div>

                <div>
                    <label for="service_label" class="block text-sm font-medium text-slate-700 mb-1">Service concerné</label>
                    <input type="text" id="service_label" name="service_label"
                           value="{{ old('service_label', $testimonial->service_label ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-slate-700 mb-1">Note <span class="text-red-500">*</span></label>
                    <select id="rating" name="rating"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>
                                {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Contenu du témoignage <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="5" maxlength="1000"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                          required>{{ old('content', $testimonial->content) }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Max 1000 caractères</p>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-between">
            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST"
                  onsubmit="return confirm('Supprimer ce témoignage ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-2xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                    Supprimer
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.testimonials.index') }}"
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
