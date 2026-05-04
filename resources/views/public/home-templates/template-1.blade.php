<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1.15fr_0.85fr]">
    <div>
        <p class="text-sm uppercase tracking-[0.24em]" style="color: var(--brand-primary)">{{ $homepage['hero_kicker'] ?? 'Artisan local' }}</p>
        <h1 class="mt-4 max-w-3xl text-4xl font-semibold md:text-5xl" style="font-family: var(--font-heading)">{{ $homepage['hero_title'] ?? $company->name }}</h1>
        <p class="mt-5 max-w-2xl text-lg text-slate-600">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>

        <div class="mt-8 flex flex-wrap gap-4">
            <a href="#devis" class="rounded-full px-5 py-3 text-sm font-semibold text-white no-underline" style="background: var(--brand-primary)">{{ $ctaPrimaryLabel }}</a>
            <a href="#realisations" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 no-underline">{{ $ctaSecondaryLabel }}</a>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            @foreach($trustItems->take(3) as $item)
                <div class="rounded-[2rem] bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Confiance</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $item }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div id="devis" class="space-y-4">
        @if($weatherAlert)
            <div class="rounded-[2rem] border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                Vigilance en cours : {{ $weatherAlert->title ?? 'conditions locales à surveiller' }}.
            </div>
        @endif
        @include('components.public.form-devis')
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-6">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($highlightItems->take(3) as $item)
            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.22em] text-slate-400">Point fort</p>
                <h2 class="mt-3 text-xl font-semibold text-slate-900">{{ $item }}</h2>
            </article>
        @endforeach
    </div>
</section>

@include('public.home-templates.partials.sections-default')
