@php($headerCompany = $company ?? \App\Models\Company::query()->first())
<header class="border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
        <a href="{{ url('/') }}" class="font-semibold" style="font-family: var(--font-heading)">
            {{ $headerCompany?->name ?? config('app.name') }}
        </a>
        <nav class="hidden gap-6 text-sm md:flex">
            <a href="{{ url('/') }}#services">Services</a>
            <a href="{{ url('/') }}#zones">Zones</a>
            <a href="{{ route('public.blog.index') }}">Blog</a>
            <a href="{{ url('/') }}#faq">FAQ</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="tel:{{ $headerCompany?->phone }}" class="hidden text-sm md:block">{{ $headerCompany?->phone }}</a>
            <a href="{{ route('public.devis') }}" class="rounded-full px-4 py-2 text-sm font-medium text-white" style="background: var(--brand-primary)">Devis Gratuit</a>
        </div>
    </div>
</header>
