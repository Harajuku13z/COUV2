@extends('layouts.admin')
@section('title', 'Nouvel article')

@section('content')
<div class="d-grid gap-4">

    {{-- Back link --}}
    <div>
        <a href="{{ \App\Support\CentralAppUrl::admin('blog') }}" class="admin-btn admin-btn-secondary d-inline-flex">
            <i class="bi bi-arrow-left me-1"></i>Retour au blog
        </a>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ \App\Support\CentralAppUrl::admin('blog') }}" method="POST">
        @csrf

        <div class="row g-4 align-items-start">

            {{-- Left: main content (col-lg-8) --}}
            <div class="col-lg-8 d-grid gap-4">

                {{-- Content panel --}}
                <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                    <h2 class="admin-section-title mb-4"><i class="bi bi-journal-richtext me-1"></i>Contenu</h2>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title"
                               value="{{ old('title') }}"
                               placeholder="Titre de l'article"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label fw-semibold">Résumé <span class="text-muted fw-normal">(max 500 car.)</span></label>
                        <textarea id="excerpt" name="excerpt" rows="3" maxlength="500"
                                  placeholder="Bref résumé affiché dans les listes d'articles…"
                                  class="form-control">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-semibold">Contenu de l'article <span class="text-danger">*</span></label>
                        <textarea id="content" name="content" rows="14"
                                  placeholder="Rédigez votre article ici…"
                                  class="form-control font-monospace" required>{{ old('content') }}</textarea>
                        <div class="form-text">HTML ou Markdown selon votre configuration.</div>
                    </div>
                </div>

                {{-- SEO panel --}}
                <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                    <h2 class="admin-section-title mb-4">SEO</h2>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label fw-semibold">Meta title <span class="text-muted fw-normal">(max 70 car.)</span></label>
                        <input type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title') }}"
                               maxlength="70"
                               placeholder="Titre SEO — laissez vide pour utiliser le titre de l'article"
                               class="form-control">
                        <div class="form-text">Idéalement entre 50 et 70 caractères.</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label fw-semibold">Meta description <span class="text-muted fw-normal">(max 160 car.)</span></label>
                        <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"
                                  placeholder="Description affichée dans les résultats Google…"
                                  class="form-control">{{ old('meta_description') }}</textarea>
                        <div class="form-text">Idéalement entre 120 et 160 caractères.</div>
                    </div>
                </div>

            </div>

            {{-- Right: sidebar (col-lg-4) --}}
            <div class="col-lg-4 d-grid gap-4">

                <div class="admin-panel admin-panel-strong p-4">
                    <h2 class="admin-section-title mb-4">Publication</h2>

                    <div class="mb-3">
                        <label for="category" class="form-label fw-semibold">Catégorie</label>
                        <input type="text" id="category" name="category"
                               value="{{ old('category') }}"
                               placeholder="Ex : Toiture, Conseils…"
                               class="form-control">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Brouillon</option>
                            <option value="published" @selected(old('status') === 'published')>Publié</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
                            <i class="bi bi-save me-1"></i>Créer l'article
                        </button>
                        <a href="{{ \App\Support\CentralAppUrl::admin('blog') }}"
                           class="admin-btn admin-btn-secondary w-100 justify-content-center">
                            Annuler
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection
