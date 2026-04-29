<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration initiale | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-6xl px-4 py-12">
        <div class="rounded-[2.5rem] bg-white p-8 shadow-sm md:p-12">
            <p class="text-sm uppercase tracking-[0.28em]" style="color:#4f7465">Configuration initiale</p>
            <h1 class="mt-4 text-4xl font-semibold">Le site n est pas encore finalise</h1>
            <p class="mt-4 max-w-3xl text-lg text-slate-600">
                Le domaine est bien relie a l application, mais les tables metier necessaires au wizard ne sont pas encore disponibles sur cette instance.
                Cette page te confirme que la redirection fonctionne et te donne l etat de la configuration serveur actuelle.
            </p>

            <div class="mt-10 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="rounded-[2rem] bg-slate-50 p-6">
                    <h2 class="text-xl font-semibold">Etat des prerequis</h2>
                    <div class="mt-5 space-y-3">
                        @foreach($checks as $label => $ok)
                            <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3">
                                <span class="font-medium">{{ strtoupper($label) }}</span>
                                <span class="{{ $ok ? 'text-emerald-700' : 'text-amber-700' }}">{{ $ok ? 'OK' : 'A creer' }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-[2rem] bg-slate-950 p-6 text-slate-100">
                    <h2 class="text-xl font-semibold">Configuration serveur detectee</h2>
                    <div class="mt-5 space-y-3 text-sm">
                        <div><span class="text-slate-400">APP_URL</span><p class="mt-1">{{ $envInfo['app_url'] }}</p></div>
                        <div><span class="text-slate-400">Domaine principal</span><p class="mt-1">{{ $envInfo['app_domain'] }}</p></div>
                        <div><span class="text-slate-400">Domaine admin</span><p class="mt-1">{{ $envInfo['admin_domain'] ?: 'non defini' }}</p></div>
                        <div><span class="text-slate-400">Base centrale</span><p class="mt-1">{{ $envInfo['db_database'] ?: 'non definie' }}</p></div>
                        <div><span class="text-slate-400">Mail expediteur</span><p class="mt-1">{{ $envInfo['mail_from'] ?: 'non defini' }}</p></div>
                        <div><span class="text-slate-400">Queue</span><p class="mt-1">{{ $envInfo['queue'] ?: 'non definie' }}</p></div>
                        <div><span class="text-slate-400">Cache</span><p class="mt-1">{{ $envInfo['cache'] ?: 'non defini' }}</p></div>
                    </div>
                </section>
            </div>

            <div class="mt-10 rounded-[2rem] border border-amber-200 bg-amber-50 p-6 text-amber-900">
                <p class="font-semibold">Suite recommandee</p>
                <p class="mt-2 text-sm">
                    Finaliser le provisionnement de l instance metier (tables tenant / structure de contenu), puis le wizard complet d onboarding prendra automatiquement le relais sur cette meme URL.
                </p>
            </div>
        </div>
    </main>
</body>
</html>
