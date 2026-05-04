@extends('layouts.admin')
@section('title', 'Informations de l\'entreprise')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">Configuration</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Informations de l'entreprise</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Gérez les informations générales, coordonnées et présence en ligne de votre entreprise.</p>
    </section>

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

    <form action="{{ \App\Support\CentralAppUrl::admin('company') }}" method="POST">
        @csrf

        {{-- Section 1: Identité --}}
        <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
            <div class="admin-section-head mb-4">
                <div>
                    <h2 class="admin-section-title"><i class="bi bi-person-fill me-1"></i>Identité</h2>
                    <p class="admin-section-copy">Nom légal, SIRET et type d'activité.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Nom de l'entreprise <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $company?->name ?? '') }}"
                           class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="siret" class="form-label fw-semibold">SIRET <span class="text-muted fw-normal">(14 chiffres, optionnel)</span></label>
                    <input type="text" id="siret" name="siret"
                           value="{{ old('siret', $company?->siret ?? '') }}"
                           maxlength="14" pattern="\d{14}"
                           placeholder="12345678901234"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="activity_type" class="form-label fw-semibold">Type d'activité</label>
                    <select id="activity_type" name="activity_type" class="form-select">
                        @foreach(['couvreur' => 'Couvreur', 'plombier' => 'Plombier', 'peintre' => 'Peintre', 'electricien' => 'Électricien', 'elagueur' => 'Élagueur', 'facadier' => 'Façadier', 'autre' => 'Autre'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('activity_type', $company?->activity_type ?? '') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ton de communication</label>
                    <div class="d-flex gap-4 mt-1">
                        @foreach(['professionnel' => 'Professionnel', 'chaleureux' => 'Chaleureux', 'urgent' => 'Urgent'] as $val => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tone" id="tone_{{ $val }}" value="{{ $val }}"
                                       @checked(old('tone', $company?->tone ?? 'professionnel') === $val)>
                                <label class="form-check-label" for="tone_{{ $val }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Coordonnées --}}
        <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
            <div class="admin-section-head mb-4">
                <div>
                    <h2 class="admin-section-title"><i class="bi bi-telephone-fill me-1"></i>Coordonnées</h2>
                    <p class="admin-section-copy">Téléphone, e-mail et adresse postale.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label fw-semibold">Téléphone</label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone', $company?->phone ?? '') }}"
                           class="form-control" placeholder="06 12 34 56 78">
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">E-mail</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $company?->email ?? '') }}"
                           class="form-control" placeholder="contact@entreprise.fr">
                </div>

                <div class="col-12">
                    <label for="address" class="form-label fw-semibold">Adresse</label>
                    <input type="text" id="address" name="address"
                           value="{{ old('address', $company?->address ?? '') }}"
                           class="form-control" placeholder="12 rue de la Paix">
                </div>

                <div class="col-md-8">
                    <label for="city" class="form-label fw-semibold">Ville</label>
                    <input type="text" id="city" name="city"
                           value="{{ old('city', $company?->city ?? '') }}"
                           class="form-control" placeholder="Lyon">
                </div>

                <div class="col-md-4">
                    <label for="postal_code" class="form-label fw-semibold">Code postal</label>
                    <input type="text" id="postal_code" name="postal_code"
                           value="{{ old('postal_code', $company?->postal_code ?? '') }}"
                           maxlength="5" class="form-control" placeholder="69000">
                </div>
            </div>
        </div>

        {{-- Section 3: Certifications & urgences --}}
        <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
            <div class="admin-section-head mb-4">
                <div>
                    <h2 class="admin-section-title"><i class="bi bi-check-lg me-1"></i>Certifications &amp; urgences</h2>
                    <p class="admin-section-copy">Labels qualité et disponibilité pour les interventions urgentes.</p>
                </div>
            </div>

            <div class="mb-3">
                <p class="form-label fw-semibold mb-2">Certifications</p>
                <div class="d-flex flex-wrap gap-4">
                    @foreach(['RGE' => 'RGE', 'Qualibat' => 'Qualibat', 'Decennale' => 'Décennale'] as $val => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="certifications[]"
                                   id="cert_{{ $val }}" value="{{ $val }}"
                                   @checked(in_array($val, old('certifications', $company?->certifications ?? [])))>
                            <label class="form-check-label fw-semibold" for="cert_{{ $val }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-check form-switch mt-3">
                <input type="hidden" name="emergency_available" value="0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="emergency_available" name="emergency_available" value="1"
                       @checked(old('emergency_available', $company?->emergency_available ?? false))>
                <label class="form-check-label fw-semibold" for="emergency_available">
                    Disponible pour les urgences
                </label>
                <div class="text-muted small mt-1">Activez si vous proposez des interventions d'urgence 24h/24.</div>
            </div>
        </div>

        {{-- Section 4: Présence digitale --}}
        <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
            <div class="admin-section-head mb-4">
                <div>
                    <h2 class="admin-section-title"><i class="bi bi-geo-alt-fill me-1"></i>Présence digitale</h2>
                    <p class="admin-section-copy">Texte d'accroche et liens vers vos profils en ligne.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label for="offer_text" class="form-label fw-semibold">Texte d'accroche / offre <span class="text-muted fw-normal">(max 500 caractères)</span></label>
                    <textarea id="offer_text" name="offer_text" rows="3" maxlength="500"
                              placeholder="Ex : Devis gratuit sous 24h, intervention rapide sur toute la région…"
                              class="form-control">{{ old('offer_text', $company?->offer_text ?? '') }}</textarea>
                    <div class="form-text">{{ strlen(old('offer_text', $company?->offer_text ?? '')) }}/500 caractères</div>
                </div>

                <div class="col-12">
                    <label for="gbp_url" class="form-label fw-semibold">Google Business Profile (URL)</label>
                    <input type="url" id="gbp_url" name="gbp_url"
                           value="{{ old('gbp_url', $company?->gbp_url ?? '') }}"
                           placeholder="https://maps.google.com/…"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="facebook_url" class="form-label fw-semibold">Facebook (URL)</label>
                    <input type="url" id="facebook_url" name="facebook_url"
                           value="{{ old('facebook_url', $company?->facebook_url ?? '') }}"
                           placeholder="https://www.facebook.com/…"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="instagram_url" class="form-label fw-semibold">Instagram (URL)</label>
                    <input type="url" id="instagram_url" name="instagram_url"
                           value="{{ old('instagram_url', $company?->instagram_url ?? '') }}"
                           placeholder="https://www.instagram.com/…"
                           class="form-control">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="admin-actions justify-content-end">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="bi bi-save me-1"></i>Enregistrer les modifications
            </button>
        </div>

    </form>
</div>
@endsection
