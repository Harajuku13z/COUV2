<div class="container-xl pb-5">
    <form wire:submit="importAndContinue" class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 2 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Import des communes par departement</h2>
            </div>
            <span class="setup-pill">Selection simple</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 33.333%"></div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mt-4 mb-0">
                <div class="fw-bold mb-2">Certaines informations sont a corriger avant de continuer.</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Departements a importer</label>

                        <div
                            id="department-builder"
                            class="d-grid gap-2"
                            data-options='@json($available_departments)'
                            data-selected='@json(collect(json_decode($department_codes_payload, true) ?? [])->values()->all())'
                            wire:ignore
                        ></div>

                        <input id="department-codes-payload" type="hidden" wire:model.live="department_codes_payload">

                        <div class="d-flex justify-content-start mt-3">
                            <button id="department-add-row" onclick="window.departmentBuilderAddRow && window.departmentBuilderAddRow()" type="button" class="btn btn-dark rounded-4 fw-semibold">+ Ajouter un departement</button>
                        </div>

                        <div class="form-text">Choisis un ou plusieurs departements. Le systeme importera ensuite toutes les communes de chaque departement choisi.</div>

                        @error('department_codes_payload')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="setup-info-card d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Communes deja importees pour la selection actuelle</span>
                            <span class="fs-4 fw-bold">{{ $this->importedCitiesCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="setup-dark-card h-100">
                    <div class="text-uppercase small opacity-75 fw-semibold">Import automatique</div>
                    <h3 class="h2 fw-bold mt-3">Toutes les communes seront recuperees</h3>
                    <p class="mt-3 mb-0 opacity-75" style="line-height: 1.9;">
                        Tu choisis les departements, puis la plateforme importe automatiquement toutes les communes depuis l API officielle du gouvernement.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button type="submit" class="btn setup-btn-primary">Importer et continuer</button>
        </div>
    </form>
</div>
