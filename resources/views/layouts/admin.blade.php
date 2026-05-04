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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-body-font-family: "Manrope", sans-serif;
            --bs-body-bg: #f5f1e9;
            --bs-body-color: #16241f;
            --bs-primary: #335546;
            --bs-primary-rgb: 51, 85, 70;
            --bs-secondary-color: #647067;
            --bs-border-color: rgba(37, 54, 47, 0.1);
            --admin-bg: #f4f0e8;
            --admin-surface: rgba(255, 255, 255, 0.84);
            --admin-surface-strong: rgba(255, 255, 255, 0.94);
            --admin-border: rgba(37, 54, 47, 0.1);
            --admin-ink: #16241f;
            --admin-muted: #647067;
            --admin-primary: #335546;
            --admin-secondary: #21332d;
            --admin-accent: #c7772d;
            --admin-danger: #b74d4d;
            --admin-success: #2f7a5b;
            --admin-shadow: 0 28px 70px rgba(29, 41, 35, 0.08);
            --admin-radius: 28px;
        }

        body {
            min-height: 100vh;
            color: var(--admin-ink);
            background:
                radial-gradient(circle at top left, rgba(199, 119, 45, 0.12), transparent 24%),
                radial-gradient(circle at 80% 0%, rgba(51, 85, 70, 0.14), transparent 28%),
                linear-gradient(180deg, #f8f4ec 0%, #f4f0e8 45%, #f2f5ef 100%);
        }

        .admin-sidebar-shell {
            min-height: calc(100vh - 2rem);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 32px;
            background:
                linear-gradient(180deg, rgba(18, 27, 24, 0.98), rgba(27, 39, 34, 0.95)),
                linear-gradient(135deg, rgba(199, 119, 45, 0.15), transparent 40%);
            color: #f4f0e8;
            box-shadow: 0 24px 70px rgba(18, 27, 24, 0.16);
        }

        .admin-brand {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
        }

        .admin-brand-kicker,
        .admin-kicker {
            margin: 0 0 .5rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .28em;
            text-transform: uppercase;
            color: rgba(244, 240, 232, 0.72);
        }

        .admin-brand-title,
        .admin-page-title {
            margin: 0;
            font-family: "Fraunces", serif;
            letter-spacing: -.02em;
        }

        .admin-brand-title {
            font-size: 1.5rem;
            color: #fff7ed;
            line-height: 1.1;
        }

        .admin-brand-copy {
            margin: .75rem 0 0;
            font-size: .92rem;
            line-height: 1.6;
            color: rgba(244, 240, 232, 0.72);
        }

        .admin-shell-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid rgba(37, 54, 47, 0.08);
            backdrop-filter: blur(16px);
            background: rgba(248, 244, 236, 0.84);
        }

        .admin-page-title {
            font-size: clamp(1.35rem, 2vw, 2rem);
            color: var(--admin-secondary);
            line-height: 1.05;
        }

        .admin-page-copy {
            margin: .45rem 0 0;
            max-width: 720px;
            color: var(--admin-muted);
            font-size: .96rem;
            line-height: 1.7;
        }

        .admin-nav-pills .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border-radius: 20px;
            padding: .9rem 1rem;
            color: rgba(244, 240, 232, 0.9);
            font-weight: 700;
        }

        .admin-nav-pills .nav-link small {
            display: block;
            margin-top: .15rem;
            color: rgba(244, 240, 232, 0.56);
            font-size: .78rem;
            font-weight: 600;
        }

        .admin-nav-pills .nav-link:hover,
        .admin-nav-pills .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(255, 247, 237, 0.16), rgba(199, 119, 45, 0.16));
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
            font-size: .78rem;
            font-weight: 800;
        }

        .admin-main {
            padding: 1.5rem 0 3rem;
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

        .admin-stat-card { padding: 24px; }
        .admin-stat-label { margin: 0; color: var(--admin-muted); font-size: .86rem; font-weight: 700; }
        .admin-stat-value { margin: 14px 0 0; font-size: clamp(2rem, 3vw, 3rem); line-height: 1; letter-spacing: -.04em; font-weight: 800; color: var(--admin-secondary); }
        .admin-stat-note { margin: 12px 0 0; font-size: .85rem; color: var(--admin-muted); }

        .admin-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: .65rem .95rem;
            background: rgba(255,255,255,.7);
            border: 1px solid rgba(37,54,47,.08);
            color: var(--admin-secondary);
            font-size: .86rem;
            font-weight: 700;
        }

        .admin-actions { display: flex; flex-wrap: wrap; gap: .75rem; }
        .admin-btn, .admin-link-btn { display: inline-flex; align-items: center; justify-content: center; gap: .6rem; border-radius: 999px; padding: .78rem 1.15rem; border: 1px solid transparent; font-size: .92rem; font-weight: 800; text-decoration: none; transition: 180ms ease; }
        .admin-btn-primary { color: #fff; background: linear-gradient(135deg, var(--admin-primary), #4a715f); box-shadow: 0 16px 30px rgba(51,85,70,.2); }
        .admin-btn-secondary { color: var(--admin-secondary); background: rgba(255,255,255,.82); border-color: rgba(37,54,47,.1); }
        .admin-btn-warning { color: #8a4a16; background: #fff7ed; border-color: rgba(199,119,45,.24); }
        .admin-btn-danger { color: #8e3030; background: #fff1f1; border-color: rgba(183,77,77,.22); }
        .admin-btn-primary:hover, .admin-btn-secondary:hover, .admin-btn-warning:hover, .admin-btn-danger:hover { transform: translateY(-1px); }
        .admin-btn-primary:hover { color: #fff; }

        .admin-section-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
        .admin-section-title { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--admin-secondary); }
        .admin-section-copy { margin: 6px 0 0; color: var(--admin-muted); font-size: .92rem; line-height: 1.6; }

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
            font-size: .95rem;
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

        .admin-table-wrap { overflow-x: auto; }
        .admin-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 760px; }
        .admin-table thead th { padding: 0 14px 16px; border-bottom: 1px solid rgba(37,54,47,.08); color: var(--admin-muted); font-size: .78rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .admin-table tbody td { padding: 18px 14px; border-bottom: 1px solid rgba(37,54,47,.06); vertical-align: top; }
        .admin-table tbody tr:hover td { background: rgba(255,255,255,.42); }

        .admin-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 6px 10px; font-size: .76rem; font-weight: 800; }
        .admin-badge-success { color: #1f6c4f; background: rgba(47,122,91,.12); }
        .admin-badge-muted { color: #59655f; background: rgba(87,101,93,.1); }
        .admin-badge-accent { color: #8a4a16; background: rgba(199,119,45,.14); }

        .admin-alert {
            margin-bottom: 24px;
            border-radius: 24px;
            border: 1px solid rgba(47, 122, 91, 0.14);
            background: rgba(47, 122, 91, 0.08);
            color: #24573f;
            padding: 16px 18px;
            font-size: .92rem;
            font-weight: 700;
        }

        .admin-alert-error {
            border-color: rgba(183, 77, 77, 0.18);
            background: rgba(183, 77, 77, 0.08);
            color: #8e3030;
        }

        .offcanvas.admin-offcanvas {
            --bs-offcanvas-bg: #1b2722;
            --bs-offcanvas-color: #f4f0e8;
        }

        @media (max-width: 991.98px) {
            .admin-page-copy { max-width: none; }
        }
    </style>
</head>
<body class="admin-app">
    @php
        $adminHomeUrl = \App\Support\CentralAppUrl::admin();
        $siteHomeUrl = \App\Support\CentralAppUrl::app();
        $navItems = [
            ['icon' => 'bi-grid-1x2-fill', 'url' => \App\Support\CentralAppUrl::admin(), 'path' => '/admin', 'label' => 'Vue d\'ensemble', 'meta' => 'KPI et activite'],
            ['icon' => 'bi-buildings-fill', 'url' => \App\Support\CentralAppUrl::admin('company'), 'path' => '/admin/company', 'label' => 'Entreprise', 'meta' => 'Profil et ton'],
            ['icon' => 'bi-geo-alt-fill', 'url' => \App\Support\CentralAppUrl::admin('zones'), 'path' => '/admin/zones', 'label' => 'Zones', 'meta' => 'Departements et villes'],
            ['icon' => 'bi-tools', 'url' => \App\Support\CentralAppUrl::admin('services'), 'path' => '/admin/services', 'label' => 'Services', 'meta' => 'Offres et activations'],
            ['icon' => 'bi-file-earmark-text-fill', 'url' => \App\Support\CentralAppUrl::admin('pages'), 'path' => '/admin/pages', 'label' => 'Pages SEO', 'meta' => 'Production locale'],
            ['icon' => 'bi-telephone-inbound-fill', 'url' => \App\Support\CentralAppUrl::admin('leads'), 'path' => '/admin/leads', 'label' => 'Leads', 'meta' => 'Demandes entrantes'],
            ['icon' => 'bi-stars', 'url' => \App\Support\CentralAppUrl::admin('testimonials'), 'path' => '/admin/testimonials', 'label' => 'Avis', 'meta' => 'Preuve sociale'],
            ['icon' => 'bi-journal-richtext', 'url' => \App\Support\CentralAppUrl::admin('blog'), 'path' => '/admin/blog', 'label' => 'Blog', 'meta' => 'Contenu editorial'],
            ['icon' => 'bi-palette-fill', 'url' => \App\Support\CentralAppUrl::admin('branding'), 'path' => '/admin/branding', 'label' => 'Branding', 'meta' => 'Couleurs et assets'],
            ['icon' => 'bi-key-fill', 'url' => \App\Support\CentralAppUrl::admin('api-settings'), 'path' => '/admin/api-settings', 'label' => 'APIs', 'meta' => 'Cles et connexions'],
        ];
        $currentPath = request()->path();
        $currentItem = collect($navItems)->first(function (array $item) use ($currentPath): bool {
            return ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
        });
    @endphp

    <nav class="navbar admin-shell-navbar navbar-expand-lg">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary d-lg-none rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <a class="navbar-brand fw-bold text-decoration-none text-dark-emphasis" href="{{ $adminHomeUrl }}">Console Admin</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ $siteHomeUrl }}" class="btn btn-light rounded-pill border">Voir le site</a>
                <form method="POST" action="{{ \App\Support\CentralAppUrl::admin('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid admin-main px-3 px-lg-4">
        <div class="row g-4">
            <div class="col-lg-4 col-xl-3 d-none d-lg-block">
                <aside class="admin-sidebar-shell p-3 p-xl-4 position-sticky top-0">
                    <div class="admin-brand">
                        <p class="admin-brand-kicker">Framework Dashboard</p>
                        <a href="{{ $adminHomeUrl }}" class="text-decoration-none">
                            <h1 class="admin-brand-title">Bootstrap Admin</h1>
                        </a>
                        <p class="admin-brand-copy">Une structure dashboard plus stable et plus cohérente pour piloter tout le back-office.</p>
                    </div>

                    <div class="nav nav-pills flex-column admin-nav-pills gap-2 mt-4">
                        @foreach ($navItems as $index => $item)
                            @php
                                $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
                            @endphp
                            <a href="{{ $item['url'] }}" class="nav-link{{ $active ? ' active' : '' }}">
                                <span class="d-flex align-items-start gap-3">
                                    <i class="bi {{ $item['icon'] }} fs-6 mt-1"></i>
                                    <span>
                                        <span class="d-block">{{ $item['label'] }}</span>
                                        <small>{{ $item['meta'] }}</small>
                                    </span>
                                </span>
                                <span class="admin-nav-badge">{{ $index + 1 }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div class="admin-brand mt-4">
                        <p class="admin-brand-kicker">Domaine</p>
                        <p class="admin-brand-copy mt-0">{{ parse_url($siteHomeUrl, PHP_URL_HOST) }}</p>
                        <a href="{{ $siteHomeUrl }}" class="admin-link-btn admin-btn-secondary mt-3">Ouvrir le site</a>
                    </div>
                </aside>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="admin-panel admin-panel-strong p-4 p-lg-5 mb-4">
                    <p class="admin-kicker" style="color: var(--admin-primary);">Administration</p>
                    <div class="d-flex flex-column flex-xl-row align-items-xl-end justify-content-between gap-3">
                        <div>
                            <h2 class="admin-page-title">@yield('title', 'Tableau de bord')</h2>
                            @if ($currentItem)
                                <p class="admin-page-copy">{{ $currentItem['meta'] }}</p>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach (collect($navItems)->take(4) as $item)
                                @php
                                    $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
                                @endphp
                                <a href="{{ $item['url'] }}" class="btn {{ $active ? 'btn-primary' : 'btn-light border' }} rounded-pill">
                                    <i class="bi {{ $item['icon'] }} me-1"></i>{{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="admin-alert">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start admin-offcanvas" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarLabel">Navigation admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
        </div>
        <div class="offcanvas-body">
            <div class="admin-brand mb-4">
                <p class="admin-brand-kicker">Dashboard</p>
                <h2 class="admin-brand-title">Bootstrap Admin</h2>
                <p class="admin-brand-copy">Accès rapide aux sections clés du back-office.</p>
            </div>
            <div class="nav nav-pills flex-column admin-nav-pills gap-2">
                @foreach ($navItems as $index => $item)
                    @php
                        $active = ('/'.$currentPath === $item['path']) || ($item['path'] !== '/admin' && str_starts_with('/'.$currentPath, $item['path']));
                    @endphp
                    <a href="{{ $item['url'] }}" class="nav-link{{ $active ? ' active' : '' }}">
                        <span class="d-flex align-items-start gap-3">
                            <i class="bi {{ $item['icon'] }} fs-6 mt-1"></i>
                            <span>
                                <span class="d-block">{{ $item['label'] }}</span>
                                <small>{{ $item['meta'] }}</small>
                            </span>
                        </span>
                        <span class="admin-nav-badge">{{ $index + 1 }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
