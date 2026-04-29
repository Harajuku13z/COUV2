@php
    $list = collect($breadcrumbs)->values();
    $items = $list->map(function (array $crumb, int $index): array {
        return array_filter([
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['name'],
            'item' => $crumb['url'] ?? null,
        ]);
    })->all();
@endphp

<nav aria-label="Fil d ariane" class="mx-auto max-w-6xl px-4 pt-6 text-sm text-slate-500">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach($list as $crumb)
            <li class="flex items-center gap-2">
                @if(! $loop->first)
                    <span>/</span>
                @endif
                @if(! empty($crumb['url']) && ! $loop->last)
                    <a href="{{ $crumb['url'] }}" class="hover:text-slate-900">{{ $crumb['name'] }}</a>
                @else
                    <span class="text-slate-700">{{ $crumb['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
