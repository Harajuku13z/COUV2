<div class="container-xl pb-5">
    <form wire:submit="importAndContinue" class="setup-panel p-4 p-lg-5">
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
                        <label class="form-label fw-semibold text-secondary">Departements cibles</label>
                        <div class="d-grid gap-2">
                            @foreach ($department_rows as $index => $departmentCode)
                                <div class="row g-2 align-items-stretch">
                                    <div class="col-sm-10">
                                        <select wire:model="department_rows.{{ $index }}" class="form-select setup-form-select">
                                            <option value="">Choisir un departement</option>
                                            @foreach ($available_departments as $department)
                                                <option value="{{ $department['code'] }}">{{ $department['code'] }} - {{ $department['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_rows.'.$index) <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-sm-2 d-grid">
                                        <button wire:click="removeDepartmentRow({{ $index }})" type="button" class="btn btn-outline-secondary rounded-4 fw-semibold">
                                            {{ count($department_rows) === 1 ? 'Vider' : 'Retirer' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-start mt-3">
                            <button wire:click="addDepartmentRow" type="button" class="btn btn-dark rounded-4 fw-semibold">+ Ajouter un departement</button>
                        </div>

                        <div class="form-text">Liste officielle des departements via l API geo.api.gouv.fr.</div>

                        @error('department_rows')
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

                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Communes prioritaires</label>
                        <textarea wire:model="priority_cities" rows="5" class="form-control setup-form-control" placeholder="Nantes, Saint-Herblain, Reze, Orvault..."></textarea>
                        @error('priority_cities') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
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
            <button type="submit" class="btn setup-btn-primary">Importer et continuer</button>
        </div>
    </form>
</div>
