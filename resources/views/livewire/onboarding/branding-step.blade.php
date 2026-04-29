<div class="mx-auto max-w-4xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Branding</h2>
    <div class="grid gap-4 md:grid-cols-2">
        <input wire:model="brand_primary" class="rounded-2xl border px-4 py-3" placeholder="#4f7465">
        <input wire:model="brand_secondary" class="rounded-2xl border px-4 py-3" placeholder="#23312d">
        <input wire:model="brand_accent" class="rounded-2xl border px-4 py-3" placeholder="#d97706">
        <input wire:model="heading_font" class="rounded-2xl border px-4 py-3" placeholder="Police titres">
        <input wire:model="body_font" class="rounded-2xl border px-4 py-3" placeholder="Police texte">
        <input wire:model="facebook_url" class="rounded-2xl border px-4 py-3" placeholder="Facebook URL">
        <input wire:model="instagram_url" class="rounded-2xl border px-4 py-3" placeholder="Instagram URL">
        <input wire:model="gbp_url" class="rounded-2xl border px-4 py-3" placeholder="Google Business URL">
        <input type="file" wire:model="logo" class="rounded-2xl border px-4 py-3">
        <input type="file" wire:model="favicon" class="rounded-2xl border px-4 py-3">
    </div>
    <div class="rounded-3xl p-6 text-white" style="background: {{ $brand_primary }}">
        Apercu du theme avec {{ $heading_font }} / {{ $body_font }}
    </div>
    <div class="flex items-center justify-between">
        <button wire:click="previousStep" class="rounded-full border px-5 py-3">Retour</button>
        <button wire:click="saveAndContinue" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Continuer</button>
    </div>
</div>
