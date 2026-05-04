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
            <nav class="mt-8 space-y-1 text-sm">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',        'label' => 'Vue d\'ensemble'],
                        ['route' => 'admin.company.edit',     'label' => 'Mon entreprise'],
                        ['route' => 'admin.zones.index',      'label' => 'Zones & villes'],
                        ['route' => 'admin.services.index',   'label' => 'Services'],
                        ['route' => 'admin.pages.index',      'label' => 'Pages SEO'],
                        ['route' => 'admin.leads.index',      'label' => 'Leads'],
                        ['route' => 'admin.testimonials.index','label' => 'Témoignages'],
                        ['route' => 'admin.blog.index',       'label' => 'Blog'],
                        ['route' => 'admin.branding.edit',    'label' => 'Branding'],
                        ['route' => 'admin.api-settings.edit','label' => 'Clés API'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block rounded-2xl px-4 py-2.5 transition-colors
                              {{ request()->routeIs($item['route']) ? 'bg-slate-700 text-white' : 'hover:bg-slate-900' }}">
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
                        <a href="{{ url('/') }}" class="rounded-full border border-slate-300 px-4 py-2">Voir le site</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
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
