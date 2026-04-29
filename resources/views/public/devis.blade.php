@extends('layouts.app')

@section('content')
@php($devisPage = $page ?? $featuredPage ?? null)
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1.05fr_0.95fr]">
    <div>
        <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Demande de devis</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">
            {{ $devisPage?->content?->h1 ?? 'Recevez un devis clair et rapide' }}
        </h1>
        <p class="mt-4 text-lg text-slate-600">{{ $devisPage?->content?->intro ?? ($company->offer_text ?? 'Decrivez votre projet et recevez une estimation adaptee a votre situation.') }}</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-[2rem] bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Analyse</p><p class="mt-2 text-2xl font-semibold">Sous 2h</p></div>
            <div class="rounded-[2rem] bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Devis</p><p class="mt-2 text-2xl font-semibold">Detaille</p></div>
            <div class="rounded-[2rem] bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Accompagnement</p><p class="mt-2 text-2xl font-semibold">Local</p></div>
        </div>
    </div>
    <div>
        @include('components.public.form-devis', ['page' => $devisPage])
    </div>
</section>
@endsection
