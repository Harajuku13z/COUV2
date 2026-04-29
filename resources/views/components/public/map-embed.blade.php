@php
    $query = $query ?? trim(($company->fullAddress() ?? '').' '.($city->name ?? ''));
    $mapUrl = 'https://www.google.com/maps?q='.urlencode($query).'&output=embed';
@endphp

<div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
    <iframe
        src="{{ $mapUrl }}"
        title="Carte zone d intervention"
        class="h-[320px] w-full"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
