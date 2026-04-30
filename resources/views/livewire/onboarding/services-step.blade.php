<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 3 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Services a activer</h2>
            </div>
            <span class="setup-pill">Catalogue metier</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 50%"></div>
        </div>

        <div class="row g-3 mt-1">
            @foreach($services as $service)
                <div class="col-12">
                    <div class="setup-info-card">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-3">
                                <label class="d-flex align-items-center gap-3 fw-semibold">
                                    <input type="checkbox" wire:model="selected.{{ $service->id }}" class="setup-check">
                                    <span>{{ $service->name }}</span>
                                </label>
                            </div>
                            <div class="col-lg-5">
                                <input wire:model="descriptions.{{ $service->id }}" class="form-control setup-form-control" placeholder="Description personnalisee">
                            </div>
                            <div class="col-lg-4">
                                <input wire:model="prices.{{ $service->id }}" class="form-control setup-form-control" placeholder="Prix indicatif">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
        </div>
    </section>
</div>
