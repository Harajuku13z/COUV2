<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $theme = [
            'primary' => \App\Models\Setting::query()->where('key', 'brand_primary')->value('value') ?? '#4f7465',
            'secondary' => \App\Models\Setting::query()->where('key', 'brand_secondary')->value('value') ?? '#23312d',
            'accent' => \App\Models\Setting::query()->where('key', 'brand_accent')->value('value') ?? '#d97706',
            'heading_font' => \App\Models\Setting::query()->where('key', 'heading_font')->value('value') ?? 'Sora',
            'body_font' => \App\Models\Setting::query()->where('key', 'body_font')->value('value') ?? 'Instrument Sans',
        ];
        $seo = $seo ?? [
            'title' => $page->content->meta_title ?? $company->name ?? config('app.name'),
            'description' => $page->content->meta_description ?? $company->offer_text ?? 'Plateforme artisan SEO',
            'canonical' => isset($page) ? url($page->slug) : url('/'),
        ];
    @endphp
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <style>
        :root {
            --brand-primary: {{ $theme['primary'] }};
            --brand-secondary: {{ $theme['secondary'] }};
            --brand-accent: {{ $theme['accent'] }};
            --font-heading: '{{ $theme['heading_font'] }}', sans-serif;
            --font-body: '{{ $theme['body_font'] }}', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-50 text-slate-900">
    @include('components.public.header')
    @includeWhen(isset($weatherAlert) || (isset($weatherAlerts) && count($weatherAlerts) > 0), 'components.public.weather-alert', ['weatherAlert' => $weatherAlert ?? ($weatherAlerts[0] ?? null)])
    <main>
        @if (session('status'))
            <div class="mx-auto max-w-6xl px-4 py-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
    @include('components.public.footer')
    @stack('scripts')
</body>
</html>
