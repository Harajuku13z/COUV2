@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<section class="space-y-8">
    <div class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.35fr_0.65fr] lg:px-8">
            <div>
                <p class="admin-kicker" style="color:rgba(255,244,232,0.72);">Vue d'ensemble</p>
                <h1 class="admin-page-title" style="color:#fff7ed;">Le cockpit de ton acquisition locale.</h1>
                <p class="admin-page-copy" style="color:rgba(248,244,236,0.78);">
                    Suis la production SEO, l'activite commerciale et les signaux techniques depuis une interface plus lisible et plus actionnable.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="admin-pill" style="background:rgba(255,255,255,0.08);color:#fff7ed;border-color:rgba(255,255,255,0.08);">
                        {{ $stats['pages']['published'] }} pages publiees
                    </span>
                    <span class="admin-pill" style="background:rgba(255,255,255,0.08);color:#fff7ed;border-color:rgba(255,255,255,0.08);">
                        {{ $stats['leads']['month'] }} leads ce mois
                    </span>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                    <p class="text-sm font-semibold text-white/70">Budget IA mensuel</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-white">${{ number_format($stats['openai_cost_month'], 2) }}</p>
                    <p class="mt-2 text-sm text-white/60">Vision immediate des couts de generation.</p>
                </div>
                <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                    <p class="text-sm font-semibold text-white/70">File de jobs</p>
                    <div class="mt-3 flex items-end justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-white/45">En attente</p>
                            <p class="mt-1 text-3xl font-extrabold text-white">{{ $stats['jobs']['pending'] ?? '—' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-[0.2em] text-white/45">Echec recent</p>
                            <p class="mt-1 text-3xl font-extrabold text-white">{{ $stats['jobs']['failed'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-stat-grid lg:grid-cols-2 xl:grid-cols-4">
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Pages au total</p>
            <p class="admin-stat-value">{{ $stats['pages']['total'] }}</p>
            <p class="admin-stat-note">Base editoriale disponible pour la couverture locale.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Pages publiees</p>
            <p class="admin-stat-value">{{ $stats['pages']['published'] }}</p>
            <p class="admin-stat-note">Contenus visibles et exploitables pour le SEO.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Leads du mois</p>
            <p class="admin-stat-value">{{ $stats['leads']['month'] }}</p>
            <p class="admin-stat-note">Demandes entrantes captees sur la periode en cours.</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Leads a traiter</p>
            <p class="admin-stat-value">{{ $stats['leads']['new'] ?? 0 }}</p>
            <p class="admin-stat-note">Opportunites encore fraiches a recontacter.</p>
        </article>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="admin-panel admin-panel-strong p-6 lg:p-7">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Flux de leads sur 7 jours</h2>
                    <p class="admin-section-copy">Une lecture rapide de la dynamique commerciale recente.</p>
                </div>
                <span class="admin-pill">7 derniers jours</span>
            </div>
            <div class="h-80">
                <canvas id="leadsChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-panel admin-panel-strong p-6">
                <div class="admin-section-head">
                    <div>
                        <h2 class="admin-section-title">Actions rapides</h2>
                        <p class="admin-section-copy">Les deux leviers d'entretien les plus frequents.</p>
                    </div>
                </div>
                <div class="grid gap-3">
                    <form method="POST" action="{{ route('admin.dashboard.sitemap') }}">
                        @csrf
                        <button class="admin-btn admin-btn-primary w-full justify-between">
                            <span>Regenerer le sitemap</span>
                            <span>SEO</span>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.dashboard.weather') }}">
                        @csrf
                        <button class="admin-btn admin-btn-secondary w-full justify-between">
                            <span>Rafraichir les donnees meteo</span>
                            <span>Data</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="admin-panel p-6" style="background:linear-gradient(180deg, rgba(255,247,237,0.92), rgba(255,255,255,0.88));">
                <div class="admin-section-head">
                    <div>
                        <h2 class="admin-section-title">Synthese technique</h2>
                        <p class="admin-section-copy">Lecture rapide de la sante operationnelle.</p>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-note">
                        <p class="m-0 text-sm font-semibold text-slate-500">Jobs en attente</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['jobs']['pending'] ?? '—' }}</p>
                    </div>
                    <div class="admin-note" style="background:rgba(183,77,77,0.05);border-color:rgba(183,77,77,0.08);">
                        <p class="m-0 text-sm font-semibold text-slate-500">Jobs en echec</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['jobs']['failed'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Statuts des leads</h2>
                    <p class="admin-section-copy">Repartition des demandes dans le pipe commercial.</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Top villes</h2>
                    <p class="admin-section-copy">Les zones qui captent le plus de demandes.</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($stats['top_cities'] as $city)
                    <div class="flex items-center justify-between rounded-[20px] border border-slate-200/70 bg-white/80 px-4 py-3">
                        <span class="font-semibold text-slate-800">{{ $city->city_label }}</span>
                        <span class="admin-badge admin-badge-accent">{{ $city->total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucune donnee.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Top services</h2>
                    <p class="admin-section-copy">Les demandes les plus frequentes du moment.</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($stats['top_services'] as $service)
                    <div class="flex items-center justify-between rounded-[20px] border border-slate-200/70 bg-white/80 px-4 py-3">
                        <span class="font-semibold text-slate-800">{{ $service->service_requested }}</span>
                        <span class="admin-badge admin-badge-success">{{ $service->total }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucune donnee.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Erreurs API recentes</h2>
                    <p class="admin-section-copy">Surveillance des points de friction les plus critiques.</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                @forelse($stats['api_errors'] as $error)
                    <div class="rounded-[22px] border border-red-100 bg-red-50/60 p-4">
                        <p class="font-semibold text-slate-900">{{ $error->service }}</p>
                        <p class="mt-1 leading-6 text-slate-600">{{ $error->error_message }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">Aucune erreur recente.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-panel admin-panel-strong p-6">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Evenements meteo suivis</h2>
                    <p class="admin-section-copy">Contextes locaux a surveiller pour l'activation commerciale.</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                @forelse($stats['weather_events'] as $event)
                    <div class="rounded-[22px] border border-slate-200/70 bg-white/84 p-4">
                        <p class="font-semibold text-slate-900">{{ $event->event_type }} · {{ $event->intensity }}</p>
                        <p class="mt-1 text-slate-600">{{ optional($event->event_date)->format('d/m/Y') }}{{ $event->city ? ' · '.$event->city->name : '' }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">Aucun evenement recent.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const leadsCtx = document.getElementById('leadsChart');
    if (leadsCtx) {
        new Chart(leadsCtx, {
            type: 'line',
            data: {
                labels: @json($stats['charts']['leads_by_day']['labels']),
                datasets: [{
                    label: 'Leads',
                    data: @json($stats['charts']['leads_by_day']['data']),
                    borderColor: '#335546',
                    backgroundColor: 'rgba(51, 85, 70, 0.12)',
                    pointBackgroundColor: '#c7772d',
                    pointBorderColor: '#fff7ed',
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#647067' } },
                    y: { grid: { color: 'rgba(37,54,47,0.08)' }, ticks: { color: '#647067', precision: 0 } }
                }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($stats['charts']['lead_status']['labels']),
                datasets: [{
                    data: @json($stats['charts']['lead_status']['data']),
                    backgroundColor: ['#335546', '#3f6a58', '#c7772d', '#4c8a68', '#b74d4d'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18 } } }
            }
        });
    }
</script>
@endpush
