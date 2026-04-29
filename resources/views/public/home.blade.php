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
        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-[2rem] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Reponse</p>
                <p class="mt-2 text-2xl font-semibold">Sous 2h</p>
            </div>
            <div class="rounded-[2rem] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Implantation</p>
                <p class="mt-2 text-2xl font-semibold">{{ $company->city }}</p>
            </div>
            <div class="rounded-[2rem] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Urgence</p>
                <p class="mt-2 text-2xl font-semibold">{{ $company->emergency_available ? 'Disponible' : 'Sur demande' }}</p>
            </div>
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

@if($media->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex items-end justify-between gap-6">
        <div>
            <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Realisations</p>
            <h2 class="mt-3 text-3xl font-semibold">Exemples de chantiers</h2>
        </div>
        <a href="{{ route('public.contact') }}" class="text-sm font-semibold" style="color: var(--brand-primary)">Parler de votre projet</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($media as $item)
            <figure class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
                <img src="{{ $item->url ?? asset('storage/'.$item->path) }}" alt="Realisation {{ $loop->iteration }}" class="h-72 w-full object-cover" loading="lazy">
            </figure>
        @endforeach
    </div>
</section>
@endif

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

@if($latestPosts->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex items-end justify-between gap-6">
        <div>
            <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Blog local</p>
            <h2 class="mt-3 text-3xl font-semibold">Les derniers conseils utiles</h2>
        </div>
        <a href="{{ route('public.blog.index') }}" class="text-sm font-semibold" style="color: var(--brand-primary)">Voir tous les articles</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($latestPosts as $post)
            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $post->category ?? 'Conseil' }}</p>
                <h3 class="mt-3 text-xl font-semibold">{{ $post->title }}</h3>
                <p class="mt-3 text-sm text-slate-600">{{ $post->excerpt ?: $post->meta_description }}</p>
                <a href="{{ route('public.blog.show', $post->slug) }}" class="mt-5 inline-flex text-sm font-semibold" style="color: var(--brand-primary)">Lire l article</a>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection
