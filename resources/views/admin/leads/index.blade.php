@extends('layouts.admin')
@section('title', 'Demandes entrantes')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">CRM</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Demandes entrantes</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Suivez et qualifiez les leads générés par votre site.</p>
    </section>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif

    {{-- Stats row --}}
    @php
        $totalLeads   = $leads->total();
        $newLeads     = $leads->getCollection()->where('status','new')->count();
        $monthLeads   = $leads->getCollection()->filter(fn($l) => $l->created_at->isCurrentMonth())->count();
    @endphp
    <div class="row g-3">
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Total leads</p>
                <p class="admin-stat-value">{{ $totalLeads }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Nouveaux</p>
                <p class="admin-stat-value">{{ $newLeads }}</p>
                <p class="admin-stat-note">À traiter</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-panel admin-stat-card">
                <p class="admin-stat-label">Ce mois-ci</p>
                <p class="admin-stat-value">{{ $monthLeads }}</p>
            </div>
        </div>
    </div>

    {{-- Table panel --}}
    <div class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">Tous les leads</h2>
                <p class="admin-section-copy">{{ $totalLeads }} demande{{ $totalLeads > 1 ? 's' : '' }} reçue{{ $totalLeads > 1 ? 's' : '' }}.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ \App\Support\CentralAppUrl::admin('leads/export') }}" class="admin-btn admin-btn-secondary">
                    <i class="bi bi-arrow-left me-1" style="transform:rotate(270deg);display:inline-block;"></i>Exporter CSV
                </a>
            </div>
        </div>

        @if($leads->isEmpty())
            <div class="admin-note text-center py-5 mt-3">
                <i class="bi bi-telephone-fill fs-3 d-block mb-2 text-muted"></i>
                <p class="mb-0">Aucune demande pour le moment.</p>
            </div>
        @else
            <div class="admin-table-wrap mt-3">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Contact</th>
                            <th>Service demandé</th>
                            <th>Ville</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:.9rem;">{{ $lead->name }}</div>
                                    @if($lead->phone)
                                        <div class="text-muted" style="font-size:.78rem;">
                                            <i class="bi bi-telephone-fill me-1"></i>{{ $lead->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td style="max-width:160px;">
                                    <span class="d-inline-block text-truncate" style="max-width:150px;">
                                        {{ $lead->service_requested ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $lead->city ?? '—' }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'new'       => ['label' => 'Nouveau',   'class' => 'admin-badge-success'],
                                            'contacted' => ['label' => 'Contacté',  'class' => 'admin-badge-accent'],
                                            'quoted'    => ['label' => 'Devisé',    'class' => 'admin-badge-muted'],
                                            'won'       => ['label' => 'Gagné',     'class' => 'admin-badge-success'],
                                            'lost'      => ['label' => 'Perdu',     'class' => 'admin-badge-muted'],
                                        ];
                                        $s = $statusMap[$lead->status] ?? ['label' => $lead->status, 'class' => 'admin-badge-muted'];
                                    @endphp
                                    <span class="admin-badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </td>
                                <td style="font-size:.82rem;color:var(--admin-muted);">
                                    {{ $lead->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ \App\Support\CentralAppUrl::admin('leads/'.$lead->id) }}"
                                       class="admin-btn admin-btn-secondary" style="padding:.45rem .85rem;font-size:.8rem;">
                                        <i class="bi bi-eye me-1"></i>Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
                <div class="mt-4">
                    {{ $leads->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
