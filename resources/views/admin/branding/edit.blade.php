@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="text-3xl font-semibold">Branding</h1>
    <form method="POST" action="{{ route('admin.branding.update') }}" class="mt-8 grid gap-4 rounded-3xl bg-white p-8 shadow-sm md:grid-cols-2">
        @csrf
        <input name="brand_primary" value="{{ $settings['brand_primary'] ?? '#4f7465' }}" class="rounded-xl border px-3 py-2">
        <input name="brand_secondary" value="{{ $settings['brand_secondary'] ?? '#23312d' }}" class="rounded-xl border px-3 py-2">
        <input name="brand_accent" value="{{ $settings['brand_accent'] ?? '#d97706' }}" class="rounded-xl border px-3 py-2">
        <input name="heading_font" value="{{ $settings['heading_font'] ?? 'Sora' }}" class="rounded-xl border px-3 py-2">
        <input name="body_font" value="{{ $settings['body_font'] ?? 'Instrument Sans' }}" class="rounded-xl border px-3 py-2">
        <button class="rounded-full px-4 py-2 text-white md:col-span-2" style="background: var(--brand-primary)">Sauvegarder</button>
    </form>
</section>
@endsection
