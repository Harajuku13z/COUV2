@extends('layouts.app')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1.1fr_0.9fr]">
    <div>
        <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Contact direct</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">Parlons de votre besoin sur le terrain</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Besoin d un devis, d un avis technique ou d une intervention rapide ? Nous revenons vers vous avec une proposition claire et actionnable.</p>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-[2rem] bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Telephone</p>
                <a href="tel:{{ $company->phone }}" class="mt-2 block text-xl font-semibold">{{ $company->phone }}</a>
            </div>
            <div class="rounded-[2rem] bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Email</p>
                <a href="mailto:{{ $company->email }}" class="mt-2 block text-xl font-semibold">{{ $company->email }}</a>
            </div>
        </div>

        <div class="mt-8 rounded-[2rem] bg-slate-950 p-8 text-slate-100">
            <h2 class="text-2xl font-semibold">Zones et services couverts</h2>
            <div class="mt-4 flex flex-wrap gap-3">
                @foreach($services as $service)
                    <span class="rounded-full bg-white/10 px-4 py-2 text-sm">{{ $service->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            @include('components.public.map-embed', ['query' => $company->fullAddress()])
        </div>
    </div>

    <div class="space-y-6">
        @include('components.public.form-devis', ['page' => $featuredPage])

        <form method="POST" action="{{ route('public.leads.contact') }}" class="space-y-4 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <input type="hidden" name="page_id" value="{{ $featuredPage?->id }}">
            <input type="hidden" name="source_url" value="{{ url()->current() }}">
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <input type="text" name="company_name" class="hidden" tabindex="-1" autocomplete="off">
            <input name="name" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Nom*" required>
            <input name="phone" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Telephone*" required>
            <input name="email" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Email">
            <input name="service_requested" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Service concerne">
            <textarea name="message" rows="5" class="w-full rounded-2xl border border-slate-300 px-4 py-3" placeholder="Votre message"></textarea>
            <button class="w-full rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--brand-primary)">Envoyer mon message</button>
        </form>
    </div>
</section>
@endsection
