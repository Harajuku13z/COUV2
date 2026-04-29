<div class="mx-auto max-w-4xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Informations entreprise</h2>
    <div class="grid gap-4 md:grid-cols-2">
        <input wire:model="name" class="rounded-2xl border px-4 py-3" placeholder="Nom">
        <input wire:model="siret" class="rounded-2xl border px-4 py-3" placeholder="SIRET">
        <input wire:model="activity_main" class="rounded-2xl border px-4 py-3" placeholder="Activite principale">
        <select wire:model="activity_type" class="rounded-2xl border px-4 py-3">
            @foreach(['couvreur','plombier','peintre','electricien','elagueur','facadier','custom'] as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
        <input wire:model="phone" class="rounded-2xl border px-4 py-3" placeholder="Telephone">
        <input wire:model="email" class="rounded-2xl border px-4 py-3" placeholder="Email">
        <input wire:model="address" class="rounded-2xl border px-4 py-3 md:col-span-2" placeholder="Adresse">
        <input wire:model="city" class="rounded-2xl border px-4 py-3" placeholder="Ville">
        <input wire:model="postal_code" class="rounded-2xl border px-4 py-3" placeholder="Code postal">
    </div>
    <textarea wire:model="offer_text" rows="4" class="w-full rounded-2xl border px-4 py-3" placeholder="Texte d'offre"></textarea>
    <div class="flex justify-end">
        <button wire:click="saveAndContinue" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Continuer</button>
    </div>
</div>
