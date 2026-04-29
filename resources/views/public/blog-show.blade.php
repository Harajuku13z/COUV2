@extends('layouts.app')

@section('content')
<article class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1fr_320px]">
    <div>
        <p class="text-sm uppercase tracking-[0.28em]" style="color: var(--brand-primary)">{{ $post->category ?? 'Blog' }}</p>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">{{ $post->title }}</h1>
        <p class="mt-4 text-lg text-slate-600">{{ $post->excerpt ?: $post->meta_description }}</p>

        @if($post->featured_image)
            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="mt-8 h-[420px] w-full rounded-[2rem] object-cover" loading="eager">
        @endif

        <div class="prose prose-slate mt-10 max-w-none leading-7">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
    <aside class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Besoin d un avis rapide ?</p>
            <a href="{{ route('public.contact') }}" class="mt-4 inline-flex rounded-full px-4 py-2 text-sm font-semibold text-white" style="background: var(--brand-primary)">Parler a un artisan</a>
        </div>

        @if($latestPosts->isNotEmpty())
            <div class="rounded-[2rem] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold">Articles a lire ensuite</h2>
                <div class="mt-4 space-y-4">
                    @foreach($latestPosts as $item)
                        <a href="{{ route('public.blog.show', $item->slug) }}" class="block rounded-2xl border border-slate-200 p-4">
                            <p class="font-medium">{{ $item->title }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $item->meta_description }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </aside>
</article>
@endsection
