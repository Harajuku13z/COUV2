<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-[2.5rem] bg-white p-8 shadow-sm md:p-10">
            <p class="text-sm uppercase tracking-[0.24em]" style="color: var(--brand-primary)">{{ $homepage['hero_kicker'] ?? 'Artisan local' }}</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-semibold md:text-5xl" style="font-family: var(--font-heading)">{{ $homepage['hero_title'] ?? $company->name }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-slate-600">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#devis" class="rounded-full px-5 py-3 text-sm font-semibold text-white no-underline" style="background: var(--brand-primary)">{{ $ctaPrimaryLabel }}</a>
                <a href="tel:{{ $company->phone }}" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 no-underline">Appeler maintenant</a>
            </div>
        </div>

        <div class="grid gap-4">
            @foreach($trustItems->take(3) as $item)
                <div class="rounded-[2rem] bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-xs uppercase tracking-[0.22em] text-white/45">Pourquoi nous</p>
                    <p class="mt-3 text-lg font-semibold">{{ $item }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="devis" class="mx-auto max-w-6xl px-4 pb-8">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($highlightItems->take(3) as $item)
            <div class="rounded-[2rem] border border-slate-200 bg-white p-5 text-center shadow-sm">
                <p class="text-sm font-semibold text-slate-700">{{ $item }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        @include('components.public.form-devis')
    </div>
</section>

@include('public.home-templates.partials.sections-default')
