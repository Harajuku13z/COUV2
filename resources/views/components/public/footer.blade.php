@php($footerCompany = $company ?? \App\Models\Company::query()->first())
@php($footerServices = \App\Models\Service::query()->take(8)->get())
@php($footerCities = \App\Models\City::query()->active()->orderByDesc('population')->take(8)->get())
<footer class="mt-16 bg-slate-950 text-slate-200">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 md:grid-cols-4">
        <div>
            <p class="font-semibold">{{ $footerCompany?->name ?? config('app.name') }}</p>
            <p class="mt-3 text-sm text-slate-400">{{ $footerCompany?->offer_text ?? 'Interventions locales, devis rapides et accompagnement de proximite.' }}</p>
        </div>
        <div>
            <p class="font-semibold">Services</p>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($footerServices as $service)
                    <a class="block text-slate-400" href="{{ url($service->slug) }}">{{ $service->name }}</a>
                @endforeach
            </div>
        </div>
        <div>
            <p class="font-semibold">Zone d'intervention</p>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($footerCities as $city)
                    <a class="block text-slate-400" href="{{ url($city->slug) }}">{{ $city->name }}</a>
                @endforeach
            </div>
        </div>
        <div>
            <p class="font-semibold">Contact</p>
            <div class="mt-3 space-y-2 text-sm text-slate-400">
                <p>{{ $footerCompany?->fullAddress() }}</p>
                <p>{{ $footerCompany?->phone }}</p>
                <p>{{ $footerCompany?->email }}</p>
            </div>
        </div>
    </div>
</footer>
