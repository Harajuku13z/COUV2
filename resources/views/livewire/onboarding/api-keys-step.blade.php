<div class="mx-auto max-w-6xl px-4 pb-12">
    <div class="rounded-[2.5rem] border border-white/70 bg-white/85 p-6 shadow-[0_18px_70px_rgba(35,49,45,0.08)] backdrop-blur md:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Etape 5 sur 6</p>
        <h2 class="mt-3 text-3xl font-semibold md:text-4xl">Cles API et automatisation</h2>

        <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-[83.333%] rounded-full" style="background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent))"></div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-4">
                <input wire:model="openai_api_key" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="OpenAI API key">
                <input wire:model="serpapi_key" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="SerpAPI key">
                <input wire:model="openweather_api_key" class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3.5" placeholder="OpenWeather API key">
                <div class="grid gap-3 sm:grid-cols-3 text-sm">
                    <div class="rounded-[1.25rem] px-4 py-3 {{ $openai_valid ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-500' }}">OpenAI · {{ $openai_valid ? 'valide' : 'a tester' }}</div>
                    <div class="rounded-[1.25rem] px-4 py-3 {{ $serpapi_valid ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-500' }}">SerpAPI · {{ $serpapi_valid ? 'valide' : 'a tester' }}</div>
                    <div class="rounded-[1.25rem] px-4 py-3 {{ $openweather_valid ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-500' }}">Weather · {{ $openweather_valid ? 'valide' : 'a tester' }}</div>
                </div>
            </div>

            <aside class="rounded-[2rem] bg-slate-950 p-6 text-slate-100">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-400">A quoi servent ces cles</p>
                <div class="mt-4 space-y-4 text-sm leading-7 text-slate-300">
                    <p><strong class="text-white">OpenAI</strong> pour la generation et la personnalisation des contenus.</p>
                    <p><strong class="text-white">SerpAPI</strong> pour lire les signaux SEO et la concurrence locale.</p>
                    <p><strong class="text-white">Weather</strong> pour contextualiser les pages et les alertes meteo.</p>
                </div>
            </aside>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <button wire:click="previousStep" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Retour</button>
            <div class="flex gap-3">
                <button wire:click="testKeys" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Tester les cles</button>
                <button wire:click="saveAndContinue" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-400/20 transition hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary))">Continuer</button>
            </div>
        </div>
    </div>
</div>
