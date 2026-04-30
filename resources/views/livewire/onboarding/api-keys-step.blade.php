<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 5 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Cles API et automatisation</h2>
            </div>
            <span class="setup-pill">Integrations</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 83.333%"></div>
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
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">OpenWeather API key</label>
                        <input wire:model="openweather_api_key" class="form-control setup-form-control" placeholder="Ta cle meteo">
                    </div>
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="setup-info-card h-100 {{ $openai_valid ? 'border border-success-subtle bg-success-subtle' : '' }}">
                                    <div class="fw-bold">OpenAI</div>
                                    <div class="text-secondary">{{ $openai_valid ? 'Cle validee' : 'A tester' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setup-info-card h-100 {{ $serpapi_valid ? 'border border-success-subtle bg-success-subtle' : '' }}">
                                    <div class="fw-bold">SerpAPI</div>
                                    <div class="text-secondary">{{ $serpapi_valid ? 'Cle validee' : 'A tester' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setup-info-card h-100 {{ $openweather_valid ? 'border border-success-subtle bg-success-subtle' : '' }}">
                                    <div class="fw-bold">Weather</div>
                                    <div class="text-secondary">{{ $openweather_valid ? 'Cle validee' : 'A tester' }}</div>
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
                        <div><strong>OpenAI</strong><br><span class="opacity-75">Pour la generation et la personnalisation des contenus.</span></div>
                        <div><strong>SerpAPI</strong><br><span class="opacity-75">Pour suivre les signaux SEO et la concurrence locale.</span></div>
                        <div><strong>Weather</strong><br><span class="opacity-75">Pour contextualiser les pages et les alertes meteo.</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <button wire:click="testKeys" type="button" class="btn btn-outline-dark setup-btn-secondary">Tester les cles</button>
                <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
            </div>
        </div>
    </section>
</div>
