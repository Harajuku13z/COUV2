<div class="mx-auto max-w-4xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Lancement</h2>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">Pages estimees: {{ $this->estimatedPages }}</div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">Cout OpenAI estime: {{ $this->estimatedCost }}</div>
    </div>
    <div class="flex items-center justify-between">
        <button wire:click="previousStep" class="rounded-full border px-5 py-3">Retour</button>
        <button wire:click="launch" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Lancer la generation</button>
    </div>
</div>
