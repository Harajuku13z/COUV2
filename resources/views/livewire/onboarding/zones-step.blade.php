<div class="mx-auto max-w-4xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Zones d'intervention</h2>
    <div class="grid gap-4 md:grid-cols-2">
        <input wire:model="department_code" class="rounded-2xl border px-4 py-3" placeholder="Departement">
        <input wire:model="intervention_radius_km" type="range" min="10" max="100" class="rounded-2xl border px-4 py-3">
    </div>
    <textarea wire:model="priority_cities" rows="4" class="w-full rounded-2xl border px-4 py-3" placeholder="Communes prioritaires, separees par des virgules"></textarea>
    <p class="text-sm text-slate-500">Communes actuellement importees : {{ $this->importedCitiesCount }}</p>
    <div class="flex items-center justify-between">
        <button wire:click="previousStep" class="rounded-full border px-5 py-3">Retour</button>
        <button wire:click="importAndContinue" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Importer et continuer</button>
    </div>
</div>
