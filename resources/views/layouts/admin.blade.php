<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trim($__env->yieldContent('title', 'Admin')).' | '.config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    @php
        $adminHomeUrl = \App\Support\CentralAppUrl::admin();
        $siteHomeUrl = \App\Support\CentralAppUrl::app();
        $navItems = [
            ['url' => \App\Support\CentralAppUrl::admin(), 'path' => '/admin', 'label' => 'Vue d\'ensemble'],
            ['url' => \App\Support\CentralAppUrl::admin('company'), 'path' => '/admin/company', 'label' => 'Mon entreprise'],
            ['url' => \App\Support\CentralAppUrl::admin('zones'), 'path' => '/admin/zones', 'label' => 'Zones & villes'],
            ['url' => \App\Support\CentralAppUrl::admin('services'), 'path' => '/admin/services', 'label' => 'Services'],
            ['url' => \App\Support\CentralAppUrl::admin('pages'), 'path' => '/admin/pages', 'label' => 'Pages SEO'],
            ['url' => \App\Support\CentralAppUrl::admin('leads'), 'path' => '/admin/leads', 'label' => 'Leads'],
            ['url' => \App\Support\CentralAppUrl::admin('testimonials'), 'path' => '/admin/testimonials', 'label' => 'Témoignages'],
            ['url' => \App\Support\CentralAppUrl::admin('blog'), 'path' => '/admin/blog', 'label' => 'Blog'],
            ['url' => \App\Support\CentralAppUrl::admin('branding'), 'path' => '/admin/branding', 'label' => 'Branding'],
            ['url' => \App\Support\CentralAppUrl::admin('api-settings'), 'path' => '/admin/api-settings', 'label' => 'Clés API'],
        ];
        $currentPath = request()->path();
    @endphp
    <div class="flex min-h-screen">
        <aside class="hidden w-72 shrink-0 bg-slate-950 px-6 py-8 text-slate-100 lg:block">
            <a href="{{ $adminHomeUrl }}" class="text-lg font-semibold">Tableau de bord</a>
            <nav class="mt-8 space-y-1 text-sm">
                @foreach($navItems as $item)
                    @php $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path'])); @endphp
                    <a href="{{ $item['url'] }}"
                       class="block rounded-2xl px-4 py-2.5 transition-colors {{ $active ? 'bg-slate-700 text-white' : 'hover:bg-slate-900' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
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
                        <a href="{{ $siteHomeUrl }}" class="rounded-full border border-slate-300 px-4 py-2">Voir le site</a>
                        <form method="POST" action="{{ \App\Support\CentralAppUrl::admin('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-slate-300 px-4 py-2 text-slate-600 hover:bg-slate-50">Deconnexion</button>
                        </form>
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
