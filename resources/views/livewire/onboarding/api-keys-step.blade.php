<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 5 sur 5</div>
                <h2 class="setup-section-title mt-3 mb-0">Cles API et automatisation</h2>
            </div>
            <span class="setup-pill">Integrations</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 100%"></div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">OpenAI API key</label>
                        <input wire:model="openai_api_key" class="form-control setup-form-control" placeholder="sk-...">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">SerpAPI key</label>
                        <input wire:model="serpapi_key" class="form-control setup-form-control" placeholder="Ta cle SerpAPI">
                    </div>

                    {{-- Météo : Open-Meteo (gratuit, sans clé, données Météo-France) --}}
                    <div class="col-12">
                        <div class="rounded-4 px-4 py-3 d-flex align-items-start gap-3"
                             style="background:#eef3ef;border:1px solid rgba(54,84,70,.12);">
                            <div style="font-size:1.4rem;line-height:1;">🇫🇷</div>
                            <div>
                                <div class="fw-bold" style="color:var(--setup-primary);">Météo — Open-Meteo (Météo-France)</div>
                                <div class="text-secondary small mt-1">
                                    Données officielles via <strong>Open-Meteo</strong> (modèle ARPEGE de Météo-France).
                                    Aucune clé requise — gratuit, sans inscription.
                                </div>
                                <a href="https://open-meteo.com" target="_blank" class="small fw-semibold mt-1 d-inline-block"
                                   style="color:var(--setup-primary);">open-meteo.com →</a>
                            </div>
                            <span class="badge rounded-pill ms-auto flex-shrink-0" style="background:#365446;color:#fff;font-size:.75rem;padding:.4em .8em;">
                                ✓ Actif
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="setup-info-card h-100 {{ $openai_valid ? 'border border-success-subtle bg-success-subtle' : '' }}">
                                    <div class="fw-bold">OpenAI</div>
                                    <div class="text-secondary small mt-1">{{ $openai_valid ? '✓ Cle validee' : 'A renseigner' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="setup-info-card h-100 {{ $serpapi_valid ? 'border border-success-subtle bg-success-subtle' : '' }}">
                                    <div class="fw-bold">SerpAPI</div>
                                    <div class="text-secondary small mt-1">{{ $serpapi_valid ? '✓ Cle validee' : 'A renseigner' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="setup-dark-card h-100">
                    <div class="text-uppercase small opacity-75 fw-semibold">A quoi servent ces cles</div>
                    <div class="mt-3 d-grid gap-3">
                        <div>
                            <strong>OpenAI</strong><br>
                            <span class="opacity-75">Generation et personnalisation des contenus SEO.</span>
                        </div>
                        <div>
                            <strong>SerpAPI</strong><br>
                            <span class="opacity-75">Suivi des signaux SEO et de la concurrence locale.</span>
                        </div>
                        <div>
                            <strong>Météo 🇫🇷</strong><br>
                            <span class="opacity-75">Donnees officielles Météo-France via Open-Meteo. Gratuit, aucune cle.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <button wire:click="testKeys" type="button" class="btn btn-outline-dark setup-btn-secondary">Tester les cles</button>
                <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Terminer la configuration →</button>
            </div>
        </div>
    </section>
</div>
