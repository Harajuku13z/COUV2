<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 4 sur 6</p>
        <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Branding et presence visuelle</h2>

        <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-[66.666%] rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_340px]">
            <div class="grid gap-4 md:grid-cols-2">
                <input wire:model="brand_primary" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="#4f7465">
                <input wire:model="brand_secondary" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="#23312d">
                <input wire:model="brand_accent" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="#d97706">
                <input wire:model="heading_font" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="Police titres">
                <input wire:model="body_font" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="Police texte">
                <input wire:model="facebook_url" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="Facebook URL">
                <input wire:model="instagram_url" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="Instagram URL">
                <input wire:model="gbp_url" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="Google Business URL">
                <input type="file" wire:model="logo" class="rounded-[1.25rem] border border-dashed border-slate-300 bg-white px-4 py-3.5">
                <input type="file" wire:model="favicon" class="rounded-[1.25rem] border border-dashed border-slate-300 bg-white px-4 py-3.5">
            </div>

            <div class="space-y-4">
                <div class="rounded-[2rem] p-6 text-white shadow-lg" style="background: linear-gradient(135deg, {{ $brand_primary }}, {{ $brand_secondary }})">
                    <p class="text-sm uppercase tracking-[0.24em] text-white/70">Apercu</p>
                    <h3 class="mt-4 text-2xl font-semibold">{{ $heading_font }}</h3>
                    <p class="mt-2 text-sm text-white/80">Ton univers visuel commence a prendre forme.</p>
                    <div class="mt-6 flex gap-3">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold text-slate-950" style="background: {{ $brand_accent }}">Accent</span>
                        <span class="rounded-full border border-white/25 px-3 py-1 text-xs">{{ $body_font }}</span>
                    </div>
                </div>
                <div class="rounded-[1.75rem] bg-slate-50 p-5 text-sm leading-7 text-slate-600">
                    Choisis des couleurs lisibles et une paire typographique simple. Le site doit inspirer confiance avant meme la lecture.
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="previousStep" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Retour</button>
            <button wire:click="saveAndContinue" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Continuer</button>
        </div>
    </div>
</div>
