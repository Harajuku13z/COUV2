<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 1 sur 6</p>
                    <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Informations entreprise</h2>
                </div>
                <div class="hidden rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 md:block">Base identitaire</div>
            </div>

            <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full w-[16.666%] rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Nom de l entreprise</span>
                    <input wire:model="name" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="Ex. Les Toits de l Ouest">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">SIRET</span>
                    <input wire:model="siret" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="14 chiffres">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Activite principale</span>
                    <input wire:model="activity_main" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="Ex. Renovation de toiture, depannage fuite">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Type de metier</span>
                    <select wire:model="activity_type" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white">
            @foreach(['couvreur','plombier','peintre','electricien','elagueur','facadier','custom'] as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
                    </select>
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Telephone</span>
                    <input wire:model="phone" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="06 00 00 00 00">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Email</span>
                    <input wire:model="email" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="contact@exemple.fr">
                </label>
                <label class="space-y-2 md:col-span-2">
                    <span class="text-sm font-medium text-slate-700">Adresse</span>
                    <input wire:model="address" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="12 rue des Artisans">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Ville</span>
                    <input wire:model="city" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="Nantes">
                </label>
                <label class="space-y-2">
                    <span class="text-sm font-medium text-slate-700">Code postal</span>
                    <input wire:model="postal_code" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="44000">
                </label>
            </div>

            <label class="mt-5 block space-y-2">
                <span class="text-sm font-medium text-slate-700">Promesse commerciale</span>
                <textarea wire:model="offer_text" rows="5" class="w-full rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="Ex. Interventions rapides, devis detaille sous 2h et accompagnement local sur mesure."></textarea>
            </label>

            <div class="mt-8 flex justify-end">
                <button wire:click="saveAndContinue" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Continuer</button>
            </div>
        </section>

        <aside class="space-y-6">
            <div class="rounded-[2.5rem] bg-slate-950 p-6 text-slate-100 shadow-[0_18px_70px_rgba(35,49,45,0.14)] md:p-8">
                <p class="text-xs uppercase tracking-[0.28em] text-slate-400">Pourquoi cette etape</p>
                <h3 class="mt-3 text-2xl font-semibold">On fixe la colonne vertebrale du site</h3>
                <p class="mt-4 text-sm leading-7 text-slate-300">
                    Ces informations servent a ton identite publique, a la tonalite des pages, au schema local business et a la coherence de tous les contenus qui seront generes ensuite.
                </p>
                <div class="mt-6 space-y-3 text-sm">
                    <div class="rounded-[1.4rem] bg-white/5 px-4 py-3">Nom + metier : base du positionnement local</div>
                    <div class="rounded-[1.4rem] bg-white/5 px-4 py-3">Telephone + email : canaux de conversion immediats</div>
                    <div class="rounded-[1.4rem] bg-white/5 px-4 py-3">Promesse : fil rouge du copywriting futur</div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/70 bg-white/85 p-6 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Conseil de remplissage</p>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Sois precis sur le coeur d activite. Une formule concrete comme “depannage fuite toiture et renovation couverture” donnera de meilleurs contenus qu un libelle trop generique.
                </p>
            </div>
        </aside>
    </div>
</div>
