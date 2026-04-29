@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12">
    <h1 class="text-3xl font-semibold">Dashboard</h1>
    <div class="mt-8 grid gap-4 md:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm">Pages total: {{ $stats['pages']['total'] }}</div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">Publiees: {{ $stats['pages']['published'] }}</div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">Leads mois: {{ $stats['leads']['month'] }}</div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">Cout IA mois: ${{ number_format($stats['openai_cost_month'], 2) }}</div>
    </div>
    <div class="mt-10 grid gap-6 md:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="font-semibold">Erreurs API recentes</h2>
            <div class="mt-4 space-y-3 text-sm">
                @foreach($stats['api_errors'] as $error)
                    <div>{{ $error->service }} — {{ $error->error_message }}</div>
                @endforeach
            </div>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="font-semibold">Evenements meteo</h2>
            <div class="mt-4 space-y-3 text-sm">
                @foreach($stats['weather_events'] as $event)
                    <div>{{ $event->event_type }} {{ $event->intensity }} — {{ $event->event_date }}</div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
