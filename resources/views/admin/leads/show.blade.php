@extends('layouts.admin')
@section('title', 'Lead : '.$lead->name)

@section('content')
<div class="d-grid gap-4">

    {{-- Back link --}}
    <div>
        <a href="{{ \App\Support\CentralAppUrl::admin('leads') }}" class="admin-btn admin-btn-secondary d-inline-flex">
            <i class="bi bi-arrow-left me-1"></i>Retour aux leads
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- Left: Lead details (col-lg-8) --}}
        <div class="col-lg-8">
            <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                <div class="admin-section-head mb-4">
                    <div>
                        <p class="admin-kicker">Demande entrante</p>
                        <h1 class="admin-page-title">{{ $lead->name }}</h1>
                    </div>
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
                    <span class="admin-badge {{ $s['class'] }} fs-6">{{ $s['label'] }}</span>
                </div>

                <div class="row g-3 mb-4">
                    @if($lead->phone)
                        <div class="col-md-6">
                            <div class="admin-note">
                                <div class="admin-kicker mb-1">Téléphone</div>
                                <a href="tel:{{ $lead->phone }}" class="fw-semibold text-decoration-none" style="color:var(--admin-secondary);">
                                    <i class="bi bi-telephone-fill me-1"></i>{{ $lead->phone }}
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($lead->email)
                        <div class="col-md-6">
                            <div class="admin-note">
                                <div class="admin-kicker mb-1">E-mail</div>
                                <a href="mailto:{{ $lead->email }}" class="fw-semibold text-decoration-none" style="color:var(--admin-secondary);">
                                    {{ $lead->email }}
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($lead->city)
                        <div class="col-md-6">
                            <div class="admin-note">
                                <div class="admin-kicker mb-1">Ville</div>
                                <span class="fw-semibold"><i class="bi bi-geo-alt-fill me-1"></i>{{ $lead->city }}</span>
                            </div>
                        </div>
                    @endif
                    @if($lead->service_requested)
                        <div class="col-md-6">
                            <div class="admin-note">
                                <div class="admin-kicker mb-1">Service demandé</div>
                                <span class="fw-semibold">{{ $lead->service_requested }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                @if($lead->message)
                    <div class="mb-3">
                        <p class="form-label fw-semibold mb-1">Message</p>
                        <div class="admin-note" style="white-space:pre-wrap;">{{ $lead->message }}</div>
                    </div>
                @endif

                @if($lead->urgency_level)
                    <div class="mb-3">
                        <p class="form-label fw-semibold mb-1">Niveau d'urgence</p>
                        <span class="admin-badge admin-badge-accent">{{ ucfirst($lead->urgency_level) }}</span>
                    </div>
                @endif

                @if($lead->source_url)
                    <div class="mb-3">
                        <p class="form-label fw-semibold mb-1">Page source</p>
                        <a href="{{ $lead->source_url }}" target="_blank" rel="noopener"
                           class="text-decoration-none text-muted" style="font-size:.88rem;word-break:break-all;">
                            {{ $lead->source_url }}
                        </a>
                    </div>
                @endif

                @if($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
                    <div class="admin-note mt-3" style="font-size:.84rem;">
                        <p class="admin-kicker mb-2">Paramètres UTM</p>
                        <div class="row g-2">
                            @if($lead->utm_source)
                                <div class="col-auto"><span class="admin-pill">utm_source: {{ $lead->utm_source }}</span></div>
                            @endif
                            @if($lead->utm_medium)
                                <div class="col-auto"><span class="admin-pill">utm_medium: {{ $lead->utm_medium }}</span></div>
                            @endif
                            @if($lead->utm_campaign)
                                <div class="col-auto"><span class="admin-pill">utm_campaign: {{ $lead->utm_campaign }}</span></div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Status update (col-lg-4) --}}
        <div class="col-lg-4 d-grid gap-4">

            <div class="admin-panel admin-panel-strong p-4">
                <h2 class="admin-section-title mb-4">Mettre à jour le statut</h2>

                <form action="{{ \App\Support\CentralAppUrl::admin('leads/'.$lead->id.'/status') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Statut</label>
                        <select id="status" name="status" class="form-select">
                            @foreach(['new' => 'Nouveau', 'contacted' => 'Contacté', 'quoted' => 'Devisé', 'won' => 'Gagné', 'lost' => 'Perdu'] as $val => $label)
                                <option value="{{ $val }}" @selected($lead->status === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold">Notes internes</label>
                        <textarea id="notes" name="notes" rows="5"
                                  placeholder="Ajoutez vos remarques sur ce lead…"
                                  class="form-control">{{ old('notes', $lead->notes ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
                        <i class="bi bi-save me-1"></i>Enregistrer
                    </button>
                </form>
            </div>

            <div class="admin-panel p-4">
                <p class="admin-kicker mb-1">Reçu le</p>
                <p class="fw-semibold mb-0">{{ $lead->created_at->format('d/m/Y à H:i') }}</p>
            </div>

        </div>
    </div>

</div>
@endsection
