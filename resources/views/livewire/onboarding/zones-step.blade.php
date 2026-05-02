<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 2 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Zones d intervention</h2>
            </div>
            <span class="setup-pill">Ciblage geographique</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 33.333%"></div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Departements cibles</label>
                        <input
                            wire:model.live.debounce.300ms="department_search"
                            class="form-control setup-form-control"
                            placeholder="Ex. 44, Loire-Atlantique, 75, Paris"
                        >
                        <div class="form-text">Recherche officielle via l API geo.api.gouv.fr. Tu peux ajouter plusieurs departements.</div>

                        @error('selected_departments')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        @if (count($selected_departments) > 0)
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @foreach ($selected_departments as $department)
                                    <span class="badge rounded-pill text-bg-dark px-3 py-2 d-inline-flex align-items-center gap-2">
                                        <span>{{ $department['code'] }} · {{ $department['name'] }}</span>
                                        <button wire:click="removeDepartment('{{ $department['code'] }}')" type="button" class="btn btn-sm btn-light rounded-pill px-2 py-0">x</button>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if ($department_search !== '' && count($department_suggestions) > 0)
                            <div class="setup-sidecard p-2 mt-3">
                                <div class="list-group list-group-flush">
                                    @foreach ($department_suggestions as $department)
                                        <button
                                            wire:click='addDepartment(@js($department["code"]), @js($department["name"]))'
                                            type="button"
                                            class="list-group-item list-group-item-action border-0 rounded-3"
                                        >
                                            <span class="fw-semibold">{{ $department['code'] }}</span>
                                            <span class="text-secondary">· {{ $department['name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Rayon d intervention</label>
                        <div class="setup-info-card h-100">
                            <input wire:model="intervention_radius_km" type="range" min="10" max="100" class="form-range mt-1">
                            <div class="fw-semibold text-secondary">{{ $intervention_radius_km }} km</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Communes prioritaires</label>
                        <textarea wire:model="priority_cities" rows="5" class="form-control setup-form-control" placeholder="Nantes, Saint-Herblain, Reze, Orvault..."></textarea>
                    </div>
                    <div class="col-12">
                        <div class="setup-info-card d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Communes actuellement importees</span>
                            <span class="fs-4 fw-bold">{{ $this->importedCitiesCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="setup-dark-card h-100">
                    <div class="text-uppercase small opacity-75 fw-semibold">Impact SEO</div>
                    <h3 class="h2 fw-bold mt-3">On dessine ton terrain de jeu local</h3>
                    <p class="mt-3 mb-0 opacity-75" style="line-height: 1.9;">
                        Les departements choisis sont recuperes depuis l API officielle du gouvernement, puis utilises pour importer les communes utiles et prioriser les futures pages locales.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="importAndContinue" type="button" class="btn setup-btn-primary">Importer et continuer</button>
        </div>
    </section>
</div>
