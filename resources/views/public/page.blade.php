@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-16">
    <div class="rounded-[2.5rem] bg-white p-8 shadow-sm md:p-12">
        <h1 class="text-4xl font-semibold" style="font-family: var(--font-heading)">{{ $page->content->h1 ?? $page->slug }}</h1>
        <p class="mt-5 text-lg text-slate-600">{{ $page->content->intro ?? '' }}</p>

        @foreach(($page->content->sections ?? []) as $section)
            <section class="mt-10">
                <h2 class="text-2xl font-semibold">{{ $section['title'] ?? 'Section' }}</h2>
                <div class="mt-3 space-y-4 leading-7 text-slate-700">{!! nl2br(e($section['content'] ?? '')) !!}</div>
            </section>
        @endforeach
    </div>
</section>
@endsection
