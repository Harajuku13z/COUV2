@extends('layouts.admin')

@section('title', 'Parametres API')

@section('content')
<section class="max-w-4xl">
    <form method="POST" action="{{ route('admin.api-settings.update') }}" class="space-y-4 rounded-[2rem] bg-white p-8 shadow-sm">
        @csrf
        <input name="openai_api_key" placeholder="OpenAI API key" class="w-full rounded-xl border px-3 py-2">
        <input name="serpapi_key" placeholder="SerpAPI key" class="w-full rounded-xl border px-3 py-2">
        <input name="openweather_api_key" placeholder="OpenWeather key" class="w-full rounded-xl border px-3 py-2">
        <button class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Sauvegarder</button>
    </form>
</section>
@endsection
