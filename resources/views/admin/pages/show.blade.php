@extends('layouts.admin')

@section('title', 'Detail page')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold">{{ $page->slug }}</h1>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.pages.regenerate', $page) }}">@csrf<button class="rounded-full border px-4 py-2">Regenerer</button></form>
            <form method="POST" action="{{ route('admin.pages.toggle-status', $page) }}">@csrf<button class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Basculer statut</button></form>
        </div>
    </div>
    <div class="mt-8 rounded-3xl bg-white p-8 shadow-sm">
        <p class="text-sm text-slate-500">{{ $page->status }}</p>
        <h2 class="mt-4 text-2xl font-semibold">{{ $page->content->h1 ?? 'Sans contenu' }}</h2>
        <p class="mt-4 text-slate-700">{{ $page->content->intro ?? '' }}</p>

        @if(!empty($page->content?->featured_image_path))
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-900">Image de mise en avant</h3>
                <img src="{{ asset('storage/'.$page->content->featured_image_path) }}" alt="{{ $page->content->featured_image_alt ?? ($page->content->h1 ?? 'Image de page') }}" class="mt-4 h-72 w-full rounded-[2rem] object-cover">
            </div>
        @endif

        @if (!empty($page->content?->photo_suggestions))
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-900">Suggestions photo</h3>
                <div class="mt-4 space-y-3">
                    @foreach ($page->content->photo_suggestions as $suggestion)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-medium text-slate-900">{{ $suggestion['title'] ?? 'Visuel' }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $suggestion['brief'] ?? '' }}</p>
                            @if (!empty($suggestion['alt']))
                                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">Alt : {{ $suggestion['alt'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($page->content?->realization_photos))
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-900">Photos récupérées depuis les réalisations</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @foreach ($page->content->realization_photos as $photo)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <img src="{{ $photo['url'] ?? asset('storage/'.($photo['path'] ?? '')) }}" alt="{{ $photo['alt'] ?? 'Photo réalisation' }}" class="h-56 w-full object-cover" loading="lazy">
                            <div class="p-4 text-sm text-slate-600">
                                {{ $photo['title'] ?? 'Réalisation' }}@if(!empty($photo['city_label'])) · {{ $photo['city_label'] }}@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
