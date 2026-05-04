<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trim($__env->yieldContent('title', 'Admin')).' | '.config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-bg: #f4f0e8;
            --admin-surface: rgba(255, 255, 255, 0.84);
            --admin-surface-strong: #ffffff;
            --admin-border: rgba(37, 54, 47, 0.1);
            --admin-ink: #16241f;
            --admin-muted: #647067;
            --admin-primary: #335546;
            --admin-primary-soft: rgba(51, 85, 70, 0.08);
            --admin-secondary: #21332d;
            --admin-accent: #c7772d;
            --admin-accent-soft: rgba(199, 119, 45, 0.12);
            --admin-danger: #b74d4d;
            --admin-success: #2f7a5b;
            --admin-shadow: 0 28px 70px rgba(29, 41, 35, 0.08);
            --admin-radius: 28px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--admin-ink);
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(199, 119, 45, 0.12), transparent 24%),
                radial-gradient(circle at 80% 0%, rgba(51, 85, 70, 0.14), transparent 28%),
                linear-gradient(180deg, #f8f4ec 0%, #f4f0e8 45%, #f2f5ef 100%);
        }

        .admin-app {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            display: none;
            width: 310px;
            min-height: 100vh;
            padding: 28px;
            background:
                linear-gradient(180deg, rgba(18, 27, 24, 0.98), rgba(27, 39, 34, 0.95)),
                linear-gradient(135deg, rgba(199, 119, 45, 0.15), transparent 40%);
            color: #f4f0e8;
        }

        .admin-brand {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 32px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
        }

        .admin-brand-kicker {
            margin: 0 0 10px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: rgba(244, 240, 232, 0.72);
        }

        .admin-brand-title {
            margin: 0;
            font-family: "Fraunces", serif;
            font-size: 1.55rem;
            line-height: 1.1;
            color: #fff7ed;
        }

        .admin-brand-copy {
            margin: 10px 0 0;
            font-size: 0.92rem;
            line-height: 1.6;
            color: rgba(244, 240, 232, 0.72);
        }

        .admin-sidebar-nav {
            margin-top: 26px;
            display: grid;
            gap: 8px;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border: 1px solid transparent;
            border-radius: 20px;
            padding: 14px 16px;
            color: rgba(244, 240, 232, 0.86);
            text-decoration: none;
            transition: 180ms ease;
        }

        .admin-nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .admin-nav-link.is-active {
            background: linear-gradient(135deg, rgba(255, 247, 237, 0.16), rgba(199, 119, 45, 0.16));
            border-color: rgba(255, 247, 237, 0.1);
            color: #ffffff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .admin-nav-text {
            display: grid;
            gap: 2px;
        }

        .admin-nav-label {
            font-size: 0.96rem;
            font-weight: 700;
        }

        .admin-nav-meta {
            font-size: 0.78rem;
            color: rgba(244, 240, 232, 0.56);
        }

        .admin-nav-link.is-active .admin-nav-meta {
            color: rgba(255, 247, 237, 0.72);
        }

        .admin-nav-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 247, 237, 0.92);
            font-size: 0.78rem;
            font-weight: 800;
        }

        .admin-sidebar-footer {
            margin-top: auto;
            padding-top: 22px;
        }

        .admin-content {
            flex: 1;
            min-width: 0;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(18px);
            background: rgba(248, 244, 236, 0.7);
            border-bottom: 1px solid rgba(37, 54, 47, 0.07);
        }

        .admin-topbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 18px 20px;
        }

        .admin-mobile-nav {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 0 20px 16px;
            scrollbar-width: none;
        }

        .admin-mobile-nav::-webkit-scrollbar {
            display: none;
        }

        .admin-mobile-link {
            white-space: nowrap;
            border: 1px solid rgba(37, 54, 47, 0.08);
            border-radius: 999px;
            padding: 10px 14px;
            text-decoration: none;
            color: var(--admin-secondary);
            background: rgba(255, 255, 255, 0.74);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .admin-mobile-link.is-active {
            color: #fff;
            background: linear-gradient(135deg, var(--admin-primary), #436a58);
            border-color: transparent;
            box-shadow: 0 14px 28px rgba(51, 85, 70, 0.18);
        }

        .admin-kicker {
            margin: 0 0 8px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--admin-primary);
        }

        .admin-page-title {
            margin: 0;
            font-family: "Fraunces", serif;
            font-size: clamp(1.9rem, 3vw, 2.8rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            color: var(--admin-secondary);
        }

        .admin-page-copy {
            margin: 8px 0 0;
            max-width: 700px;
            color: var(--admin-muted);
            font-size: 0.98rem;
            line-height: 1.7;
        }

        .admin-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .admin-shell-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 20px 48px;
        }

        .admin-panel {
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius);
            background: var(--admin-surface);
            box-shadow: var(--admin-shadow);
            backdrop-filter: blur(18px);
        }

        .admin-panel-strong {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.88)),
                linear-gradient(135deg, rgba(199, 119, 45, 0.04), transparent);
        }

        .admin-panel-dark {
            color: #f8f4ec;
            background:
                linear-gradient(180deg, rgba(25, 38, 33, 0.98), rgba(31, 49, 43, 0.95)),
                radial-gradient(circle at top right, rgba(199, 119, 45, 0.22), transparent 30%);
        }

        .admin-stat-grid {
            display: grid;
            gap: 18px;
        }

        .admin-stat-card {
            padding: 24px;
        }

        .admin-stat-label {
            margin: 0;
            color: var(--admin-muted);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .admin-stat-value {
            margin: 14px 0 0;
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 800;
            color: var(--admin-secondary);
        }

        .admin-stat-note {
            margin: 12px 0 0;
            font-size: 0.85rem;
            color: var(--admin-muted);
        }

        .admin-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(37, 54, 47, 0.08);
            color: var(--admin-secondary);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .admin-btn,
        .admin-link-btn,
        .admin-app button,
        .admin-app select,
        .admin-app input,
        .admin-app textarea {
            font-family: "Manrope", sans-serif;
        }

        .admin-btn,
        .admin-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 999px;
            padding: 12px 18px;
            border: 1px solid transparent;
            font-size: 0.92rem;
            font-weight: 800;
            text-decoration: none;
            transition: 180ms ease;
        }

        .admin-btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--admin-primary), #4a715f);
            box-shadow: 0 16px 30px rgba(51, 85, 70, 0.2);
        }

        .admin-btn-primary:hover,
        .admin-btn-primary:focus {
            color: #fff;
            transform: translateY(-1px);
        }

        .admin-btn-secondary {
            color: var(--admin-secondary);
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(37, 54, 47, 0.1);
        }

        .admin-btn-secondary:hover,
        .admin-btn-danger:hover,
        .admin-btn-warning:hover {
            transform: translateY(-1px);
        }

        .admin-btn-warning {
            color: #8a4a16;
            background: #fff7ed;
            border-color: rgba(199, 119, 45, 0.24);
        }

        .admin-btn-danger {
            color: #8e3030;
            background: #fff1f1;
            border-color: rgba(183, 77, 77, 0.22);
        }

        .admin-section-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .admin-section-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--admin-secondary);
        }

        .admin-section-copy {
            margin: 6px 0 0;
            color: var(--admin-muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .admin-note {
            border-radius: 22px;
            padding: 18px 20px;
            background: rgba(51, 85, 70, 0.05);
            border: 1px solid rgba(51, 85, 70, 0.08);
            color: var(--admin-secondary);
        }

        .admin-app input[type="text"],
        .admin-app input[type="email"],
        .admin-app input[type="password"],
        .admin-app input[type="url"],
        .admin-app input[type="tel"],
        .admin-app select,
        .admin-app textarea {
            width: 100%;
            border-radius: 18px;
            border: 1px solid rgba(37, 54, 47, 0.1);
            background: rgba(255, 255, 255, 0.92);
            padding: 14px 16px;
            font-size: 0.95rem;
            color: var(--admin-secondary);
            box-sizing: border-box;
            outline: none;
            transition: 160ms ease;
        }

        .admin-app input:focus,
        .admin-app select:focus,
        .admin-app textarea:focus {
            border-color: rgba(51, 85, 70, 0.35);
            box-shadow: 0 0 0 4px rgba(51, 85, 70, 0.08);
        }

        .admin-table-wrap {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 760px;
        }

        .admin-table thead th {
            padding: 0 14px 16px;
            border-bottom: 1px solid rgba(37, 54, 47, 0.08);
            color: var(--admin-muted);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-table tbody td {
            padding: 18px 14px;
            border-bottom: 1px solid rgba(37, 54, 47, 0.06);
            vertical-align: top;
        }

        .admin-table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.42);
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .admin-badge-success {
            color: #1f6c4f;
            background: rgba(47, 122, 91, 0.12);
        }

        .admin-badge-muted {
            color: #59655f;
            background: rgba(87, 101, 93, 0.1);
        }

        .admin-badge-accent {
            color: #8a4a16;
            background: rgba(199, 119, 45, 0.14);
        }

        .admin-alert {
            margin-bottom: 24px;
            border-radius: 24px;
            border: 1px solid rgba(47, 122, 91, 0.14);
            background: rgba(47, 122, 91, 0.08);
            color: #24573f;
            padding: 16px 18px;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .admin-alert-error {
            border-color: rgba(183, 77, 77, 0.18);
            background: rgba(183, 77, 77, 0.08);
            color: #8e3030;
        }

        @media (min-width: 1024px) {
            .admin-sidebar {
                display: flex;
                flex-direction: column;
            }

            .admin-mobile-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $adminHomeUrl = \App\Support\CentralAppUrl::admin();
        $siteHomeUrl = \App\Support\CentralAppUrl::app();
        $navItems = [
            ['url' => \App\Support\CentralAppUrl::admin(), 'path' => '/admin', 'label' => 'Vue d\'ensemble', 'meta' => 'KPI et activite'],
            ['url' => \App\Support\CentralAppUrl::admin('company'), 'path' => '/admin/company', 'label' => 'Entreprise', 'meta' => 'Profil et ton'],
            ['url' => \App\Support\CentralAppUrl::admin('zones'), 'path' => '/admin/zones', 'label' => 'Zones', 'meta' => 'Departements et villes'],
            ['url' => \App\Support\CentralAppUrl::admin('services'), 'path' => '/admin/services', 'label' => 'Services', 'meta' => 'Offres et activations'],
            ['url' => \App\Support\CentralAppUrl::admin('pages'), 'path' => '/admin/pages', 'label' => 'Pages SEO', 'meta' => 'Production locale'],
            ['url' => \App\Support\CentralAppUrl::admin('leads'), 'path' => '/admin/leads', 'label' => 'Leads', 'meta' => 'Demandes entrantes'],
            ['url' => \App\Support\CentralAppUrl::admin('testimonials'), 'path' => '/admin/testimonials', 'label' => 'Avis', 'meta' => 'Preuve sociale'],
            ['url' => \App\Support\CentralAppUrl::admin('blog'), 'path' => '/admin/blog', 'label' => 'Blog', 'meta' => 'Contenu editorial'],
            ['url' => \App\Support\CentralAppUrl::admin('branding'), 'path' => '/admin/branding', 'label' => 'Branding', 'meta' => 'Couleurs et assets'],
            ['url' => \App\Support\CentralAppUrl::admin('api-settings'), 'path' => '/admin/api-settings', 'label' => 'APIs', 'meta' => 'Cles et connexions'],
        ];
        $currentPath = request()->path();
        $currentItem = collect($navItems)->first(function (array $item) use ($currentPath): bool {
            return ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
        });
    @endphp

    <div class="admin-app">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <p class="admin-brand-kicker">Pilotage</p>
                <a href="{{ $adminHomeUrl }}" style="text-decoration:none;">
                    <h1 class="admin-brand-title">Console Artisan SEO</h1>
                </a>
                <p class="admin-brand-copy">Un back-office plus clair pour piloter ta visibilite locale, tes zones et tes leads sans friction.</p>
            </div>

            <nav class="admin-sidebar-nav">
                @foreach ($navItems as $index => $item)
                    @php
                        $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
                    @endphp
                    <a href="{{ $item['url'] }}" class="admin-nav-link{{ $active ? ' is-active' : '' }}">
                        <span class="admin-nav-text">
                            <span class="admin-nav-label">{{ $item['label'] }}</span>
                            <span class="admin-nav-meta">{{ $item['meta'] }}</span>
                        </span>
                        <span class="admin-nav-badge">{{ $index + 1 }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="admin-sidebar-footer">
                <div class="admin-brand">
                    <p class="admin-brand-kicker">Domaine</p>
                    <p class="admin-brand-copy" style="margin-top:0;">{{ parse_url($siteHomeUrl, PHP_URL_HOST) }}</p>
                    <div class="admin-actions" style="margin-top:16px;">
                        <a href="{{ $siteHomeUrl }}" class="admin-link-btn admin-btn-secondary">Voir le site</a>
                    </div>
                </div>
            </div>
        </aside>

        <div class="admin-content admin-app">
            <header class="admin-topbar">
                <div class="admin-topbar-inner">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="admin-kicker">Administration</p>
                            <h2 class="admin-page-title" style="font-size:clamp(1.45rem,2vw,2rem);">@yield('title', 'Tableau de bord')</h2>
                            @if ($currentItem)
                                <p class="admin-page-copy" style="max-width:none;">{{ $currentItem['meta'] }}</p>
                            @endif
                        </div>
                        <div class="admin-actions">
                            <a href="{{ $siteHomeUrl }}" class="admin-link-btn admin-btn-secondary">Voir le site</a>
                            <form method="POST" action="{{ \App\Support\CentralAppUrl::admin('logout') }}">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-danger">Deconnexion</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="admin-mobile-nav">
                    @foreach ($navItems as $item)
                        @php
                            $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
                        @endphp
                        <a href="{{ $item['url'] }}" class="admin-mobile-link{{ $active ? ' is-active' : '' }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </header>

            <main class="admin-shell-main">
                @if (session('status'))
                    <div class="admin-alert">{{ session('status') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
