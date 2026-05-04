@extends('layouts.admin')
@section('title', 'Créer un service')

@section('content')
<div class="d-grid gap-4">

    {{-- Back link --}}
    <div>
        <a href="{{ \App\Support\CentralAppUrl::admin('services') }}" class="admin-btn admin-btn-secondary d-inline-flex">
            <i class="bi bi-arrow-left me-1"></i>Retour aux services
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

    <form action="{{ \App\Support\CentralAppUrl::admin('services') }}" method="POST">
        @csrf

        <div class="admin-panel admin-panel-strong p-4 p-lg-5">
            <div class="admin-section-head mb-4">
                <div>
                    <h1 class="admin-page-title">Créer un service</h1>
                    <p class="admin-page-copy">Ajoutez un nouveau service proposé par votre entreprise.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Nom du service <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           placeholder="Ex : Remplacement de tuiles"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="category" class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                    <input type="text" id="category" name="category"
                           value="{{ old('category') }}"
                           placeholder="Ex : Toiture, Plomberie…"
                           class="form-control" required>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="Décrivez ce service en quelques phrases…"
                              class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <div class="admin-note">
                        <div class="form-check">
                            <input type="hidden" name="is_emergency" value="0">
                            <input class="form-check-input" type="checkbox" id="is_emergency" name="is_emergency" value="1"
                                   @checked(old('is_emergency'))>
                            <label class="form-check-label fw-semibold" for="is_emergency">
                                <i class="bi bi-telephone-fill me-1"></i>Service d'urgence
                            </label>
                            <div class="text-muted small mt-1">Cochez si ce service est proposé en intervention urgente.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-actions mt-4 justify-content-end">
                <a href="{{ \App\Support\CentralAppUrl::admin('services') }}" class="admin-btn admin-btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Créer le service
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
