@extends('layouts.app')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1.05fr_0.95fr]">
    <div>
        <p class="text-sm uppercase tracking-[0.28em] text-red-600">Intervention urgente</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">
            {{ $page->content->h1 ?? 'Besoin d une intervention rapide ?' }}
        </h1>
        <p class="mt-4 text-lg text-slate-600">{{ $page->content->intro ?? 'Exposez la situation et nous revenons vers vous rapidement pour prioriser l intervention.' }}</p>

        @if(! empty($weatherAlerts))
            <div class="mt-8 rounded-[2rem] border border-amber-200 bg-amber-50 p-6 text-amber-900">
                <p class="font-semibold">Contexte meteo a surveiller</p>
                <p class="mt-2 text-sm">{{ $weatherAlerts[0]->description ?? 'Un evenement recent peut avoir un impact sur la situation.' }}</p>
            </div>
        @endif
    </div>
    <div class="space-y-6">
        @include('components.public.form-urgence')
        @include('components.public.form-devis')
    </div>
</section>
@endsection
