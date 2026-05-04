@extends('layouts.admin')
@section('title', 'Page : '.$page->slug)

@section('content')
<div class="d-grid gap-4">

    {{-- Back link --}}
    <div>
        <a href="{{ \App\Support\CentralAppUrl::admin('pages') }}" class="admin-btn admin-btn-secondary d-inline-flex">
            <i class="bi bi-arrow-left me-1"></i>Retour aux pages
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- Left: Page details (col-lg-8) --}}
        <div class="col-lg-8">
            <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                <div class="admin-section-head mb-4">
                    <div>
                        <p class="admin-kicker">Page SEO</p>
                        <h1 class="admin-page-title">{{ $page->content->h1 ?? $page->slug }}</h1>
                    </div>
                    @if($page->status === 'published')
                        <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Publiée</span>
                    @else
                        <span class="admin-badge admin-badge-muted">Brouillon</span>
                    @endif
                </div>

                @if($page->content?->intro)
                    <div class="admin-note mb-4">
                        <p class="admin-kicker mb-1">Introduction</p>
                        <p class="mb-0" style="font-size:.92rem;line-height:1.7;">
                            {{ Str::limit($page->content->intro, 300) }}
                        </p>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="form-label fw-semibold mb-1">Type de page</p>
                        <span class="admin-badge admin-badge-accent">{{ $page->page_type }}</span>
                    </div>

                    <div class="col-md-6">
                        <p class="form-label fw-semibold mb-1">Slug</p>
                        <code style="font-size:.82rem;color:var(--admin-secondary);">{{ $page->slug }}</code>
                    </div>

                    @if(isset($page->similarity_score))
                        <div class="col-md-6">
                            <p class="form-label fw-semibold mb-1">Score de similarité</p>
                            @if($page->similarity_score > 0.7)
                                <span class="admin-badge admin-badge-accent">
                                    ⚠ {{ number_format($page->similarity_score, 2) }} — Contenu trop similaire
                                </span>
                            @else
                                <span class="admin-badge admin-badge-success">
                                    {{ number_format($page->similarity_score, 2) }} — OK
                                </span>
                            @endif
                        </div>
                    @endif

                    @if($page->last_generated_at)
                        <div class="col-md-6">
                            <p class="form-label fw-semibold mb-1">Dernière génération</p>
                            <span style="font-size:.88rem;color:var(--admin-muted);">
                                {{ $page->last_generated_at->format('d/m/Y à H:i') }}
                            </span>
                        </div>
                    @endif

                    @if(isset($page->view_count))
                        <div class="col-md-6">
                            <p class="form-label fw-semibold mb-1">Vues</p>
                            <span class="fw-semibold">{{ number_format($page->view_count) }}</span>
                        </div>
                    @endif

                    @if(isset($page->lead_count))
                        <div class="col-md-6">
                            <p class="form-label fw-semibold mb-1">Leads générés</p>
                            <span class="fw-semibold">{{ $page->lead_count }}</span>
                        </div>
                    @endif
                </div>

                @if($page->content?->meta_title || $page->content?->meta_description)
                    <hr class="my-4" style="border-color:rgba(17,24,39,.06);">
                    <h3 class="admin-section-title mb-3">SEO</h3>
                    @if($page->content?->meta_title)
                        <div class="mb-2">
                            <p class="form-label fw-semibold mb-1">Meta title</p>
                            <p class="mb-0" style="font-size:.9rem;">{{ $page->content->meta_title }}</p>
                        </div>
                    @endif
                    @if($page->content?->meta_description)
                        <div class="mb-2">
                            <p class="form-label fw-semibold mb-1">Meta description</p>
                            <p class="mb-0 text-muted" style="font-size:.88rem;">{{ $page->content->meta_description }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Right: Actions (col-lg-4) --}}
        <div class="col-lg-4 d-grid gap-4">

            <div class="admin-panel admin-panel-strong p-4">
                <h2 class="admin-section-title mb-4">Actions</h2>

                <div class="d-grid gap-3">
                    <form action="{{ \App\Support\CentralAppUrl::admin('pages/'.$page->id.'/regenerate') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
                            Régénérer le contenu
                        </button>
                    </form>

                    <form action="{{ \App\Support\CentralAppUrl::admin('pages/'.$page->id.'/toggle-status') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-secondary w-100 justify-content-center">
                            @if($page->status === 'published')
                                <i class="bi bi-toggle-on me-1"></i>Dépublier la page
                            @else
                                <i class="bi bi-toggle-off me-1"></i>Publier la page
                            @endif
                        </button>
                    </form>
                </div>
            </div>

            @if($page->similarity_score > 0.7)
                <div class="admin-panel p-4">
                    <p class="admin-kicker mb-2" style="color:#7f1d1d;">Alerte qualité</p>
                    <p class="mb-0" style="font-size:.88rem;color:var(--admin-secondary);">
                        Le score de similarité de cette page est élevé ({{ number_format($page->similarity_score, 2) }}).
                        Pensez à régénérer ou à diversifier le contenu.
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
