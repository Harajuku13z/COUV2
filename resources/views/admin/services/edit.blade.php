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

    <form action="{{ \App\Support\CentralAppUrl::admin('services/'.$service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

        <div class="admin-panel admin-panel-strong p-6 space-y-5">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Photos du service</h2>
                    <p class="admin-section-copy">La première photo sert de visuel de mise en avant. Les pages générées récupèrent aussi les photos des réalisations existantes.</p>
                </div>
            </div>

            <div>
                <label for="photos" class="mb-2 block text-sm font-semibold text-slate-700">Ajouter des photos</label>
                <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
                <p class="mt-2 text-sm text-slate-500">Ajoute une ou plusieurs photos du service. La première sera utilisée comme image principale si disponible.</p>
            </div>

            @if($service->media->isNotEmpty())
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($service->media as $photo)
                        <article class="rounded-[24px] border border-slate-200 bg-white p-3 shadow-sm">
                            <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $service->name }}" class="h-52 w-full rounded-[18px] object-cover" loading="lazy">
                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $loop->first ? 'Photo principale' : 'Photo service' }}</p>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Ordre {{ $photo->sort_order }}</p>
                                </div>
                                <form action="{{ \App\Support\CentralAppUrl::admin('services/'.$service->id.'/photos/'.$photo->id) }}" method="POST" onsubmit="return confirm('Supprimer cette photo du service ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger">Retirer</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="admin-note">
                    <p class="m-0 text-sm text-slate-600">Aucune photo service pour le moment. Si tu laisses vide, les pages essaieront d’utiliser les photos des réalisations correspondantes.</p>
                </div>
            @endif
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
