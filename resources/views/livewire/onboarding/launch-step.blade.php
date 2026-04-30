<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 6 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-2">Lancement</h2>
                <p class="text-secondary mb-0" style="line-height: 1.85;">
                    Tout est pret. On peut maintenant lancer l import des zones et la generation initiale des pages.
                </p>
            </div>
            <span class="setup-pill">Publication initiale</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 100%"></div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-6">
                <div class="setup-dark-card h-100">
                    <div class="text-uppercase small opacity-75 fw-semibold">Pages estimees</div>
                    <div class="display-4 fw-bold mt-3">{{ $this->estimatedPages }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="setup-sidecard p-4 h-100">
                    <div class="text-uppercase small text-secondary fw-semibold">Cout OpenAI estime</div>
                    <div class="display-4 fw-bold mt-3">{{ $this->estimatedCost }}</div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="launch" type="button" class="btn setup-btn-primary">Lancer la generation</button>
        </div>
    </section>
</div>
