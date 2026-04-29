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
    </div>
</section>
@endsection
