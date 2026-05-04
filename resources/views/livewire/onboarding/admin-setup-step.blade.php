<div class="container-xl pb-5">
    <section class="setup-panel p-4 p-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="setup-kicker">Etape 5 sur 6</div>
                <h2 class="setup-section-title mt-3 mb-0">Acces admin et email</h2>
            </div>
            <span class="setup-pill">Securite</span>
        </div>

        <div class="setup-progress mt-4">
            <div class="setup-progress-bar" style="width: 83.333%"></div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mt-4 mb-0">
                <div class="fw-bold mb-2">Certaines informations sont a corriger.</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 mt-1">
            {{-- Identifiants admin --}}
            <div class="col-12">
                <h3 class="h6 fw-bold text-uppercase mb-3" style="letter-spacing:.12em;color:var(--setup-primary);">
                    Identifiants de connexion admin
                </h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Email admin</label>
                        <input wire:model="admin_email"
                               type="email"
                               class="form-control setup-form-control"
                               placeholder="admin@monsite.fr">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Mot de passe</label>
                        <input wire:model="admin_password"
                               type="password"
                               class="form-control setup-form-control"
                               placeholder="Min. 8 caracteres"
                               autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Confirmer le mot de passe</label>
                        <input wire:model="admin_password_confirmation"
                               type="password"
                               class="form-control setup-form-control"
                               placeholder="Repeter le mot de passe"
                               autocomplete="new-password">
                    </div>
                </div>
            </div>

            {{-- Config email --}}
            <div class="col-12">
                <h3 class="h6 fw-bold text-uppercase mb-3 mt-2" style="letter-spacing:.12em;color:var(--setup-primary);">
                    Configuration email (SMTP)
                </h3>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-secondary">Serveur SMTP</label>
                        <input wire:model="mail_host"
                               class="form-control setup-form-control"
                               placeholder="smtp.hostinger.com">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-secondary">Port</label>
                        <input wire:model="mail_port"
                               class="form-control setup-form-control"
                               placeholder="465">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-secondary">Chiffrement</label>
                        <select wire:model="mail_encryption" class="form-select setup-form-select">
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                            <option value="">Aucun</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Utilisateur SMTP</label>
                        <input wire:model="mail_username"
                               class="form-control setup-form-control"
                               placeholder="ton@email.fr">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Mot de passe SMTP</label>
                        <input wire:model="mail_password"
                               type="password"
                               class="form-control setup-form-control"
                               placeholder="Mot de passe ou app password"
                               autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Adresse expediteur</label>
                        <input wire:model="mail_from_address"
                               class="form-control setup-form-control"
                               placeholder="contact@monsite.fr">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Nom expediteur</label>
                        <input wire:model="mail_from_name"
                               class="form-control setup-form-control"
                               placeholder="Mon Entreprise">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-5">
            <button wire:click="previousStep" type="button" class="btn btn-outline-secondary setup-btn-secondary">Retour</button>
            <button wire:click="saveAndContinue" type="button" class="btn setup-btn-primary">Continuer</button>
        </div>
    </section>
</div>
