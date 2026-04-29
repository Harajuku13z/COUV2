<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 3 sur 6</p>
                <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Services a activer</h2>
            </div>
        </div>

        <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-[50%] rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
        </div>

        <div class="mt-8 space-y-4">
        @foreach($services as $service)
            <div class="grid gap-3 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 md:grid-cols-[auto_1fr_200px]">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model="selected.{{ $service->id }}" class="h-5 w-5 rounded border-slate-300 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                    <span class="font-medium">{{ $service->name }}</span>
                </label>
                <input wire:model="descriptions.{{ $service->id }}" class="rounded-[1.1rem] border border-slate-200 bg-white px-4 py-3" placeholder="Description personnalisee">
                <input wire:model="prices.{{ $service->id }}" class="rounded-[1.1rem] border border-slate-200 bg-white px-4 py-3" placeholder="Prix indicatif">
            </div>
        @endforeach
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="previousStep" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Retour</button>
            <button wire:click="saveAndContinue" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Continuer</button>
        </div>
    </div>
</div>
