@extends('layouts.app')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 md:grid-cols-[1fr_360px]">
    <div>
        <nav class="text-sm text-slate-500">Accueil / {{ $page->service?->name }} / {{ $page->city?->name }}</nav>
        <h1 class="mt-4 text-4xl font-semibold" style="font-family: var(--font-heading)">{{ $page->content->h1 ?? $page->slug }}</h1>
        <p class="mt-6 text-lg text-slate-700">{{ $page->content->intro ?? '' }}</p>

        @foreach(($page->content->sections ?? []) as $section)
            <section class="mt-10">
                <h2 class="text-2xl font-semibold">{{ $section['title'] ?? 'Section' }}</h2>
                <div class="mt-3 prose max-w-none text-slate-700">{!! nl2br(e($section['content'] ?? '')) !!}</div>
            </section>
        @endforeach

        @if(($page->content->faq ?? []) !== [])
            <section class="mt-12">
                <h2 class="text-2xl font-semibold">Questions frequentes</h2>
                <div class="mt-6 space-y-4">
                    @foreach($page->content->faq as $faq)
                        <details class="rounded-2xl border border-slate-200 bg-white p-4">
                            <summary class="cursor-pointer font-medium">{{ $faq['question'] ?? 'Question' }}</summary>
                            <p class="mt-3 text-sm text-slate-600">{{ $faq['answer'] ?? '' }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
    <aside class="space-y-6">
        @include('components.public.form-devis')
        @if($page->service?->is_emergency)
            @include('components.public.form-urgence')
        @endif
    </aside>
</section>
@endsection
