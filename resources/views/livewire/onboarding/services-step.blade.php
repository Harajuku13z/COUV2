<div class="mx-auto max-w-5xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Services</h2>
    <div class="space-y-4">
        @foreach($services as $service)
            <div class="grid gap-3 rounded-3xl border border-slate-200 bg-white p-5 md:grid-cols-[auto_1fr_180px]">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model="selected.{{ $service->id }}">
                    <span>{{ $service->name }}</span>
                </label>
                <input wire:model="descriptions.{{ $service->id }}" class="rounded-2xl border px-4 py-3" placeholder="Description personnalisee">
                <input wire:model="prices.{{ $service->id }}" class="rounded-2xl border px-4 py-3" placeholder="Prix indicatif">
            </div>
        @endforeach
    </div>
    <div class="flex items-center justify-between">
        <button wire:click="previousStep" class="rounded-full border px-5 py-3">Retour</button>
        <button wire:click="saveAndContinue" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Continuer</button>
    </div>
</div>
