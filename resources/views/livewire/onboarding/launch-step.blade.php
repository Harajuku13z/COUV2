<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 6 sur 6</p>
        <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Lancement</h2>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-600">Tout est pret. On peut maintenant lancer l import des zones et la generation initiale des pages.</p>

        <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-full rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-sm">
                <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Pages estimees</p>
                <p class="mt-3 text-4xl font-semibold">{{ $this->estimatedPages }}</p>
            </div>
            <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Cout OpenAI estime</p>
                <p class="mt-3 text-4xl font-semibold">{{ $this->estimatedCost }}</p>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="previousStep" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Retour</button>
            <button wire:click="launch" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Lancer la generation</button>
        </div>
    </div>
</div>
