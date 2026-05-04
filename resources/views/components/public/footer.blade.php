@php($footerCompany = $company ?? \App\Models\Company::query()->first())
@php($footerServices = \App\Models\Service::query()->take(8)->get())
@php($footerCities = \App\Models\City::query()->active()->orderByDesc('population')->take(8)->get())
<footer class="mt-20">
    <div class="mx-auto max-w-7xl px-4">
        <section class="rounded-[2.5rem] border border-slate-200 bg-white px-6 py-8 shadow-[0_24px_60px_rgba(15,23,42,0.06)] md:px-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Votre projet commence ici</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-950" style="font-family: var(--font-heading)">Un site clair. Une offre lisible. Une prise de contact simple.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ $footerCompany?->offer_text ?? 'Interventions locales, devis rapides et accompagnement de proximité.' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="tel:{{ $footerCompany?->phone }}" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-800 no-underline">Appeler</a>
                    <a href="{{ route('public.devis') }}" class="rounded-full px-5 py-3 text-sm font-semibold text-white no-underline" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));">Demander un devis</a>
                </div>
            </div>
        </section>
    </div>
    <div class="mx-auto mt-6 grid max-w-7xl gap-10 px-4 py-12 md:grid-cols-4">
        <div>
            <p class="font-semibold text-slate-950">{{ $footerCompany?->name ?? config('app.name') }}</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $footerCompany?->offer_text ?? 'Interventions locales, devis rapides et accompagnement de proximite.' }}</p>
        </div>
        <div>
            <p class="font-semibold text-slate-950">Services</p>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($footerServices as $service)
                    @php($serviceSlug = \App\Models\Page::query()->published()->where('service_id', $service->id)->value('slug'))
                    <a class="block text-slate-600 no-underline transition hover:text-slate-950" href="{{ $serviceSlug ? url($serviceSlug) : url('/') }}">{{ $service->name }}</a>
                @endforeach
            </div>
        </div>
        <div>
            <p class="font-semibold text-slate-950">Zone d'intervention</p>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($footerCities as $city)
                    @php($citySlug = \App\Models\Page::query()->published()->where('city_id', $city->id)->value('slug'))
                    <a class="block text-slate-600 no-underline transition hover:text-slate-950" href="{{ $citySlug ? url($citySlug) : route('public.contact') }}">{{ $city->name }}</a>
                @endforeach
            </div>
        </div>
        <div>
            <p class="font-semibold text-slate-950">Contact</p>
            <div class="mt-3 space-y-2 text-sm text-slate-600">
                <p>{{ $footerCompany?->fullAddress() }}</p>
                <p>{{ $footerCompany?->phone }}</p>
                <p>{{ $footerCompany?->email }}</p>
            </div>
        </div>
    </div>
</footer>
