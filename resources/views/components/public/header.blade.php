@php($headerCompany = $company ?? \App\Models\Company::query()->first())
<header class="sticky top-0 z-40">
    <div class="mx-auto max-w-7xl px-4 pt-4">
        <div class="flex items-center justify-between gap-4 rounded-full border border-slate-200/80 bg-white/86 px-4 py-3 shadow-[0_16px_40px_rgba(15,23,42,0.06)] backdrop-blur md:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3 font-semibold text-slate-900 no-underline" style="font-family: var(--font-heading)">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold text-white" style="background: linear-gradient(135deg, var(--brand-secondary), var(--brand-primary));">
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($headerCompany?->name ?? 'A', 0, 1)) }}
                </span>
                <span>{{ $headerCompany?->name ?? config('app.name') }}</span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 md:flex">
                <a class="no-underline transition hover:text-slate-950" href="{{ url('/') }}#services">Services</a>
                <a class="no-underline transition hover:text-slate-950" href="{{ url('/') }}#realisations">Réalisations</a>
                <a class="no-underline transition hover:text-slate-950" href="{{ route('public.blog.index') }}">Conseils</a>
                <a class="no-underline transition hover:text-slate-950" href="{{ url('/') }}#faq">Avis</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="tel:{{ $headerCompany?->phone }}" class="hidden text-sm font-semibold text-slate-700 no-underline lg:block">{{ $headerCompany?->phone }}</a>
                <a href="{{ route('public.devis') }}" class="rounded-full px-4 py-2.5 text-sm font-semibold text-white no-underline shadow-sm" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));">Obtenir un devis</a>
            </div>
        </div>
    </div>
</header>
