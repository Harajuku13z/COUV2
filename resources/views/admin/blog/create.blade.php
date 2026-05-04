@extends('layouts.admin')
@section('title', 'Nouvel article')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('admin.blog.index') }}"
           class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-2">
            &larr; Retour au blog
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Nouvel article</h1>
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

    <form action="{{ route('admin.blog.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
                    <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Contenu</h2>

                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title"
                               value="{{ old('title') }}"
                               placeholder="Titre de l'article"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                               required>
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1">Résumé <span class="text-slate-400 font-normal">(max 500 car.)</span></label>
                        <textarea id="excerpt" name="excerpt" rows="3" maxlength="500"
                                  placeholder="Bref résumé affiché dans les listes d'articles…"
                                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">{{ old('excerpt') }}</textarea>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Contenu de l'article <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" rows="16"
                                  placeholder="Rédigez votre article ici…"
                                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-300"
                                  required>{{ old('content') }}</textarea>
                        <p class="mt-1 text-xs text-slate-400">Vous pouvez utiliser du HTML ou du Markdown selon votre configuration.</p>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
                    <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">SEO</h2>

                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta title <span class="text-slate-400 font-normal">(max 70 car.)</span>
                        </label>
                        <input type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title') }}"
                               maxlength="70"
                               placeholder="Titre SEO — laissez vide pour utiliser le titre de l'article"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta description <span class="text-slate-400 font-normal">(max 160 car.)</span>
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"
                                  placeholder="Description affichée dans les résultats Google…"
                                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">{{ old('meta_description') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
                    <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Publication</h2>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Statut <span class="text-red-500">*</span></label>
                        <select id="status" name="status"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                required>
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Brouillon</option>
                            <option value="published" @selected(old('status') === 'published')>Publié</option>
                        </select>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Catégorie</label>
                        <input type="text" id="category" name="category"
                               value="{{ old('category') }}"
                               placeholder="Ex : Toiture, Conseils…"
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                            class="w-full rounded-2xl bg-slate-900 text-white px-4 py-3 text-sm font-medium hover:bg-slate-800 transition-colors">
                        Créer l'article
                    </button>
                    <a href="{{ route('admin.blog.index') }}"
                       class="w-full text-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Annuler
                    </a>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection
