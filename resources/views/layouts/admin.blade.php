<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trim($__env->yieldContent('title', 'Admin')).' | '.config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        <aside class="hidden w-72 shrink-0 bg-slate-950 px-6 py-8 text-slate-100 lg:block">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold">Tableau de bord</a>
            <nav class="mt-10 space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">Vue d'ensemble</a>
                <a href="{{ route('admin.pages.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">Pages</a>
                <a href="{{ route('admin.leads.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">Leads</a>
                <a href="{{ route('admin.branding.edit') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">Branding</a>
                <a href="{{ route('admin.api-settings.edit') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">API</a>
                <a href="{{ route('onboarding') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-900">Onboarding</a>
            </nav>
        </aside>
        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Admin</p>
                        <h1 class="text-xl font-semibold">@yield('title', 'Tableau de bord')</h1>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ url('/') }}" class="rounded-full border border-slate-300 px-4 py-2">Voir le site</a>
                    </div>
                </div>
            </header>
            <main class="mx-auto max-w-7xl px-4 py-8">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
