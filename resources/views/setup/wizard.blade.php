<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration du site | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @livewireStyles
    <style>
        :root {
            --setup-primary: #365446;
            --setup-secondary: #1f2b25;
            --setup-accent: #d8892b;
            --setup-soft: #eef3ef;
            --setup-paper: rgba(255, 255, 255, 0.88);
        }

        body {
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: #163021;
            background:
                radial-gradient(circle at top left, rgba(54, 84, 70, 0.16), transparent 28%),
                radial-gradient(circle at top right, rgba(216, 137, 43, 0.18), transparent 24%),
                linear-gradient(180deg, #f3f1ea 0%, #f7f8f4 38%, #eef3ef 100%);
        }

        .setup-shell {
            position: relative;
            padding: 3rem 0 4rem;
        }

        .setup-shell::before,
        .setup-shell::after {
            position: absolute;
            z-index: -1;
            content: "";
            border-radius: 999px;
            filter: blur(10px);
        }

        .setup-shell::before {
            top: 3rem;
            left: 6%;
            width: 15rem;
            height: 15rem;
            background: rgba(54, 84, 70, 0.14);
        }

        .setup-shell::after {
            top: 6rem;
            right: 8%;
            width: 18rem;
            height: 18rem;
            background: rgba(216, 137, 43, 0.14);
        }

        .setup-hero,
        .setup-panel,
        .setup-sidecard {
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: var(--setup-paper);
            backdrop-filter: blur(14px);
            box-shadow: 0 24px 80px rgba(28, 44, 37, 0.08);
        }

        .setup-hero,
        .setup-panel {
            border-radius: 2rem;
        }

        .setup-sidecard {
            border-radius: 1.5rem;
        }

        .setup-stat {
            border-radius: 1.25rem;
            padding: 1rem 1.1rem;
            background: #fff;
            box-shadow: inset 0 0 0 1px rgba(28, 44, 37, 0.08);
        }

        .setup-stat-dark {
            background: linear-gradient(145deg, #1f2b25, #31453a);
            color: #fff;
            box-shadow: none;
        }

        .setup-kicker {
            letter-spacing: 0.28em;
            text-transform: uppercase;
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--setup-primary);
        }

        .setup-title {
            font-size: clamp(2.3rem, 4vw, 4rem);
            line-height: 1.02;
            font-weight: 800;
            color: #17251f;
        }

        .setup-lead {
            color: #526258;
            font-size: 1.05rem;
            line-height: 1.85;
        }

        .setup-progress {
            height: 0.7rem;
            border-radius: 999px;
            background: #e3ebe5;
            overflow: hidden;
        }

        .setup-progress-bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--setup-primary), var(--setup-accent));
        }

        .setup-section-title {
            font-weight: 800;
            font-size: clamp(1.9rem, 2vw, 2.5rem);
            color: #17251f;
        }

        .setup-form-control,
        .setup-form-select {
            border-radius: 1rem;
            border-color: rgba(54, 84, 70, 0.14);
            background: #f8faf7;
            padding: 0.9rem 1rem;
        }

        .setup-form-control:focus,
        .setup-form-select:focus {
            border-color: rgba(54, 84, 70, 0.46);
            box-shadow: 0 0 0 0.25rem rgba(54, 84, 70, 0.14);
            background: #fff;
        }

        .setup-btn-primary {
            border: 0;
            border-radius: 999px;
            padding: 0.9rem 1.4rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--setup-primary), #4e7662);
            box-shadow: 0 14px 30px rgba(54, 84, 70, 0.2);
        }

        .setup-btn-secondary {
            border-radius: 999px;
            padding: 0.9rem 1.4rem;
            font-weight: 700;
        }

        .setup-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            background: #eef3ef;
            color: #365446;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .setup-dark-card {
            border-radius: 1.75rem;
            padding: 1.5rem;
            color: #fff;
            background: linear-gradient(160deg, #1f2b25, #304237);
            box-shadow: 0 20px 50px rgba(28, 44, 37, 0.18);
        }

        .setup-info-card {
            border-radius: 1.5rem;
            padding: 1.4rem;
            background: #f8faf7;
            border: 1px solid rgba(54, 84, 70, 0.08);
        }

        .setup-check {
            width: 1.2rem;
            height: 1.2rem;
            accent-color: var(--setup-primary);
        }

        @media (max-width: 991.98px) {
            .setup-shell {
                padding-top: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <main class="setup-shell">
        <div class="container-xl">
            <section class="setup-hero p-4 p-lg-5 mb-4 mb-lg-5">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-8">
                        <div class="setup-kicker">Mise en route guidee</div>
                        <h1 class="setup-title mt-3 mb-3">Configure ton site artisan avec une interface plus claire et plus pro</h1>
                        <p class="setup-lead mb-0">
                            On pose les bases de ton activite, tes zones, tes services, ton image de marque et tes integrations dans un seul parcours.
                            Le but est d aller vite, mais avec une presentation propre et rassurante.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-3">
                            <div class="col-sm-4 col-lg-12 col-xl-4">
                                <div class="setup-stat setup-stat-dark h-100">
                                    <div class="text-uppercase small opacity-75 fw-semibold">Temps moyen</div>
                                    <div class="fs-3 fw-bold mt-2">8 min</div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-6 col-xl-4">
                                <div class="setup-stat h-100">
                                    <div class="text-uppercase small text-secondary fw-semibold">Etapes</div>
                                    <div class="fs-3 fw-bold mt-2">6</div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-6 col-xl-4">
                                <div class="setup-stat h-100">
                                    <div class="text-uppercase small text-secondary fw-semibold">Objectif</div>
                                    <div class="fs-5 fw-bold mt-2">Pret a lancer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <livewire:onboarding.onboarding-wizard />
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @livewireScripts
</body>
</html>
