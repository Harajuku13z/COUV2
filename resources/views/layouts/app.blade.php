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
            'type' => 'website',
            'image' => isset($company?->logo_path) ? asset('storage/'.$company->logo_path) : null,
            'robots' => isset($page) && $page->status !== 'published' ? 'noindex,nofollow' : 'index,follow',
        ];
        $schema = $schema ?? [];
        $breadcrumbs = $breadcrumbs ?? [];
    @endphp
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ $company->name ?? config('app.name') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Instrument+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    @if(! empty($seo['image']))
        <meta property="og:image" content="{{ $seo['image'] }}">
        <meta name="twitter:image" content="{{ $seo['image'] }}">
        <link rel="preload" as="image" href="{{ $seo['image'] }}">
    @endif
    <style>
        :root {
            --brand-primary: {{ $theme['primary'] }};
            --brand-secondary: {{ $theme['secondary'] }};
            --brand-accent: {{ $theme['accent'] }};
            --font-heading: '{{ $theme['heading_font'] }}', sans-serif;
            --font-body: '{{ $theme['body_font'] }}', sans-serif;
            --site-ink: #111827;
            --site-muted: #5b6472;
            --site-panel: rgba(255, 255, 255, 0.84);
            --site-border: rgba(15, 23, 42, 0.08);
            --site-shadow: 0 22px 55px rgba(15, 23, 42, 0.08);
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(79, 116, 101, 0.08), transparent 24%),
                radial-gradient(circle at top right, rgba(217, 119, 6, 0.06), transparent 18%),
                linear-gradient(180deg, #fcfcfb 0%, #f8fafc 42%, #f4f7fb 100%);
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
        @if($breadcrumbs !== [])
            @include('components.public.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        @endif
        @yield('content')
    </main>
    @include('components.public.footer')
    @foreach($schema as $schemaBlock)
        @if(! empty($schemaBlock))
            <script type="application/ld+json">{!! json_encode($schemaBlock, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endif
    @endforeach
    @stack('scripts')
</body>
</html>
