@extends('layouts.app')

@section('content')
@php
    $selectedTemplate = $homepage['selected_template'] ?? 'template-1';
    $trustItems = collect($homepage['trust_items'] ?? [])->filter()->values();
    $highlightItems = collect($homepage['highlight_items'] ?? [])->filter()->values();
    $ctaPrimaryLabel = $homepage['primary_cta'] ?? 'Demander un devis';
    $ctaSecondaryLabel = $homepage['secondary_cta'] ?? 'Voir nos réalisations';
@endphp

@includeIf('public.home-templates.'.$selectedTemplate, [
    'company' => $company,
    'services' => $services,
    'testimonials' => $testimonials,
    'realizations' => $realizations,
    'latestPosts' => $latestPosts,
    'weatherAlert' => $weatherAlert,
    'homepage' => $homepage,
    'departmentCodes' => $departmentCodes,
    'trustItems' => $trustItems,
    'highlightItems' => $highlightItems,
    'ctaPrimaryLabel' => $ctaPrimaryLabel,
    'ctaSecondaryLabel' => $ctaSecondaryLabel,
])
@endsection
