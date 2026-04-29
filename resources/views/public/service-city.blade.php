@extends('layouts.app')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 md:grid-cols-[1fr_360px]">
    <div>
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

        @if($page->outgoingInternalLinks->isNotEmpty())
            <section class="mt-12">
                <h2 class="text-2xl font-semibold">Continuer votre visite</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach($page->outgoingInternalLinks as $link)
                        @php($targetSlug = $link->toPage?->slug)
                        <a href="{{ $targetSlug ? url($targetSlug) : route('public.contact') }}" class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5">
                            <p class="text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $link->link_type)) }}</p>
                            <p class="mt-2 text-lg font-semibold">{{ $link->anchor_text }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mt-12">
            <h2 class="text-2xl font-semibold">Zone d intervention</h2>
            <div class="mt-6">
                @include('components.public.map-embed', ['city' => $page->city, 'company' => $company, 'query' => ($page->city?->name ?? '').' '.($page->city?->postal_code ?? '')])
            </div>
        </section>
    </div>
    <aside class="space-y-6">
        @include('components.public.form-devis')
        @if($page->service?->is_emergency)
            @include('components.public.form-urgence')
        @endif
        <div class="rounded-[2rem] bg-slate-950 p-6 text-slate-100">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Contact direct</p>
            <a href="tel:{{ $company->phone }}" class="mt-4 block text-2xl font-semibold">{{ $company->phone }}</a>
            <p class="mt-3 text-sm text-slate-300">{{ $company->fullAddress() }}</p>
        </div>
    </aside>
</section>
@endsection
