@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<section>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Pages total</p><p class="mt-3 text-3xl font-semibold">{{ $stats['pages']['total'] }}</p></div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Pages publiees</p><p class="mt-3 text-3xl font-semibold">{{ $stats['pages']['published'] }}</p></div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Leads ce mois</p><p class="mt-3 text-3xl font-semibold">{{ $stats['leads']['month'] }}</p></div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm"><p class="text-sm text-slate-500">Cout IA mensuel</p><p class="mt-3 text-3xl font-semibold">${{ number_format($stats['openai_cost_month'], 2) }}</p></div>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Leads sur 7 jours</h2>
            <p class="text-sm text-slate-500">Suivi visuel du flux entrant.</p>
            <div class="mt-6 h-80">
                <canvas id="leadsChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[2rem] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold">Actions rapides</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <form method="POST" action="{{ route('admin.dashboard.sitemap') }}">@csrf<button class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-left">Regenerer sitemap</button></form>
                    <form method="POST" action="{{ route('admin.dashboard.weather') }}">@csrf<button class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-left">Rafraichir donnees meteo</button></form>
                </div>
            </div>

            <div class="rounded-[2rem] bg-slate-950 p-6 text-slate-100 shadow-sm">
                <h2 class="text-lg font-semibold">Jobs et files</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div><p class="text-sm text-slate-400">En attente</p><p class="mt-2 text-3xl font-semibold">{{ $stats['jobs']['pending'] ?? '—' }}</p></div>
                    <div><p class="text-sm text-slate-400">Recemment en echec</p><p class="mt-2 text-3xl font-semibold">{{ $stats['jobs']['failed'] ?? '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Statuts des leads</h2>
            <div class="mt-4 h-72">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Top villes</h2>
            <div class="mt-4 space-y-4">
                @forelse($stats['top_cities'] as $city)
                    <div class="flex items-center justify-between"><span>{{ $city->city_label }}</span><span class="font-semibold">{{ $city->total }}</span></div>
                @empty
                    <p class="text-sm text-slate-500">Aucune donnee.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Top services</h2>
            <div class="mt-4 space-y-4">
                @forelse($stats['top_services'] as $service)
                    <div class="flex items-center justify-between"><span>{{ $service->service_requested }}</span><span class="font-semibold">{{ $service->total }}</span></div>
                @empty
                    <p class="text-sm text-slate-500">Aucune donnee.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Erreurs API recentes</h2>
            <div class="mt-4 space-y-3 text-sm">
                @forelse($stats['api_errors'] as $error)
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-medium">{{ $error->service }}</p>
                        <p class="mt-1 text-slate-600">{{ $error->error_message }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">Aucune erreur recente.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Evenements meteo suivis</h2>
            <div class="mt-4 space-y-3 text-sm">
                @forelse($stats['weather_events'] as $event)
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-medium">{{ $event->event_type }} · {{ $event->intensity }}</p>
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
                    borderColor: '#4f7465',
                    backgroundColor: 'rgba(79,116,101,0.14)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
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
                    backgroundColor: ['#4f7465', '#0f766e', '#d97706', '#16a34a', '#dc2626']
                }]
            },
            options: { maintainAspectRatio: false }
        });
    }
</script>
@endpush
