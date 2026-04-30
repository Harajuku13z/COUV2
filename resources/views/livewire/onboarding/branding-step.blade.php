<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 4 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Branding et presence visuelle</h2>
            </div>
            <span class="setup-pill">Image de marque</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 66.666%"></div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Couleur principale</label>
                        <input wire:model="brand_primary" class="form-control setup-form-control" placeholder="#4f7465">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Couleur secondaire</label>
                        <input wire:model="brand_secondary" class="form-control setup-form-control" placeholder="#23312d">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Couleur accent</label>
                        <input wire:model="brand_accent" class="form-control setup-form-control" placeholder="#d97706">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Police titres</label>
                        <input wire:model="heading_font" class="form-control setup-form-control" placeholder="Ex. Manrope">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Police texte</label>
                        <input wire:model="body_font" class="form-control setup-form-control" placeholder="Ex. Inter">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Facebook URL</label>
                        <input wire:model="facebook_url" class="form-control setup-form-control" placeholder="https://facebook.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Instagram URL</label>
                        <input wire:model="instagram_url" class="form-control setup-form-control" placeholder="https://instagram.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Google Business URL</label>
                        <input wire:model="gbp_url" class="form-control setup-form-control" placeholder="https://g.page/...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Logo</label>
                        <input type="file" wire:model="logo" class="form-control setup-form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Favicon</label>
                        <input type="file" wire:model="favicon" class="form-control setup-form-control">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="setup-dark-card">
                    <div class="text-uppercase small opacity-75 fw-semibold">Apercu rapide</div>
                    <h3 class="h2 fw-bold mt-3 mb-2">{{ $heading_font ?: 'Titres du site' }}</h3>
                    <p class="opacity-75 mb-4">Ton univers visuel commence a prendre forme et doit rester lisible, simple et memorisable.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill px-3 py-2 text-dark" style="background: {{ $brand_accent ?: '#d8892b' }}">Accent</span>
                        <span class="badge rounded-pill px-3 py-2 border border-light-subtle">{{ $body_font ?: 'Texte courant' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
        </div>
    </section>
</div>
