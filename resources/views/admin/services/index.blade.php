@extends('layouts.admin')
@section('title', 'Services')

@section('content')
<div class="space-y-8">
    <section class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div>
                <p class="admin-kicker" style="color:rgba(255,244,232,0.72);">Offre commerciale</p>
                <h1 class="admin-page-title" style="color:#fff7ed;">Tes vrais services actifs, prêts pour la génération locale.</h1>
                <p class="admin-page-copy" style="color:rgba(248,244,236,0.78);">
                    Cette page ne remonte que les services réellement activés pendant la configuration du site. Tu peux ici affiner le discours, les mots-clés et le brief photo pour l’IA.
                </p>
            </div>
            <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                <p class="text-sm font-semibold text-white/70">Génération globale</p>
                <p class="mt-2 text-sm leading-6 text-white/65">
                    Lance la préparation des pages sur {{ count($departmentCodes) }} département(s) actif(s) pour tous les services que tu proposes.
                </p>
                <div class="mt-5 flex flex-col gap-3">
                    <form action="{{ route('admin.services.generate-all-pages') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-primary w-full">Générer toutes les pages IA</button>
                    </form>
                    <a href="{{ route('admin.pages.index') }}" class="admin-link-btn admin-btn-secondary w-full">Voir les pages générées</a>
                </div>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-stat-grid md:grid-cols-2 xl:grid-cols-4">
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Services actifs</p>
            <p class="admin-stat-value">{{ $stats['services'] }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Services urgence</p>
            <p class="admin-stat-value">{{ $stats['emergency'] }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Pages déjà liées</p>
            <p class="admin-stat-value">{{ $stats['pages'] }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Départements actifs</p>
            <p class="admin-stat-value">{{ $stats['departments'] }}</p>
        </article>
    </div>

    @if ($services->isEmpty())
        <div class="admin-panel admin-panel-strong p-12 text-center">
            <p class="text-sm text-slate-500">Aucun service actif pour le moment.</p>
            <a href="{{ route('admin.services.create') }}" class="admin-link-btn admin-btn-primary mt-4">Créer un service</a>
        </div>
    @else
        @foreach ($services as $category => $categoryServices)
            <section class="admin-panel admin-panel-strong p-6">
                <div class="admin-section-head">
                    <div>
                        <h2 class="admin-section-title capitalize">{{ $category ?: 'Sans catégorie' }}</h2>
                        <p class="admin-section-copy">{{ $categoryServices->count() }} service(s) actif(s) dans cette catégorie.</p>
                    </div>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    @foreach ($categoryServices as $websiteService)
                        @php($service = $websiteService->service)
                        <article class="rounded-[26px] border border-slate-200/70 bg-white/84 p-5 shadow-sm">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-xl font-extrabold text-slate-900">{{ $service->name }}</h3>
                                        @if ($service->is_emergency)
                                            <span class="admin-badge admin-badge-accent">Urgence</span>
                                        @endif
                                        <span class="admin-badge admin-badge-success">Actif</span>
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $websiteService->custom_description ?: ($service->description ?: 'Aucune description spécifique n’a encore été définie.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Pages</p>
                                    <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $service->pages_count }}</p>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="admin-note">
                                    <p class="m-0 text-sm font-semibold text-slate-500">Vocabulaire SEO</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $websiteService->keyword_focus ?: 'Aucune consigne mot-clé pour le moment.' }}</p>
                                </div>
                                <div class="admin-note" style="background:rgba(199,119,45,0.06);border-color:rgba(199,119,45,0.10);">
                                    <p class="m-0 text-sm font-semibold text-slate-500">Brief photo</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $websiteService->photo_brief ?: 'Aucun brief photo renseigné pour le moment.' }}</p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-3">
                                <form action="{{ route('admin.services.generate-pages', $service->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-primary">Générer les pages IA</button>
                                </form>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="admin-link-btn admin-btn-secondary">Configurer le service</a>
                            </div>

                            @if ($websiteService->custom_price)
                                <p class="mt-4 text-sm font-semibold text-slate-500">Repère tarifaire : <span class="text-slate-800">{{ $websiteService->custom_price }}</span></p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif
</div>
@endsection
