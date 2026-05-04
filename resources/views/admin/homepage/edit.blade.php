@extends('layouts.admin')
@section('title', 'Page d accueil')

@section('content')
@php
    $homepageUrl = \App\Support\CentralAppUrl::admin('homepage');
    $selectedTemplate = old('selected_template', $settings['selected_template'] ?? 'template-1');
    $trustItemsValue = old('trust_items_input', implode("\n", $settings['trust_items'] ?? []));
    $highlightItemsValue = old('highlight_items_input', implode("\n", $settings['highlight_items'] ?? []));
@endphp

<div class="space-y-8">
    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="admin-panel admin-panel-dark overflow-hidden">
        <div class="grid gap-8 px-6 py-7 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div>
                <p class="admin-kicker" style="color:rgba(255,255,255,0.62);">Accueil</p>
                <h1 class="admin-page-title" style="color:#ffffff;">Choisis un des 5 designs d’accueil puis préremplis le contenu avec l’IA.</h1>
                <p class="admin-page-copy" style="color:rgba(255,255,255,0.72);">
                    Le moteur utilise les informations déjà enregistrées sur l’entreprise, les services actifs, les départements, les avis et les réalisations pour remplir automatiquement le template choisi.
                </p>
            </div>
            <div class="rounded-[26px] border border-white/10 bg-white/6 p-5">
                <p class="text-sm font-semibold text-white/70">Contexte disponible</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-4 bg-white/5 p-3 text-white">
                        <div class="text-xs uppercase text-white/50">Services actifs</div>
                        <div class="mt-2 text-2xl fw-bold">{{ $stats['services'] }}</div>
                    </div>
                    <div class="rounded-4 bg-white/5 p-3 text-white">
                        <div class="text-xs uppercase text-white/50">Réalisations</div>
                        <div class="mt-2 text-2xl fw-bold">{{ $stats['realizations'] }}</div>
                    </div>
                    <div class="rounded-4 bg-white/5 p-3 text-white">
                        <div class="text-xs uppercase text-white/50">Avis visibles</div>
                        <div class="mt-2 text-2xl fw-bold">{{ $stats['testimonials'] }}</div>
                    </div>
                    <div class="rounded-4 bg-white/5 p-3 text-white">
                        <div class="text-xs uppercase text-white/50">Départements</div>
                        <div class="mt-2 text-2xl fw-bold">{{ $stats['departments'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-panel admin-panel-strong p-4 p-lg-5">
        <div class="admin-section-head">
            <div>
                <h2 class="admin-section-title">Choix du design</h2>
                <p class="admin-section-copy">Sélectionne la variante qui servira de base publique sur la page d’accueil.</p>
            </div>
            <form method="POST" action="{{ $homepageUrl.'/generate-ai' }}" class="d-flex gap-2 flex-wrap">
                @csrf
                <input type="hidden" name="selected_template" id="selected_template_ai" value="{{ $selectedTemplate }}">
                <button type="submit" class="admin-btn admin-btn-primary">Préremplir avec l’IA</button>
            </form>
        </div>

        <div class="row g-4">
            @foreach($templates as $key => $template)
                <div class="col-md-6 col-xl-4">
                    <label class="d-block h-100">
                        <input class="d-none js-template-radio" type="radio" name="template_choice_visual" value="{{ $key }}" {{ $selectedTemplate === $key ? 'checked' : '' }}>
                        <span class="d-flex h-100 flex-column rounded-4 border bg-white p-4 shadow-sm js-template-card {{ $selectedTemplate === $key ? 'border-dark' : 'border-light' }}">
                            <span class="text-xs uppercase tracking-[0.22em] text-slate-400">{{ strtoupper(str_replace('template-', 'Template ', $key)) }}</span>
                            <span class="mt-3 text-lg font-semibold text-slate-900">{{ $template['name'] }}</span>
                            <span class="mt-2 text-sm leading-6 text-slate-600">{{ $template['description'] }}</span>
                            <span class="mt-4 rounded-4 bg-slate-100 p-3 text-sm text-slate-700">
                                @if($key === 'template-1')
                                    Hero éditorial + réassurance + grille services.
                                @elseif($key === 'template-2')
                                    Hero sombre + preuve terrain + zones couvertes.
                                @elseif($key === 'template-3')
                                    Conversion directe + CTA téléphone + arguments simples.
                                @elseif($key === 'template-4')
                                    Version sobre et premium, très équilibrée.
                                @else
                                    Approche portfolio avec visuels de réalisations.
                                @endif
                            </span>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </section>

    <form method="POST" action="{{ $homepageUrl }}" class="space-y-8">
        @csrf
        <input type="hidden" name="selected_template" id="selected_template" value="{{ $selectedTemplate }}">

        <section class="admin-panel admin-panel-strong p-4 p-lg-5">
            <div class="admin-section-head">
                <div>
                    <h2 class="admin-section-title">Contenu principal</h2>
                    <p class="admin-section-copy">Tu peux laisser l’IA remplir puis ajuster à la main chaque bloc.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kicker hero</label>
                    <input type="text" name="hero_kicker" value="{{ old('hero_kicker', $settings['hero_kicker'] ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">CTA principal</label>
                    <input type="text" name="primary_cta" value="{{ old('primary_cta', $settings['primary_cta'] ?? 'Demander un devis') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">CTA secondaire</label>
                    <input type="text" name="secondary_cta" value="{{ old('secondary_cta', $settings['secondary_cta'] ?? 'Voir nos réalisations') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Titre hero</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? ($company?->name ?? '')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description hero</label>
                    <textarea name="hero_description" rows="4">{{ old('hero_description', $settings['hero_description'] ?? ($company?->offer_text ?? '')) }}</textarea>
                </div>
            </div>
        </section>

        <section class="admin-panel admin-panel-strong p-4 p-lg-5">
            <div class="row g-4">
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Titre services</label>
                    <input type="text" name="services_title" value="{{ old('services_title', $settings['services_title'] ?? '') }}">
                    <label class="form-label fw-semibold mt-4">Introduction services</label>
                    <textarea name="services_intro" rows="4">{{ old('services_intro', $settings['services_intro'] ?? '') }}</textarea>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Titre réalisations</label>
                    <input type="text" name="realizations_title" value="{{ old('realizations_title', $settings['realizations_title'] ?? '') }}">
                    <label class="form-label fw-semibold mt-4">Introduction réalisations</label>
                    <textarea name="realizations_intro" rows="4">{{ old('realizations_intro', $settings['realizations_intro'] ?? '') }}</textarea>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Titre avis</label>
                    <input type="text" name="testimonials_title" value="{{ old('testimonials_title', $settings['testimonials_title'] ?? '') }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Titre blog</label>
                    <input type="text" name="blog_title" value="{{ old('blog_title', $settings['blog_title'] ?? '') }}">
                </div>
            </div>
        </section>

        <section class="admin-panel admin-panel-strong p-4 p-lg-5">
            <div class="row g-4">
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Points de confiance</label>
                    <textarea name="trust_items_input" rows="6" placeholder="Un point par ligne">{{ $trustItemsValue }}</textarea>
                    <p class="mt-2 text-sm text-slate-500">Exemple : Intervention rapide, entreprise locale, devis clair.</p>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Mises en avant</label>
                    <textarea name="highlight_items_input" rows="6" placeholder="Un point par ligne">{{ $highlightItemsValue }}</textarea>
                    <p class="mt-2 text-sm text-slate-500">Exemple : Pose complète, dépannage, rénovation, entretien.</p>
                </div>
            </div>
        </section>

        <div class="d-flex flex-wrap gap-3">
            <button type="submit" class="admin-btn admin-btn-primary">Enregistrer la page d’accueil</button>
            <a href="{{ \App\Support\CentralAppUrl::app() }}" class="admin-link-btn admin-btn-secondary" target="_blank" rel="noreferrer">Voir la page publique</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = Array.from(document.querySelectorAll('.js-template-radio'));
        const selectedInput = document.getElementById('selected_template');
        const selectedAiInput = document.getElementById('selected_template_ai');

        const syncSelection = (value) => {
            if (selectedInput) {
                selectedInput.value = value;
            }
            if (selectedAiInput) {
                selectedAiInput.value = value;
            }
            document.querySelectorAll('.js-template-card').forEach((card, index) => {
                const active = radios[index] && radios[index].value === value;
                card.classList.toggle('border-dark', active);
                card.classList.toggle('border-light', !active);
            });
        };

        radios.forEach((radio) => {
            radio.addEventListener('change', function () {
                syncSelection(this.value);
            });
        });

        const checked = radios.find((radio) => radio.checked);
        if (checked) {
            syncSelection(checked.value);
        }
    });
</script>
@endpush
