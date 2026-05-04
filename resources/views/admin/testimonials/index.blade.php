@extends('layouts.admin')
@section('title', 'Témoignages')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">Marketing</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Preuve sociale</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Gérez les avis clients affichés sur votre site pour renforcer la confiance des visiteurs.</p>
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

    {{-- Table panel --}}
    <div class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">Tous les témoignages</h2>
                <p class="admin-section-copy">{{ $testimonials->total() }} témoignage{{ $testimonials->total() > 1 ? 's' : '' }} au total.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ \App\Support\CentralAppUrl::admin('testimonials/create') }}" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Ajouter
                </a>
            </div>
        </div>

        @if($testimonials->isEmpty())
            <div class="admin-note text-center py-5">
                <i class="bi bi-star-fill fs-3 d-block mb-2 text-muted"></i>
                <p class="mb-0">Aucun témoignage pour le moment.</p>
            </div>
        @else
            <div class="admin-table-wrap mt-3">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Auteur</th>
                            <th>Service</th>
                            <th>Note</th>
                            <th>Source</th>
                            <th>Visibilité</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                             style="width:36px;height:36px;background:var(--admin-accent);font-size:.8rem;flex-shrink:0;">
                                            {{ strtoupper(mb_substr($testimonial->author_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.9rem;">{{ $testimonial->author_name }}</div>
                                            @if($testimonial->author_city)
                                                <div class="text-muted" style="font-size:.78rem;">{{ $testimonial->author_city }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="max-width:140px;">
                                    <span class="d-inline-block text-truncate" style="max-width:130px;">
                                        {{ $testimonial->service_label ?: '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#d97706;letter-spacing:.05em;">
                                        @for($i = 1; $i <= 5; $i++){{ $i <= $testimonial->rating ? '★' : '☆' }}@endfor
                                    </span>
                                </td>
                                <td>
                                    @if($testimonial->source === 'google')
                                        <span class="admin-badge admin-badge-accent">Google</span>
                                    @else
                                        <span class="admin-badge admin-badge-muted">Manuel</span>
                                    @endif
                                </td>
                                <td>
                                    @if($testimonial->is_visible)
                                        <span class="admin-badge admin-badge-success"><i class="bi bi-eye me-1"></i>Visible</span>
                                    @else
                                        <span class="admin-badge admin-badge-muted"><i class="bi bi-eye-slash me-1"></i>Masqué</span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;color:var(--admin-muted);">
                                    {{ $testimonial->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-end">
                                    <div class="admin-actions justify-content-end">
                                        <form action="{{ \App\Support\CentralAppUrl::admin('testimonials/'.$testimonial->id.'/toggle') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="admin-btn admin-btn-secondary" style="padding:.45rem .85rem;font-size:.8rem;">
                                                @if($testimonial->is_visible)
                                                    <i class="bi bi-eye-slash me-1"></i>Masquer
                                                @else
                                                    <i class="bi bi-eye me-1"></i>Afficher
                                                @endif
                                            </button>
                                        </form>
                                        <a href="{{ \App\Support\CentralAppUrl::admin('testimonials/'.$testimonial->id.'/edit') }}"
                                           class="admin-btn admin-btn-warning" style="padding:.45rem .85rem;font-size:.8rem;">
                                            <i class="bi bi-pencil-fill me-1"></i>Modifier
                                        </a>
                                        <form action="{{ \App\Support\CentralAppUrl::admin('testimonials/'.$testimonial->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce témoignage ?')">
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

            @if($testimonials->hasPages())
                <div class="mt-4">
                    {{ $testimonials->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
