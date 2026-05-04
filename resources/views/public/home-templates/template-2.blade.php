<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="overflow-hidden rounded-[2.5rem] bg-slate-950 px-6 py-8 text-white shadow-xl md:px-10 md:py-10">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.28em] text-white/60">{{ $homepage['hero_kicker'] ?? 'Artisan local' }}</p>
                <h1 class="mt-4 text-4xl font-semibold md:text-5xl" style="font-family: var(--font-heading)">{{ $homepage['hero_title'] ?? $company->name }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-white/72">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#devis" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 no-underline">{{ $ctaPrimaryLabel }}</a>
                    <a href="#realisations" class="rounded-full border border-white/20 px-5 py-3 text-sm font-semibold text-white no-underline">{{ $ctaSecondaryLabel }}</a>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($trustItems->take(4) as $item)
                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.22em] text-white/45">Engagement</p>
                        <p class="mt-3 text-base font-semibold">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="devis" class="mx-auto max-w-6xl px-4 pb-6">
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.22em]" style="color: var(--brand-primary)">Zones couvertes</p>
            <h2 class="mt-3 text-2xl font-semibold">Interventions locales sur {{ max(count($departmentCodes), 1) }} secteur(s)</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">Départements actifs : {{ $departmentCodes !== [] ? implode(' • ', $departmentCodes) : $company->city }}</p>
            <div class="mt-5 grid gap-3">
                @foreach($highlightItems->take(3) as $item)
                    <div class="rounded-2xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">{{ $item }}</div>
                @endforeach
            </div>
        </div>
        @include('components.public.form-devis')
    </div>
</section>

@include('public.home-templates.partials.sections-default')
