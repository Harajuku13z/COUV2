@extends('layouts.app')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 md:grid-cols-[1.2fr_0.8fr]">
    <div>
        <p class="text-sm uppercase tracking-[0.2em]" style="color: var(--brand-primary)">Artisan local</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">{{ $company->name }}</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">{{ $company->offer_text ?? 'Interventions rapides, devis precis et accompagnement local pour vos besoins.' }}</p>
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="tel:{{ $company->phone }}" class="rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--brand-primary)">Appel Direct</a>
            <a href="#devis" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold">Devis Gratuit</a>
        </div>
    </div>
    <div id="devis">
        @include('components.public.form-devis')
    </div>
</section>

<section id="services" class="mx-auto max-w-6xl px-4 py-10">
    <div class="grid gap-6 md:grid-cols-3">
        @foreach($services as $service)
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold">{{ $service->name }}</h2>
                <p class="mt-3 text-sm text-slate-600">{{ $service->description }}</p>
            </article>
        @endforeach
    </div>
</section>

<section id="faq" class="mx-auto max-w-6xl px-4 py-10">
    <div class="grid gap-6 md:grid-cols-3">
        @foreach($testimonials as $testimonial)
            <article class="rounded-3xl bg-slate-100 p-6">
                <p class="text-sm">{{ $testimonial->content }}</p>
                <p class="mt-4 text-sm font-medium">{{ $testimonial->author_name }} {{ $testimonial->author_city ? '— '.$testimonial->author_city : '' }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
