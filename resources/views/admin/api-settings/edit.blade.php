@extends('layouts.admin')
@section('title', 'Paramètres API')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">Configuration</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Paramètres API</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Configurez les clés d'accès aux services externes utilisés par la plateforme.</p>
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

    <div class="row g-4 align-items-start">

        {{-- Left: API keys form (col-lg-8) --}}
        <div class="col-lg-8 d-grid gap-4">

            <form action="{{ \App\Support\CentralAppUrl::admin('api-settings') }}" method="POST">
                @csrf

                {{-- OpenAI --}}
                <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
                    <div class="admin-section-head mb-4">
                        <div>
                            <h2 class="admin-section-title">OpenAI</h2>
                            <p class="admin-section-copy">Génération de contenu, descriptions SEO et suggestions de texte.</p>
                        </div>
                        @if($settings['openai_api_key_set'] ?? false)
                            <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Configurée</span>
                        @else
                            <span class="admin-badge admin-badge-muted">Non configurée</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="openai_api_key" class="form-label fw-semibold">Clé API OpenAI</label>
                        <div class="input-group">
                            <input type="password" id="openai_api_key" name="openai_api_key"
                                   placeholder="{{ ($settings['openai_api_key_set'] ?? false) ? '••••••••••••••••••••••••' : 'sk-...' }}"
                                   class="form-control"
                                   autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="toggleVisibility('openai_api_key', this)"
                                    title="Afficher / masquer">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            Laissez vide pour conserver la clé existante.
                            @if($settings['openai_api_key_set'] ?? false)
                                <span class="text-success fw-semibold">Une clé est déjà enregistrée.</span>
                            @endif
                        </div>
                    </div>

                    <div class="admin-actions">
                        <form action="{{ \App\Support\CentralAppUrl::admin('api-settings/test-openai') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-secondary">
                                Tester OpenAI
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SerpAPI --}}
                <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
                    <div class="admin-section-head mb-4">
                        <div>
                            <h2 class="admin-section-title">SerpAPI</h2>
                            <p class="admin-section-copy">Analyse des résultats de recherche Google pour votre positionnement local.</p>
                        </div>
                        @if($settings['serpapi_key_set'] ?? false)
                            <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Configurée</span>
                        @else
                            <span class="admin-badge admin-badge-muted">Non configurée</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="serpapi_key" class="form-label fw-semibold">Clé API SerpAPI</label>
                        <div class="input-group">
                            <input type="password" id="serpapi_key" name="serpapi_key"
                                   placeholder="{{ ($settings['serpapi_key_set'] ?? false) ? '••••••••••••••••••••••••' : 'Votre clé SerpAPI…' }}"
                                   class="form-control"
                                   autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="toggleVisibility('serpapi_key', this)"
                                    title="Afficher / masquer">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            Laissez vide pour conserver la clé existante.
                            @if($settings['serpapi_key_set'] ?? false)
                                <span class="text-success fw-semibold">Une clé est déjà enregistrée.</span>
                            @endif
                        </div>
                    </div>

                    <div class="admin-actions">
                        <form action="{{ \App\Support\CentralAppUrl::admin('api-settings/test-serpapi') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="admin-btn admin-btn-secondary">
                                Tester SerpAPI
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Save all --}}
                <div class="admin-actions justify-content-end">
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="bi bi-save me-1"></i>Enregistrer les clés
                    </button>
                </div>

            </form>

        </div>

        {{-- Right: Météo panel (col-lg-4) --}}
        <div class="col-lg-4 d-grid gap-4">

            <div class="admin-panel admin-panel-strong p-4">
                <p class="admin-kicker mb-3">Météo locale</p>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Actif</span>
                    <span class="fw-semibold" style="font-size:.92rem;">Open-Meteo</span>
                </div>
                <p class="text-muted mb-3" style="font-size:.88rem;">
                    Open-Meteo est intégré sans clé API. Les données météo locales sont disponibles immédiatement.
                </p>
                <div class="admin-note mb-3" style="font-size:.82rem;">
                    <i class="bi bi-geo-alt-fill me-1"></i>Données météo basées sur les coordonnées GPS de votre ville.
                </div>
                <form action="{{ \App\Support\CentralAppUrl::admin('api-settings/test-weather') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-secondary w-100 justify-content-center">
                        Tester la météo
                    </button>
                </form>
            </div>

            <div class="admin-panel p-4">
                <p class="admin-kicker mb-2">Aide</p>
                <ul class="mb-0 ps-3 text-muted" style="font-size:.85rem;line-height:2;">
                    <li>Les clés API sont chiffrées en base de données.</li>
                    <li>Utilisez les boutons "Tester" pour vérifier la connexion.</li>
                    <li>Une clé invalide bloquera la génération de contenu.</li>
                </ul>
            </div>

        </div>
    </div>

</div>

<script>
function toggleVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection
