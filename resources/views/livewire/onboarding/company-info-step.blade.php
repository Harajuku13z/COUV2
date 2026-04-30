<div class="container-xl pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <section class="setup-panel p-4 p-lg-5 h-100">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="setup-kicker">Etape 1 sur 6</div>
                        <h2 class="setup-section-title mt-3 mb-0">Informations entreprise</h2>
                    </div>
                    <span class="setup-pill">Base identitaire</span>
                </div>

                <div class="setup-progress mt-4">
                    <div class="setup-progress-bar" style="width: 16.666%"></div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Nom de l entreprise</label>
                        <input wire:model="name" class="form-control setup-form-control" placeholder="Ex. Les Toits de l Ouest">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">SIRET</label>
                        <input wire:model="siret" class="form-control setup-form-control" placeholder="14 chiffres">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Activite principale</label>
                        <input wire:model="activity_main" class="form-control setup-form-control" placeholder="Ex. Renovation de toiture, depannage fuite">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Type de metier</label>
                        <select wire:model="activity_type" class="form-select setup-form-select">
                            @foreach(['couvreur', 'plombier', 'peintre', 'electricien', 'elagueur', 'facadier', 'custom'] as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Telephone</label>
                        <input wire:model="phone" class="form-control setup-form-control" placeholder="06 00 00 00 00">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Email</label>
                        <input wire:model="email" class="form-control setup-form-control" placeholder="contact@exemple.fr">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Adresse</label>
                        <input wire:model="address" class="form-control setup-form-control" placeholder="12 rue des Artisans">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Ville</label>
                        <input wire:model="city" class="form-control setup-form-control" placeholder="Nantes">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Code postal</label>
                        <input wire:model="postal_code" class="form-control setup-form-control" placeholder="44000">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Promesse commerciale</label>
                        <textarea wire:model="offer_text" rows="5" class="form-control setup-form-control" placeholder="Ex. Interventions rapides, devis detaille sous 2h et accompagnement local sur mesure."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <div class="setup-dark-card mb-4">
                <div class="text-uppercase small opacity-75 fw-semibold">Pourquoi cette etape</div>
                <h3 class="h2 fw-bold mt-3">On fixe la colonne vertebrale du site</h3>
                <p class="mt-3 mb-0 opacity-75" style="line-height: 1.9;">
                    Ces informations servent a ton identite publique, au schema local business et a la coherence de tous les contenus qui seront generes ensuite.
                </p>
                <div class="mt-4 d-grid gap-2">
                    <div class="rounded-4 px-3 py-3 bg-white bg-opacity-10">Nom + metier : base du positionnement local</div>
                    <div class="rounded-4 px-3 py-3 bg-white bg-opacity-10">Telephone + email : conversion immediate</div>
                    <div class="rounded-4 px-3 py-3 bg-white bg-opacity-10">Promesse : fil rouge du copywriting</div>
                </div>
            </div>

            <div class="setup-sidecard p-4">
                <div class="fw-bold mb-2">Conseil de remplissage</div>
                <p class="text-secondary mb-0" style="line-height: 1.85;">
                    Sois precis sur le coeur d activite. Une formule concrete donne ensuite des pages plus credibles et mieux ciblees.
                </p>
            </div>
        </div>
    </div>
</div>
