<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 3 sur 5</div>
                <h2 class="setup-section-title mt-3 mb-0">Services a activer</h2>
            </div>
            <span class="setup-pill">{{ ucfirst($activityType) }}</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 50%"></div>
        </div>

        @if($servicesByCategory->isEmpty())
            <div class="alert alert-warning border-0 rounded-4 mt-4">
                Aucun service trouve. Lance le seeder sur le serveur : <code>php artisan db:seed --class=ServicesSeeder --force</code>
            </div>
        @else
            @foreach($servicesByCategory as $category => $services)
                <div class="mt-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <h3 class="h6 fw-bold mb-0 text-uppercase" style="letter-spacing:.12em;color:var(--setup-primary);">
                            {{ $categoryLabels[$category] ?? ucfirst($category) }}
                        </h3>
                        <span class="badge rounded-pill fw-semibold" style="background:#eef3ef;color:#365446;">
                            {{ $services->count() }} services
                        </span>
                    </div>

                    <div class="row g-2">
                        @foreach($services as $service)
                            <div class="col-12">
                                <div class="setup-info-card d-flex flex-column flex-lg-row align-items-lg-center gap-3"
                                     style="{{ ($selected[$service->id] ?? false) ? 'border-color:rgba(54,84,70,.35);background:#f3faf5;' : '' }}">

                                    <label class="d-flex align-items-center gap-3 fw-semibold mb-0 flex-shrink-0" style="min-width:220px;cursor:pointer;">
                                        <input type="checkbox"
                                               wire:model="selected.{{ $service->id }}"
                                               class="setup-check">
                                        <span>
                                            {{ $service->name }}
                                            @if($service->is_emergency)
                                                <span class="badge ms-1" style="background:#e53e3e;font-size:.6rem;">Urgence</span>
                                            @endif
                                        </span>
                                    </label>

                                    <input wire:model="descriptions.{{ $service->id }}"
                                           class="form-control setup-form-control flex-grow-1"
                                           placeholder="Description personnalisée (optionnel)">

                                    <input wire:model="prices.{{ $service->id }}"
                                           class="form-control setup-form-control"
                                           style="max-width:180px;"
                                           placeholder="Prix indicatif">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-5">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
        </div>
    </section>
</div>
