<section id="services" class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex items-end justify-between gap-6">
        <div>
            <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Services</p>
            <h2 class="mt-3 text-3xl font-semibold">{{ $homepage['services_title'] ?? 'Nos services' }}</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ $homepage['services_intro'] ?? '' }}</p>
        </div>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($services as $service)
            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold">{{ $service->name }}</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $service->websiteService?->custom_description ?: $service->description }}</p>
            </article>
        @endforeach
    </div>
</section>

@if($realizations->isNotEmpty())
<section id="realisations" class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex items-end justify-between gap-6">
        <div>
            <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Réalisations</p>
            <h2 class="mt-3 text-3xl font-semibold">{{ $homepage['realizations_title'] ?? 'Nos chantiers' }}</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ $homepage['realizations_intro'] ?? '' }}</p>
        </div>
        <a href="{{ route('public.contact') }}" class="text-sm font-semibold no-underline" style="color: var(--brand-primary)">Parler de votre projet</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($realizations as $realization)
            @php($photo = $realization->primaryMedia())
            <article class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
                @if($photo)
                    <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="h-72 w-full object-cover" loading="lazy">
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-slate-900">{{ $realization->title }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ $realization->city_label ?: $company->city }}</p>
                    @if($realization->description)
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $realization->description }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

@if($testimonials->isNotEmpty())
<section id="faq" class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-8">
        <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Avis</p>
        <h2 class="mt-3 text-3xl font-semibold">{{ $homepage['testimonials_title'] ?? 'Ils parlent de nous' }}</h2>
    </div>
    <div class="grid gap-6 md:grid-cols-3">
        @foreach($testimonials as $testimonial)
            <article class="rounded-[2rem] bg-slate-100 p-6">
                <p class="text-sm leading-6 text-slate-700">{{ $testimonial->content }}</p>
                <p class="mt-4 text-sm font-medium text-slate-900">{{ $testimonial->author_name }}{{ $testimonial->author_city ? ' — '.$testimonial->author_city : '' }}</p>
            </article>
        @endforeach
    </div>
</section>
@endif

@if($latestPosts->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 py-10">
    <div class="flex items-end justify-between gap-6">
        <div>
            <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Blog local</p>
            <h2 class="mt-3 text-3xl font-semibold">{{ $homepage['blog_title'] ?? 'Les derniers conseils utiles' }}</h2>
        </div>
        <a href="{{ route('public.blog.index') }}" class="text-sm font-semibold no-underline" style="color: var(--brand-primary)">Voir tous les articles</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($latestPosts as $post)
            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $post->category ?? 'Conseil' }}</p>
                <h3 class="mt-3 text-xl font-semibold">{{ $post->title }}</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt ?: $post->meta_description }}</p>
                <a href="{{ route('public.blog.show', $post->slug) }}" class="mt-5 inline-flex text-sm font-semibold no-underline" style="color: var(--brand-primary)">Lire l’article</a>
            </article>
        @endforeach
    </div>
</section>
@endif
