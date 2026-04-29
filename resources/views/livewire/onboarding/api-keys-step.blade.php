<div class="mx-auto max-w-4xl space-y-6 px-4 py-10">
    <h2 class="text-3xl font-semibold">Cles API</h2>
    <input wire:model="openai_api_key" class="w-full rounded-2xl border px-4 py-3" placeholder="OpenAI API key">
    <input wire:model="serpapi_key" class="w-full rounded-2xl border px-4 py-3" placeholder="SerpAPI key">
    <input wire:model="openweather_api_key" class="w-full rounded-2xl border px-4 py-3" placeholder="OpenWeather API key">
    <div class="flex gap-4 text-sm">
        <span>OpenAI: {{ $openai_valid ? 'valide' : 'a tester' }}</span>
        <span>SerpAPI: {{ $serpapi_valid ? 'valide' : 'a tester' }}</span>
        <span>Weather: {{ $openweather_valid ? 'valide' : 'a tester' }}</span>
    </div>
    <div class="flex items-center justify-between">
        <button wire:click="previousStep" class="rounded-full border px-5 py-3">Retour</button>
        <div class="flex gap-3">
            <button wire:click="testKeys" class="rounded-full border px-5 py-3">Tester les cles</button>
            <button wire:click="saveAndContinue" class="rounded-full px-5 py-3 text-white" style="background: var(--brand-primary)">Continuer</button>
        </div>
    </div>
</div>
