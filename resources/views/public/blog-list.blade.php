@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">Ressources</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">Conseils, meteo et actualites terrain</h1>
        <p class="mt-4 text-lg text-slate-600">Des contenus utiles pour anticiper les risques, mieux planifier vos travaux et comprendre les bons reflexes locaux.</p>
    </div>

    <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($posts as $post)
            <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                @if($post->featured_image)
                    <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="h-56 w-full object-cover" loading="lazy">
                @endif
                <div class="p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $post->category ?? 'Conseil' }}</p>
                    <h2 class="mt-3 text-2xl font-semibold">{{ $post->title }}</h2>
                    <p class="mt-3 text-sm text-slate-600">{{ $post->excerpt ?: $post->meta_description }}</p>
                    <a href="{{ route('public.blog.show', $post->slug) }}" class="mt-6 inline-flex text-sm font-semibold" style="color: var(--brand-primary)">Lire l article</a>
                </div>
            </article>
        @empty
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-10 text-slate-500 md:col-span-2 xl:col-span-3">
                Aucun article n est encore publie.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</section>
@endsection
