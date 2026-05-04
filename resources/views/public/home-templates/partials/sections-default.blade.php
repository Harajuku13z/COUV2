<section id="services" class="mx-auto max-w-7xl px-4 py-18">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em]" style="color: var(--brand-primary)">Services</p>
            <h2 class="mt-3 max-w-3xl text-4xl font-semibold tracking-[-0.03em] text-slate-950" style="font-family: var(--font-heading)">{{ $homepage['services_title'] ?? 'Nos services' }}</h2>
            <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">{{ $homepage['services_intro'] ?? '' }}</p>
        </div>
        <a href="{{ route('public.devis') }}" class="rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 no-underline">Parler de votre besoin</a>
    </div>
    <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($services as $service)
            @php($servicePhoto = $service->primaryMedia())
            <article class="overflow-hidden rounded-[2.2rem] border border-slate-200/80 bg-white shadow-[0_18px_44px_rgba(15,23,42,0.06)]">
                @if($servicePhoto)
                    <img src="{{ $servicePhoto->url ?? asset('storage/'.$servicePhoto->path) }}" alt="{{ $servicePhoto->alt_text ?? $service->name }}" class="h-56 w-full object-cover" loading="lazy">
                @endif
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $service->category ?: 'Service' }}</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-950">{{ $service->name }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $service->websiteService?->custom_description ?: $service->description }}</p>
                    <div class="mt-5">
                        <a href="{{ route('public.devis') }}" class="text-sm font-semibold no-underline" style="color: var(--brand-primary)">Demander une estimation</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

@if($realizations->isNotEmpty())
<section id="realisations" class="mx-auto max-w-7xl px-4 py-18">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em]" style="color: var(--brand-primary)">Réalisations</p>
            <h2 class="mt-3 max-w-3xl text-4xl font-semibold tracking-[-0.03em] text-slate-950" style="font-family: var(--font-heading)">{{ $homepage['realizations_title'] ?? 'Nos chantiers' }}</h2>
            <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">{{ $homepage['realizations_intro'] ?? '' }}</p>
        </div>
        <a href="{{ route('public.contact') }}" class="text-sm font-semibold no-underline" style="color: var(--brand-primary)">Parler de votre projet</a>
    </div>
    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        @foreach($realizations as $realization)
            @php($photo = $realization->primaryMedia())
            <article class="overflow-hidden rounded-[2.2rem] border border-slate-200/80 bg-white shadow-[0_18px_44px_rgba(15,23,42,0.06)]">
                @if($photo)
                    <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="h-72 w-full object-cover" loading="lazy">
                @endif
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ $realization->city_label ?: $company->city }}</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-900">{{ $realization->title }}</h3>
                    @if($realization->description)
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $realization->description }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

@if($testimonials->isNotEmpty())
<section id="faq" class="mx-auto max-w-7xl px-4 py-18">
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.3em]" style="color: var(--brand-primary)">Avis</p>
        <h2 class="mt-3 text-4xl font-semibold tracking-[-0.03em] text-slate-950" style="font-family: var(--font-heading)">{{ $homepage['testimonials_title'] ?? 'Ils parlent de nous' }}</h2>
    </div>
    <div class="grid gap-6 md:grid-cols-3">
        @foreach($testimonials as $testimonial)
            <article class="rounded-[2.2rem] border border-slate-200/80 bg-white p-6 shadow-[0_18px_44px_rgba(15,23,42,0.05)]">
                <p class="text-lg leading-8 text-slate-700">“{{ $testimonial->content }}”</p>
                <p class="mt-5 text-sm font-medium text-slate-900">{{ $testimonial->author_name }}{{ $testimonial->author_city ? ' — '.$testimonial->author_city : '' }}</p>
            </article>
        @endforeach
    </div>
</section>
@endif

@if($latestPosts->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-18">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em]" style="color: var(--brand-primary)">Conseils</p>
            <h2 class="mt-3 text-4xl font-semibold tracking-[-0.03em] text-slate-950" style="font-family: var(--font-heading)">{{ $homepage['blog_title'] ?? 'Les derniers conseils utiles' }}</h2>
        </div>
        <a href="{{ route('public.blog.index') }}" class="text-sm font-semibold no-underline" style="color: var(--brand-primary)">Voir tous les articles</a>
    </div>
    <div class="mt-10 grid gap-6 md:grid-cols-3">
        @foreach($latestPosts as $post)
            <article class="rounded-[2.2rem] border border-slate-200/80 bg-white p-6 shadow-[0_18px_44px_rgba(15,23,42,0.05)]">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $post->category ?? 'Conseil' }}</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-950">{{ $post->title }}</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $post->excerpt ?: $post->meta_description }}</p>
                <a href="{{ route('public.blog.show', $post->slug) }}" class="mt-5 inline-flex text-sm font-semibold no-underline" style="color: var(--brand-primary)">Lire l’article</a>
            </article>
        @endforeach
    </div>
</section>
@endif
