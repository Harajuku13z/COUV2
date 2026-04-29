@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-16">
    <h1 class="text-4xl font-semibold">{{ $page->content->h1 ?? $page->slug }}</h1>
    <div class="mt-6 prose max-w-none text-slate-700">
        {!! nl2br(e($page->content->intro ?? '')) !!}
    </div>
</section>
@endsection
