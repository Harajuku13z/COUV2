<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration du site | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface-50 text-slate-900">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[32rem] bg-[radial-gradient(circle_at_top_left,_rgba(79,116,101,0.18),_transparent_40%),radial-gradient(circle_at_top_right,_rgba(217,119,6,0.14),_transparent_34%),linear-gradient(180deg,_#f7f7f4_0%,_#fcfcfb_46%,_#f4f1ea_100%)]"></div>
        <div class="absolute left-[8%] top-28 h-40 w-40 rounded-full bg-brand-200/40 blur-3xl"></div>
        <div class="absolute right-[12%] top-40 h-52 w-52 rounded-full bg-amber-200/40 blur-3xl"></div>
    </div>

    <main class="relative py-8 md:py-12">
        <div class="mx-auto max-w-6xl px-4">
            <div class="mb-8 flex flex-col gap-6 rounded-[2.5rem] border border-white/70 bg-white/75 px-6 py-6 shadow-[0_20px_80px_rgba(35,49,45,0.08)] backdrop-blur md:flex-row md:items-end md:justify-between md:px-8">
                <div class="max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.34em]" style="color: var(--brand-primary)">Mise en route guidee</p>
                    <h1 class="mt-3 text-4xl font-semibold leading-tight md:text-5xl" style="font-family: var(--font-heading, 'Sora', sans-serif)">
                        Configure ton site artisan en quelques etapes nettes
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 md:text-lg">
                        On prepare ton identite, ta zone d intervention, tes services, ton branding et tes APIs dans un meme flux.
                        L objectif est simple : arriver vite a une base exploitable, sans friction inutile.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3 md:min-w-[320px]">
                    <div class="rounded-[1.6rem] bg-slate-950 px-4 py-4 text-white">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Temps moyen</p>
                        <p class="mt-2 text-2xl font-semibold">8 min</p>
                    </div>
                    <div class="rounded-[1.6rem] bg-white px-4 py-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Etapes</p>
                        <p class="mt-2 text-2xl font-semibold">6</p>
                    </div>
                    <div class="rounded-[1.6rem] bg-white px-4 py-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Resultat</p>
                        <p class="mt-2 text-2xl font-semibold">Pret a lancer</p>
                    </div>
                </div>
            </div>
        </div>

        <livewire:onboarding.onboarding-wizard />
    </main>
    @livewireScripts
</body>
</html>
