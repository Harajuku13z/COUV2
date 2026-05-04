@extends('layouts.admin')
@section('title', 'Modifier le témoignage')

@section('content')
<div class="d-grid gap-4">

    {{-- Back link --}}
    <div>
        <a href="{{ \App\Support\CentralAppUrl::admin('testimonials') }}" class="admin-btn admin-btn-secondary d-inline-flex">
            <i class="bi bi-arrow-left me-1"></i>Retour aux témoignages
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ \App\Support\CentralAppUrl::admin('testimonials/'.$testimonial->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="admin-panel admin-panel-strong p-4 p-lg-5">
            <div class="admin-section-head mb-4">
                <div>
                    <h1 class="admin-page-title">Modifier le témoignage</h1>
                    <p class="admin-page-copy">Avis de <strong>{{ $testimonial->author_name }}</strong>.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="author_name" class="form-label fw-semibold">Nom de l'auteur <span class="text-danger">*</span></label>
                    <input type="text" id="author_name" name="author_name"
                           value="{{ old('author_name', $testimonial->author_name) }}"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="author_city" class="form-label fw-semibold">Ville</label>
                    <input type="text" id="author_city" name="author_city"
                           value="{{ old('author_city', $testimonial->author_city ?? '') }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="service_label" class="form-label fw-semibold">Service concerné</label>
                    <input type="text" id="service_label" name="service_label"
                           value="{{ old('service_label', $testimonial->service_label ?? '') }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="rating" class="form-label fw-semibold">Note <span class="text-danger">*</span></label>
                    <select id="rating" name="rating" class="form-select" required>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('rating', $testimonial->rating) == $i)>
                                {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="source" class="form-label fw-semibold">Source <span class="text-danger">*</span></label>
                    <select id="source" name="source" class="form-select" required>
                        <option value="manual" @selected(old('source', $testimonial->source) === 'manual')>Manuel</option>
                        <option value="google" @selected(old('source', $testimonial->source) === 'google')>Google</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="content" class="form-label fw-semibold">Contenu du témoignage <span class="text-danger">*</span></label>
                    <textarea id="content" name="content" rows="5" maxlength="1000"
                              class="form-control" required>{{ old('content', $testimonial->content) }}</textarea>
                    <div class="form-text">Max 1000 caractères.</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                {{-- Delete --}}
                <form action="{{ \App\Support\CentralAppUrl::admin('testimonials/'.$testimonial->id) }}" method="POST"
                      onsubmit="return confirm('Supprimer ce témoignage définitivement ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn-danger">
                        <i class="bi bi-trash3-fill me-1"></i>Supprimer
                    </button>
                </form>

                {{-- Save / Cancel --}}
                <div class="admin-actions">
                    <a href="{{ \App\Support\CentralAppUrl::admin('testimonials') }}" class="admin-btn admin-btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="bi bi-save me-1"></i>Enregistrer
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
