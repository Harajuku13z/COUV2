<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 2 sur 6</p>
                <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Zones d intervention</h2>
            </div>
            <div class="hidden rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 md:block">Ciblage geographique</div>
        </div>

        <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-[33.333%] rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="space-y-2">
                        <span class="text-sm font-medium text-slate-700">Departement cible</span>
                        <input wire:model="department_code" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="44">
                    </label>
                    <label class="space-y-2">
                        <span class="text-sm font-medium text-slate-700">Rayon d intervention</span>
                        <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-4">
                            <input wire:model="intervention_radius_km" type="range" min="10" max="100" class="w-full accent-[var(--brand-primary)]">
                            <div class="mt-2 text-sm font-medium text-slate-600">{{ $intervention_radius_km }} km</div>
                        </div>
                    </label>
                </div>

                <label class="block space-y-2">
                    <span class="text-sm font-medium text-slate-700">Communes prioritaires</span>
                    <textarea wire:model="priority_cities" rows="5" class="w-full rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4 outline-none transition focus:border-brand-400 focus:bg-white" placeholder="Nantes, Saint-Herblain, Reze, Orvault..."></textarea>
                </label>

                <div class="flex items-center justify-between rounded-[1.5rem] bg-slate-50 px-4 py-4 text-sm">
                    <span class="text-slate-500">Communes actuellement importees</span>
                    <span class="text-lg font-semibold text-slate-900">{{ $this->importedCitiesCount }}</span>
                </div>
            </div>

            <aside class="rounded-[2rem] bg-slate-950 p-6 text-slate-100">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Impact SEO</p>
                <h3 class="mt-3 text-2xl font-semibold">On dessine ton terrain de jeu local</h3>
                <p class="mt-4 text-sm leading-7 text-slate-300">
                    Le departement et les villes prioritaires servent a importer les communes pertinentes et a hiérarchiser celles qui meriteront d etre generees en premier.
                </p>
            </aside>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="previousStep" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Retour</button>
            <button wire:click="importAndContinue" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Importer et continuer</button>
        </div>
    </div>
</div>
