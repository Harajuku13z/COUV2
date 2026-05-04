@php($editing = isset($realization))

<div class="row g-4">
    <div class="col-lg-8">
        <label class="form-label fw-semibold">Titre</label>
        <input type="text" name="title" value="{{ old('title', $realization->title ?? '') }}" required>
    </div>
    <div class="col-lg-4">
        <label class="form-label fw-semibold">Ville ou secteur</label>
        <input type="text" name="city_label" value="{{ old('city_label', $realization->city_label ?? '') }}" placeholder="Ex. Lille, Roubaix, Côte d'Opale">
    </div>
    <div class="col-lg-4">
        <label class="form-label fw-semibold">Date de réalisation</label>
        <input type="date" name="completed_at" value="{{ old('completed_at', isset($realization?->completed_at) ? \Illuminate\Support\Carbon::parse($realization->completed_at)->format('Y-m-d') : '') }}">
    </div>
    <div class="col-lg-4">
        <label class="form-label fw-semibold">Ordre d affichage</label>
        <input type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $realization->sort_order ?? 0) }}">
    </div>
    <div class="col-lg-4 d-flex align-items-end">
        <label class="d-flex align-items-center gap-2 rounded-4 border bg-white px-4 py-3">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $realization->is_featured ?? true) ? 'checked' : '' }}>
            <span class="fw-semibold text-slate-800">Mettre en avant sur le site</span>
        </label>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="5" placeholder="Décris la réalisation, le contexte, le besoin du client et le résultat final.">{{ old('description', $realization->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Photos</label>
        <input type="file" name="photos[]" accept="image/*" multiple>
        <p class="mt-2 text-sm text-slate-500">Tu peux ajouter plusieurs photos à une même réalisation. Elles seront optimisées automatiquement.</p>
    </div>
</div>

@if($editing && $realization->media->isNotEmpty())
    <div class="mt-5">
        <h3 class="text-lg font-semibold text-slate-900">Photos déjà ajoutées</h3>
        <div class="row g-4 mt-1">
            @foreach($realization->media as $photo)
                <div class="col-md-6 col-xl-4">
                    <article class="rounded-4 border bg-white p-3 shadow-sm h-100">
                        <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="w-100 rounded-4 object-fit-cover" style="height: 220px;" loading="lazy">
                        <div class="mt-3 d-flex align-items-center justify-content-between gap-2">
                            <div class="text-sm text-slate-500">Photo {{ $loop->iteration }}</div>
                            <form action="{{ \App\Support\CentralAppUrl::admin('realizations/'.$realization->id.'/photos/'.$photo->id) }}" method="POST" onsubmit="return confirm('Supprimer cette photo ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn-danger">Retirer</button>
                            </form>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
@endif
