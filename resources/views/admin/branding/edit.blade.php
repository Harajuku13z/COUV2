@extends('layouts.admin')
@section('title', 'Identité visuelle')

@section('content')
<div class="d-grid gap-4">

    {{-- Hero banner --}}
    <section class="admin-panel admin-panel-dark p-4 p-lg-5">
        <p class="admin-kicker" style="color:rgba(255,244,232,.72);">Configuration</p>
        <h1 class="admin-page-title" style="color:#fff7ed;">Identité visuelle</h1>
        <p class="admin-page-copy" style="color:rgba(248,244,236,.78);">Personnalisez les couleurs, les polices et les visuels de votre site.</p>
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

    <div class="row g-4 align-items-start">

        {{-- Left: settings (col-lg-8) --}}
        <div class="col-lg-8 d-grid gap-4">

            {{-- Colors + fonts --}}
            <form action="{{ \App\Support\CentralAppUrl::admin('branding') }}" method="POST">
                @csrf

                <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                    <h2 class="admin-section-title mb-4">Couleurs de marque</h2>

                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label for="brand_primary" class="form-label fw-semibold">Couleur principale</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="brand_primary_picker"
                                       value="{{ $settings['brand_primary'] ?? '#4f7465' }}"
                                       oninput="document.getElementById('brand_primary').value=this.value"
                                       style="width:44px;height:44px;border:none;background:none;cursor:pointer;padding:0;">
                                <input type="text" id="brand_primary" name="brand_primary"
                                       value="{{ old('brand_primary', $settings['brand_primary'] ?? '#4f7465') }}"
                                       class="form-control font-monospace"
                                       oninput="document.getElementById('brand_primary_picker').value=this.value"
                                       placeholder="#4f7465">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="brand_secondary" class="form-label fw-semibold">Couleur secondaire</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="brand_secondary_picker"
                                       value="{{ $settings['brand_secondary'] ?? '#23312d' }}"
                                       oninput="document.getElementById('brand_secondary').value=this.value"
                                       style="width:44px;height:44px;border:none;background:none;cursor:pointer;padding:0;">
                                <input type="text" id="brand_secondary" name="brand_secondary"
                                       value="{{ old('brand_secondary', $settings['brand_secondary'] ?? '#23312d') }}"
                                       class="form-control font-monospace"
                                       oninput="document.getElementById('brand_secondary_picker').value=this.value"
                                       placeholder="#23312d">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="brand_accent" class="form-label fw-semibold">Couleur d'accentuation</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="brand_accent_picker"
                                       value="{{ $settings['brand_accent'] ?? '#d97706' }}"
                                       oninput="document.getElementById('brand_accent').value=this.value"
                                       style="width:44px;height:44px;border:none;background:none;cursor:pointer;padding:0;">
                                <input type="text" id="brand_accent" name="brand_accent"
                                       value="{{ old('brand_accent', $settings['brand_accent'] ?? '#d97706') }}"
                                       class="form-control font-monospace"
                                       oninput="document.getElementById('brand_accent_picker').value=this.value"
                                       placeholder="#d97706">
                            </div>
                        </div>
                    </div>

                    <h2 class="admin-section-title mb-4">Polices</h2>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="heading_font" class="form-label fw-semibold">Police des titres</label>
                            <input type="text" id="heading_font" name="heading_font"
                                   value="{{ old('heading_font', $settings['heading_font'] ?? 'Sora') }}"
                                   placeholder="Ex : Sora, Fraunces, Poppins"
                                   class="form-control">
                            <div class="form-text">Nom Google Fonts exact.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="body_font" class="form-label fw-semibold">Police du corps</label>
                            <input type="text" id="body_font" name="body_font"
                                   value="{{ old('body_font', $settings['body_font'] ?? 'Instrument Sans') }}"
                                   placeholder="Ex : Instrument Sans, Manrope, Inter"
                                   class="form-control">
                            <div class="form-text">Nom Google Fonts exact.</div>
                        </div>
                    </div>

                    <div class="admin-actions mt-4 justify-content-end">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="bi bi-save me-1"></i>Sauvegarder les styles
                        </button>
                    </div>
                </div>

            </form>

            {{-- Logo upload --}}
            <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                <h2 class="admin-section-title mb-4">Logo</h2>

                <form action="{{ \App\Support\CentralAppUrl::admin('branding/logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold">Fichier logo <span class="text-muted fw-normal">(PNG, SVG, WebP recommandé)</span></label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="form-control">
                        @if(!empty($settings['logo_path']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Logo actuel"
                                     style="max-height:60px;border-radius:8px;border:1px solid rgba(17,24,39,.08);">
                                <span class="ms-2 text-muted" style="font-size:.82rem;">Logo actuel</span>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="admin-btn admin-btn-secondary">
                        Mettre à jour le logo
                    </button>
                </form>
            </div>

            {{-- Favicon upload --}}
            <div class="admin-panel admin-panel-strong p-4 p-lg-5">
                <h2 class="admin-section-title mb-4">Favicon</h2>

                <form action="{{ \App\Support\CentralAppUrl::admin('branding/favicon') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="favicon" class="form-label fw-semibold">Fichier favicon <span class="text-muted fw-normal">(ICO, PNG 32×32 recommandé)</span></label>
                        <input type="file" id="favicon" name="favicon" accept="image/*" class="form-control">
                        @if(!empty($settings['favicon_path']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$settings['favicon_path']) }}" alt="Favicon actuel"
                                     style="width:32px;height:32px;border-radius:4px;border:1px solid rgba(17,24,39,.08);">
                                <span class="ms-2 text-muted" style="font-size:.82rem;">Favicon actuel</span>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="admin-btn admin-btn-secondary">
                        Mettre à jour le favicon
                    </button>
                </form>
            </div>

        </div>

        {{-- Right: Live preview (col-lg-4) --}}
        <div class="col-lg-4">
            <div class="admin-panel admin-panel-strong p-4" id="brandPreview">
                <p class="admin-kicker mb-3">Aperçu</p>
                <div class="rounded-3 p-4 mb-3"
                     id="previewPrimary"
                     style="background:{{ $settings['brand_primary'] ?? '#4f7465' }};">
                    <p class="mb-0 fw-bold text-white" style="font-family:'{{ $settings['heading_font'] ?? 'Sora' }}',sans-serif;font-size:1.1rem;">
                        Titre en police {{ $settings['heading_font'] ?? 'Sora' }}
                    </p>
                </div>
                <div class="rounded-3 p-4 mb-3"
                     id="previewSecondary"
                     style="background:{{ $settings['brand_secondary'] ?? '#23312d' }};">
                    <p class="mb-0 text-white" style="font-family:'{{ $settings['body_font'] ?? 'Instrument Sans' }}',sans-serif;font-size:.9rem;">
                        Corps de texte en {{ $settings['body_font'] ?? 'Instrument Sans' }}.<br>
                        Lisibilité et confort de lecture.
                    </p>
                </div>
                <div class="rounded-3 p-3"
                     id="previewAccent"
                     style="background:{{ $settings['brand_accent'] ?? '#d97706' }};">
                    <p class="mb-0 fw-bold text-white" style="font-size:.85rem;">
                        Couleur d'accentuation — CTA, liens, icônes
                    </p>
                </div>

                <div class="mt-3 admin-note" style="font-size:.82rem;">
                    <p class="mb-1 fw-semibold">Couleurs actuelles</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="admin-pill" id="labelPrimary" style="font-size:.75rem;">
                            Primaire : {{ $settings['brand_primary'] ?? '#4f7465' }}
                        </span>
                        <span class="admin-pill" id="labelSecondary" style="font-size:.75rem;">
                            Secondaire : {{ $settings['brand_secondary'] ?? '#23312d' }}
                        </span>
                        <span class="admin-pill" id="labelAccent" style="font-size:.75rem;">
                            Accent : {{ $settings['brand_accent'] ?? '#d97706' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Live preview update
    document.addEventListener('input', function(e) {
        if (e.target.id === 'brand_primary' || e.target.id === 'brand_primary_picker') {
            document.getElementById('previewPrimary').style.background = e.target.value;
            document.getElementById('labelPrimary').textContent = 'Primaire : ' + e.target.value;
        }
        if (e.target.id === 'brand_secondary' || e.target.id === 'brand_secondary_picker') {
            document.getElementById('previewSecondary').style.background = e.target.value;
            document.getElementById('labelSecondary').textContent = 'Secondaire : ' + e.target.value;
        }
        if (e.target.id === 'brand_accent' || e.target.id === 'brand_accent_picker') {
            document.getElementById('previewAccent').style.background = e.target.value;
            document.getElementById('labelAccent').textContent = 'Accent : ' + e.target.value;
        }
    });
</script>
@endsection
