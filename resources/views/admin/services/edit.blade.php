@extends('layouts.admin')
@section('title', 'Modifier le service')

@section('content')
@php($websiteService = $service->websiteService)
<div class="space-y-8">
    <section class="admin-panel admin-panel-strong p-6">
        <a href="{{ \App\Support\CentralAppUrl::admin('services') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 no-underline transition hover:text-slate-700">
            &larr; Retour aux services
        </a>
        <div class="mt-4">
            <p class="admin-kicker">Service</p>
            <h1 class="admin-page-title">{{ $service->name }}</h1>
            <p class="admin-page-copy">Affinage du service, du vocabulaire à employer et du type de visuels à demander à l’IA.</p>
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

    <form action="{{ \App\Support\CentralAppUrl::admin('services/'.$service->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-panel admin-panel-strong p-6 space-y-5">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Base du service</h2>
                    <p class="admin-section-copy">Nom, catégorie et description de fond.</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nom du service</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                </div>
                <div>
                    <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Catégorie</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $service->category) }}" required>
                </div>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Description générale</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $service->description) }}</textarea>
            </div>

            <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                <input type="hidden" name="is_emergency" value="0">
                <input type="checkbox" name="is_emergency" value="1" @checked(old('is_emergency', $service->is_emergency)) class="rounded border-slate-300 text-slate-900">
                Service d'urgence
            </label>
        </div>

        <div class="admin-panel admin-panel-strong p-6 space-y-5">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Consignes IA pour ce service</h2>
                    <p class="admin-section-copy">Ce sont ces informations qui serviront à personnaliser les pages générées pour ce service.</p>
                </div>
            </div>

            <div>
                <label for="custom_description" class="mb-2 block text-sm font-semibold text-slate-700">Angle commercial spécifique</label>
                <textarea id="custom_description" name="custom_description" rows="4" placeholder="Explique ici ce que tu veux vraiment mettre en avant pour ce service.">{{ old('custom_description', $websiteService?->custom_description) }}</textarea>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="custom_price" class="mb-2 block text-sm font-semibold text-slate-700">Repère tarifaire</label>
                    <input type="text" id="custom_price" name="custom_price" value="{{ old('custom_price', $websiteService?->custom_price) }}" placeholder="Ex : à partir de 390 €">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Statut site</label>
                    <div class="admin-note">
                        <span class="admin-badge {{ ($websiteService?->is_active ?? false) ? 'admin-badge-success' : 'admin-badge-muted' }}">
                            {{ ($websiteService?->is_active ?? false) ? 'Actif sur le site' : 'Inactif sur le site' }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <label for="keyword_focus" class="mb-2 block text-sm font-semibold text-slate-700">Mots et formulations à utiliser</label>
                <textarea id="keyword_focus" name="keyword_focus" rows="5" placeholder="Ex : recherche de fuite toiture, dépannage couverture, artisan couvreur local, tuiles cassées, infiltration...">{{ old('keyword_focus', $websiteService?->keyword_focus) }}</textarea>
            </div>

            <div>
                <label for="photo_brief" class="mb-2 block text-sm font-semibold text-slate-700">Brief photo à suggérer pour ce service</label>
                <textarea id="photo_brief" name="photo_brief" rows="5" placeholder="Ex : photos de chantier en cours, gros plan sur le savoir-faire, avant/après, équipe sur toiture sécurisée...">{{ old('photo_brief', $websiteService?->photo_brief) }}</textarea>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3">
            <a href="{{ \App\Support\CentralAppUrl::admin('services') }}" class="admin-link-btn admin-btn-secondary">Annuler</a>
            <button type="submit" class="admin-btn admin-btn-primary">Enregistrer</button>
        </div>
    </form>

    <form action="{{ \App\Support\CentralAppUrl::admin('services/'.$service->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce service ?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn admin-btn-danger">Supprimer ce service</button>
    </form>
</div>
@endsection
