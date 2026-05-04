@extends('layouts.admin')
@section('title', 'Pages SEO')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">SEO</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Production SEO locale</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Générez et gérez vos pages de contenu optimisées pour le référencement local.</p>
    </section>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Stats row --}}
    @php
        $totalPages     = $pages->total();
        $publishedPages = $pages->getCollection()->where('status','published')->count();
        $draftPages     = $pages->getCollection()->where('status','draft')->count();
        $warnPages      = $pages->getCollection()->filter(fn($p) => ($p->similarity_score ?? 0) > 0.7)->count();
    @endphp
    <div class="row g-3">
        <div class="col-md-3">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Total pages</p>
                <p class="admin-stat-value">{{ $totalPages }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Publiées</p>
                <p class="admin-stat-value">{{ $publishedPages }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Brouillons</p>
                <p class="admin-stat-value">{{ $draftPages }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Similarité élevée</p>
                <p class="admin-stat-value">{{ $warnPages }}</p>
                <p class="admin-stat-note">Score &gt; 0.7</p>
            </div>
        </div>
    </div>

    {{-- Filters + Generate all --}}
    <div class="admin-panel admin-panel-strong p-4">
        <div class="d-flex flex-wrap gap-3 align-items-end justify-content-between">
            <form method="GET" action="{{ \App\Support\CentralAppUrl::admin('pages') }}" class="d-flex flex-wrap gap-3 align-items-end">
                <div>
                    <label for="filter_status" class="form-label fw-semibold mb-1" style="font-size:.82rem;">Statut</label>
                    <select id="filter_status" name="status" class="form-select form-select-sm" style="min-width:130px;">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected(request('status') === 'published')>Publiées</option>
                        <option value="draft"     @selected(request('status') === 'draft')>Brouillons</option>
                    </select>
                </div>
                <div>
                    <label for="filter_type" class="form-label fw-semibold mb-1" style="font-size:.82rem;">Type de page</label>
                    <select id="filter_type" name="page_type" class="form-select form-select-sm" style="min-width:150px;">
                        <option value="">Tous les types</option>
                        <option value="service_city"  @selected(request('page_type') === 'service_city')>Service + Ville</option>
                        <option value="city"          @selected(request('page_type') === 'city')>Ville</option>
                        <option value="service"       @selected(request('page_type') === 'service')>Service</option>
                        <option value="faq"           @selected(request('page_type') === 'faq')>FAQ</option>
                    </select>
                </div>
                <button type="submit" class="admin-btn admin-btn-secondary" style="padding:.55rem 1rem;font-size:.85rem;">
                    Filtrer
                </button>
                @if(request()->hasAny(['status','page_type']))
                    <a href="{{ \App\Support\CentralAppUrl::admin('pages') }}" class="admin-btn admin-btn-warning" style="padding:.55rem 1rem;font-size:.85rem;">
                        <i class="bi bi-x-lg me-1"></i>Réinitialiser
                    </a>
                @endif
            </form>

            <form method="POST" action="{{ \App\Support\CentralAppUrl::admin('pages/generate-all') }}"
                  onsubmit="return confirm('Générer toutes les pages ? Cette opération peut prendre plusieurs minutes.')">
                @csrf
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Générer toutes les pages
                </button>
            </form>
        </div>
    </div>

    {{-- Table panel --}}
    <div class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head mb-3">
            <h2 class="admin-section-title">Liste des pages</h2>
        </div>

        @if($pages->isEmpty())
            <div class="admin-note text-center py-5">
                <i class="bi bi-journal-richtext fs-3 d-block mb-2 text-muted"></i>
                <p class="mb-0">Aucune page générée pour le moment.</p>
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Ville</th>
                            <th>Service</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td style="font-size:.82rem;font-family:monospace;max-width:220px;">
                                    <span class="d-inline-block text-truncate" style="max-width:210px;">{{ $page->slug }}</span>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-muted">{{ $page->page_type }}</span>
                                </td>
                                <td>
                                    @if($page->status === 'published')
                                        <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Publiée</span>
                                    @else
                                        <span class="admin-badge admin-badge-muted">Brouillon</span>
                                    @endif
                                </td>
                                <td style="font-size:.85rem;">{{ $page->city?->name ?? '—' }}</td>
                                <td style="font-size:.85rem;max-width:130px;">
                                    <span class="d-inline-block text-truncate" style="max-width:120px;">
                                        {{ $page->service?->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="admin-actions justify-content-end">
                                        <a href="{{ \App\Support\CentralAppUrl::admin('pages/'.$page->id) }}"
                                           class="admin-btn admin-btn-secondary" style="padding:.4rem .75rem;font-size:.78rem;">
                                            <i class="bi bi-eye me-1"></i>Voir
                                        </a>
                                        <form action="{{ \App\Support\CentralAppUrl::admin('pages/'.$page->id.'/regenerate') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-btn admin-btn-warning" style="padding:.4rem .75rem;font-size:.78rem;">
                                                Régénérer
                                            </button>
                                        </form>
                                        <form action="{{ \App\Support\CentralAppUrl::admin('pages/'.$page->id.'/toggle-status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-btn admin-btn-secondary" style="padding:.4rem .75rem;font-size:.78rem;">
                                                @if($page->status === 'published')
                                                    <i class="bi bi-toggle-on me-1"></i>Dépublier
                                                @else
                                                    <i class="bi bi-toggle-off me-1"></i>Publier
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pages->hasPages())
                <div class="mt-4">
                    {{ $pages->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
