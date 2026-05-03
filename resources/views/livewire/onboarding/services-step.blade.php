<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 3 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Services a activer</h2>
            </div>
            <span class="setup-pill">{{ ucfirst($activityType) }}</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 50%"></div>
        </div>

        @if($servicesByCategory->isEmpty())
            <div class="alert alert-warning border-0 rounded-4 mt-4">
                Aucun service trouve pour ce metier. Verifie que le seeder a ete execute.
            </div>
        @else
            @foreach($servicesByCategory as $category => $services)
                <div class="mt-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="setup-kicker">{{ str_replace('_', ' ', $category) }}</span>
                        <span class="badge rounded-pill bg-light text-secondary border fw-semibold">{{ $services->count() }} services</span>
                    </div>

                    <div class="row g-2">
                        @foreach($services as $service)
                            <div class="col-12">
                                <div class="setup-info-card {{ ($selected[$service->id] ?? false) ? 'border-success border-opacity-50' : '' }}">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-lg-3">
                                            <label class="d-flex align-items-center gap-3 fw-semibold cursor-pointer">
                                                <input type="checkbox"
                                                       wire:model="selected.{{ $service->id }}"
                                                       class="setup-check">
                                                <span>
                                                    {{ $service->name }}
                                                    @if($service->is_emergency)
                                                        <span class="badge bg-danger ms-1" style="font-size:0.65rem;">Urgence</span>
                                                    @endif
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-lg-5">
                                            <input wire:model="descriptions.{{ $service->id }}"
                                                   class="form-control setup-form-control"
                                                   placeholder="Description personnalisee">
                                        </div>
                                        <div class="col-lg-4">
                                            <input wire:model="prices.{{ $service->id }}"
                                                   class="form-control setup-form-control"
                                                   placeholder="Prix indicatif (ex: A partir de 80€)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
        </div>
    </section>
</div>
