<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <div class="rounded-[2.5rem] bg-slate-950 p-8 text-white shadow-xl md:p-10">
            <p class="text-sm uppercase tracking-[0.24em] text-white/55">{{ $homepage['hero_kicker'] ?? 'Artisan local' }}</p>
            <h1 class="mt-4 text-4xl font-semibold md:text-5xl" style="font-family: var(--font-heading)">{{ $homepage['hero_title'] ?? $company->name }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-white/72">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#devis" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 no-underline">{{ $ctaPrimaryLabel }}</a>
                <a href="#realisations" class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white no-underline">{{ $ctaSecondaryLabel }}</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach($realizations->take(2) as $realization)
                @php($photo = $realization->primaryMedia())
                <article class="overflow-hidden rounded-[2.25rem] bg-white shadow-sm">
                    @if($photo)
                        <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="h-60 w-full object-cover" loading="lazy">
                    @endif
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $realization->title }}</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $realization->city_label ?: $company->city }}</p>
                    </div>
                </article>
            @endforeach
            @foreach($trustItems->take(2) as $item)
                <article class="rounded-[2.25rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Repère</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $item }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section id="devis" class="mx-auto max-w-6xl px-4 pb-8">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach($highlightItems->take(3) as $item)
            <div class="rounded-[2rem] bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-800">{{ $item }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        @include('components.public.form-devis')
    </div>
</section>

@include('public.home-templates.partials.sections-default')
