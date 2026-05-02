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
                            <button id="department-add-row" type="button" class="btn btn-dark rounded-4 fw-semibold">+ Ajouter un departement</button>
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

<script>
    (() => {
        const initDepartmentBuilder = () => {
            const container = document.getElementById('department-builder');
            const addButton = document.getElementById('department-add-row');
            const payloadInput = document.getElementById('department-codes-payload');

            if (!container || !addButton || !payloadInput || container.dataset.initialized === '1') {
                return;
            }

            const options = JSON.parse(container.dataset.options || '[]');
            const selected = JSON.parse(container.dataset.selected || '[]');
            container.dataset.initialized = '1';

            const syncPayload = () => {
                const codes = Array.from(container.querySelectorAll('select[data-department-row]'))
                    .map((select) => select.value.trim())
                    .filter((value, index, array) => value !== '' && array.indexOf(value) === index);

                payloadInput.value = JSON.stringify(codes);
                payloadInput.dispatchEvent(new Event('input', { bubbles: true }));
            };

            const buildSelect = (selectedCode = '') => {
                const row = document.createElement('div');
                row.className = 'row g-2 align-items-stretch';

                const selectCol = document.createElement('div');
                selectCol.className = 'col-sm-10';

                const select = document.createElement('select');
                select.className = 'form-select setup-form-select';
                select.setAttribute('data-department-row', '1');

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Choisir un departement';
                select.appendChild(placeholder);

                options.forEach((department) => {
                    const option = document.createElement('option');
                    option.value = department.code;
                    option.textContent = `${department.code} - ${department.name}`;
                    if (department.code === selectedCode) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });

                select.addEventListener('change', syncPayload);
                selectCol.appendChild(select);

                const removeCol = document.createElement('div');
                removeCol.className = 'col-sm-2 d-grid';

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-outline-secondary rounded-4 fw-semibold';
                removeButton.textContent = 'Retirer';
                removeButton.addEventListener('click', () => {
                    row.remove();
                    if (container.querySelectorAll('[data-department-row]').length === 0) {
                        container.appendChild(buildSelect(''));
                    }
                    syncPayload();
                });

                removeCol.appendChild(removeButton);
                row.appendChild(selectCol);
                row.appendChild(removeCol);

                return row;
            };

            const initialCodes = selected.length > 0 ? selected : [''];
            initialCodes.forEach((code) => container.appendChild(buildSelect(code)));

            addButton.addEventListener('click', () => {
                container.appendChild(buildSelect(''));
                syncPayload();
            });

            syncPayload();
        };

        document.addEventListener('DOMContentLoaded', initDepartmentBuilder);
        document.addEventListener('livewire:navigated', initDepartmentBuilder);
        document.addEventListener('livewire:initialized', initDepartmentBuilder);
    })();
</script>
