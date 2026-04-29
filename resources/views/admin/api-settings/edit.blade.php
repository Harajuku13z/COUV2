@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="text-3xl font-semibold">Cles API</h1>
    <form method="POST" action="{{ route('admin.api-settings.update') }}" class="mt-8 space-y-4 rounded-3xl bg-white p-8 shadow-sm">
        @csrf
        <input name="openai_api_key" placeholder="OpenAI API key" class="w-full rounded-xl border px-3 py-2">
        <input name="serpapi_key" placeholder="SerpAPI key" class="w-full rounded-xl border px-3 py-2">
        <input name="openweather_api_key" placeholder="OpenWeather key" class="w-full rounded-xl border px-3 py-2">
        <button class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Sauvegarder</button>
    </form>
</section>
@endsection
