@extends('layouts.admin')
@section('title', 'Blog')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">Contenu</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Blog</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Rédigez et publiez des articles pour améliorer votre référencement naturel et informer vos clients.</p>
    </section>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif

    {{-- Stats row --}}
    @php
        $total     = $posts->total();
        $published = $posts->getCollection()->where('status','published')->count();
        $draft     = $posts->getCollection()->where('status','draft')->count();
    @endphp
    <div class="row g-3">
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Total articles</p>
                <p class="admin-stat-value">{{ $total }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Publiés</p>
                <p class="admin-stat-value">{{ $published }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Brouillons</p>
                <p class="admin-stat-value">{{ $draft }}</p>
            </div>
        </div>
    </div>

    {{-- Table panel --}}
    <div class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">Tous les articles</h2>
                <p class="admin-section-copy">{{ $total }} article{{ $total > 1 ? 's' : '' }} au total.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ \App\Support\CentralAppUrl::admin('blog/create') }}" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nouvel article
                </a>
            </div>
        </div>

        @if($posts->isEmpty())
            <div class="admin-note text-center py-5 mt-3">
                <i class="bi bi-journal-richtext fs-3 d-block mb-2 text-muted"></i>
                <p class="mb-0">Aucun article pour le moment.</p>
            </div>
        @else
            <div class="admin-table-wrap mt-3">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td style="max-width:280px;">
                                    <span class="fw-semibold d-inline-block text-truncate" style="max-width:270px;">
                                        {{ Str::limit($post->title, 60) }}
                                    </span>
                                </td>
                                <td>
                                    @if($post->category)
                                        <span class="admin-badge admin-badge-muted">{{ $post->category }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="admin-badge admin-badge-success"><i class="bi bi-check-lg me-1"></i>Publié</span>
                                    @else
                                        <span class="admin-badge admin-badge-muted">Brouillon</span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;color:var(--admin-muted);">
                                    {{ ($post->published_at ?? $post->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="text-end">
                                    <div class="admin-actions justify-content-end">
                                        <a href="{{ \App\Support\CentralAppUrl::admin('blog/'.$post->id.'/edit') }}"
                                           class="admin-btn admin-btn-warning" style="padding:.45rem .85rem;font-size:.8rem;">
                                            <i class="bi bi-pencil-fill me-1"></i>Modifier
                                        </a>
                                        <form action="{{ \App\Support\CentralAppUrl::admin('blog/'.$post->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer définitivement cet article ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger" style="padding:.45rem .85rem;font-size:.8rem;">
                                                <i class="bi bi-trash3-fill me-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
