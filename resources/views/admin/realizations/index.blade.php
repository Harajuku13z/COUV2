@extends('layouts.admin')
@section('title', 'Realisations')

@section('content')
<div class="space-y-8">
    <section class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div>
                <p class="admin-kicker" style="color:rgba(255,255,255,0.62);">Portfolio</p>
                <h1 class="admin-page-title" style="color:#ffffff;">Gère les chantiers, les photos et les réalisations visibles sur le site.</h1>
                <p class="admin-page-copy" style="color:rgba(255,255,255,0.72);">
                    Chaque réalisation peut avoir un titre, une description, un secteur et plusieurs photos. Les réalisations mises en avant peuvent ensuite nourrir la page d’accueil et les futures pages générées.
                </p>
            </div>
            <div class="d-flex align-items-end justify-content-lg-end">
                <a href="{{ \App\Support\CentralAppUrl::admin('realizations/create') }}" class="admin-link-btn admin-btn-primary">Ajouter une réalisation</a>
            </div>
        </div>
    </section>

    <div class="admin-stat-grid md:grid-cols-3">
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Réalisations</p>
            <p class="admin-stat-value">{{ $stats['realizations'] }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Mises en avant</p>
            <p class="admin-stat-value">{{ $stats['featured'] }}</p>
        </article>
        <article class="admin-panel admin-panel-strong admin-stat-card">
            <p class="admin-stat-label">Photos totales</p>
            <p class="admin-stat-value">{{ $stats['photos'] }}</p>
        </article>
    </div>

    @if($realizations->isEmpty())
        <div class="admin-panel admin-panel-strong p-12 text-center">
            <p class="text-sm text-slate-500">Aucune réalisation pour le moment.</p>
            <a href="{{ \App\Support\CentralAppUrl::admin('realizations/create') }}" class="admin-link-btn admin-btn-primary mt-4">Créer la première réalisation</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($realizations as $realization)
                @php($photo = $realization->primaryMedia())
                <div class="col-lg-6">
                    <article class="admin-panel admin-panel-strong h-100 overflow-hidden">
                        @if($photo)
                            <img src="{{ $photo->url ?? asset('storage/'.$photo->path) }}" alt="{{ $photo->alt_text ?? $realization->title }}" class="w-100 object-fit-cover" style="height: 260px;" loading="lazy">
                        @endif
                        <div class="p-4 p-lg-5">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <h2 class="m-0 text-2xl font-semibold text-slate-900">{{ $realization->title }}</h2>
                                @if($realization->is_featured)
                                    <span class="admin-badge admin-badge-success">Accueil</span>
                                @endif
                            </div>
                            <p class="mt-3 text-sm text-slate-500">
                                {{ $realization->city_label ?: 'Secteur non renseigné' }}
                                @if($realization->completed_at)
                                    • {{ \Illuminate\Support\Carbon::parse($realization->completed_at)->translatedFormat('F Y') }}
                                @endif
                            </p>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($realization->description ?: 'Aucune description pour le moment.', 220) }}</p>
                            <div class="mt-4 d-flex flex-wrap gap-2">
                                <span class="admin-pill">{{ $realization->media_count }} photo(s)</span>
                                <span class="admin-pill">Ordre {{ $realization->sort_order }}</span>
                            </div>
                            <div class="mt-5 d-flex flex-wrap gap-3">
                                <a href="{{ \App\Support\CentralAppUrl::admin('realizations/'.$realization->id.'/edit') }}" class="admin-link-btn admin-btn-secondary">Modifier</a>
                                <form action="{{ \App\Support\CentralAppUrl::admin('realizations/'.$realization->id) }}" method="POST" onsubmit="return confirm('Supprimer cette réalisation et toutes ses photos ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
