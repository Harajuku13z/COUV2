<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
        <div class="space-y-4">
            <p class="text-sm uppercase tracking-[0.24em]" style="color: var(--brand-primary)">{{ $homepage['hero_kicker'] ?? 'Artisan local' }}</p>
            <h1 class="text-4xl font-semibold md:text-5xl" style="font-family: var(--font-heading)">{{ $homepage['hero_title'] ?? $company->name }}</h1>
            <p class="max-w-xl text-lg text-slate-600">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="#devis" class="rounded-full px-5 py-3 text-sm font-semibold text-white no-underline" style="background: var(--brand-primary)">{{ $ctaPrimaryLabel }}</a>
                <a href="#services" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 no-underline">Découvrir les services</a>
            </div>
        </div>
        <div class="rounded-[2.5rem] bg-white p-6 shadow-sm md:p-8">
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($trustItems->take(4) as $item)
                    <div class="rounded-[1.75rem] bg-slate-100 p-5">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Garantie</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="devis" class="mx-auto max-w-6xl px-4 pb-8">
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr]">
        @include('components.public.form-devis')
        <div class="grid gap-4 content-start">
            @foreach($highlightItems->take(3) as $item)
                <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-900">{{ $item }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

@include('public.home-templates.partials.sections-default')
