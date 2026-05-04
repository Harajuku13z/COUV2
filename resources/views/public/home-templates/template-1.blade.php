<section class="mx-auto max-w-7xl px-4 pb-10 pt-10 md:pt-14">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr] xl:items-start">
        <div class="space-y-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 shadow-sm">
                <span class="inline-flex h-2.5 w-2.5 rounded-full" style="background: var(--brand-primary)"></span>
                {{ $homepage['hero_kicker'] ?? 'Artisan local' }}
            </div>

            <div class="space-y-5">
                <h1 class="max-w-4xl text-5xl font-semibold tracking-[-0.04em] text-slate-950 md:text-6xl lg:text-7xl" style="font-family: var(--font-heading)">
                    {{ $homepage['hero_title'] ?? $company->name }}
                </h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-600 md:text-xl">{{ $homepage['hero_description'] ?? $company->offer_text }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="#devis" class="rounded-full px-6 py-3.5 text-sm font-semibold text-white no-underline shadow-sm" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));">{{ $ctaPrimaryLabel }}</a>
                <a href="#realisations" class="rounded-full border border-slate-300 bg-white/90 px-6 py-3.5 text-sm font-semibold text-slate-800 no-underline">{{ $ctaSecondaryLabel }}</a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach($trustItems->take(3) as $item)
                    <article class="rounded-[2rem] border border-slate-200/80 bg-white/92 p-5 shadow-[0_18px_44px_rgba(15,23,42,0.05)]">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Repère</p>
                        <p class="mt-3 text-base font-semibold leading-6 text-slate-900">{{ $item }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                @if($realizations->isNotEmpty())
                    @foreach($realizations->take(2) as $realization)
                        @php($photo = $realization->primaryMedia())
                        <article class="overflow-hidden rounded-[2.2rem] border border-slate-200 bg-white shadow-[0_18px_44px_rgba(15,23,42,0.08)] {{ $loop->first ? 'sm:col-span-2' : '' }}">
                            @if($photo)
                                <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="{{ $loop->first ? 'h-72' : 'h-56' }} w-full object-cover" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            @endif
                            <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $realization->city_label ?: $company->city }}</p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-950">{{ $realization->title }}</h2>
                            </div>
                        </article>
                    @endforeach
                @else
                    @foreach($highlightItems->take(2) as $item)
                        <article class="rounded-[2.2rem] border border-slate-200 bg-white p-6 shadow-[0_18px_44px_rgba(15,23,42,0.08)] {{ $loop->first ? 'sm:col-span-2' : '' }}">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Mise en avant</p>
                            <h2 class="mt-3 text-2xl font-semibold text-slate-950">{{ $item }}</h2>
                        </article>
                    @endforeach
                @endif
            </div>

            <div class="rounded-[2.2rem] border border-slate-200 bg-white/92 p-6 shadow-[0_18px_44px_rgba(15,23,42,0.05)]">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Réponse</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">Sous 2h</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Implantation</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $company->city }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Intervention</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $departmentCodes !== [] ? count($departmentCodes).' zones' : 'Locale' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-4">
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="rounded-[2.3rem] border border-slate-200 bg-white/92 p-7 shadow-[0_22px_55px_rgba(15,23,42,0.06)]">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Pourquoi ça convertit</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-[-0.03em] text-slate-950" style="font-family: var(--font-heading)">Un discours clair, des preuves visibles et une prise de contact immédiate.</h2>
            <div class="mt-6 grid gap-4">
                @foreach($highlightItems->take(3) as $item)
                    <div class="rounded-[1.6rem] bg-slate-50 px-5 py-4">
                        <p class="text-sm font-semibold text-slate-800">{{ $item }}</p>
                    </div>
                @endforeach
            </div>
            @if($weatherAlert)
                <div class="mt-6 rounded-[1.6rem] border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
                    Vigilance en cours : {{ $weatherAlert->title ?? 'conditions locales à surveiller' }}.
                </div>
            @endif
        </div>

        <div id="devis">
            @include('components.public.form-devis')
        </div>
    </div>
</section>

@include('public.home-templates.partials.sections-default')
