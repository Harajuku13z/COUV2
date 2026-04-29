@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold">Leads</h1>
        <a href="{{ route('admin.leads.export') }}" class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Exporter CSV</a>
    </div>
    <div class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Nom</th><th class="px-4 py-3 text-left">Telephone</th><th class="px-4 py-3 text-left">Statut</th><th class="px-4 py-3 text-left"></th></tr></thead>
            <tbody>
            @foreach($leads as $lead)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $lead->name }}</td>
                    <td class="px-4 py-3">{{ $lead->phone }}</td>
                    <td class="px-4 py-3">{{ $lead->status }}</td>
                    <td class="px-4 py-3"><a href="{{ route('admin.leads.show', $lead) }}">Voir</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $leads->links() }}</div>
</section>
@endsection
