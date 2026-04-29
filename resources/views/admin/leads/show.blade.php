@extends('layouts.admin')

@section('title', 'Lead')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="text-3xl font-semibold">{{ $lead->name }}</h1>
    <div class="mt-8 rounded-3xl bg-white p-8 shadow-sm">
        <p><strong>Telephone:</strong> {{ $lead->phone }}</p>
        <p class="mt-2"><strong>Email:</strong> {{ $lead->email }}</p>
        <p class="mt-2"><strong>Ville:</strong> {{ $lead->city_label }}</p>
        <p class="mt-2"><strong>Message:</strong> {{ $lead->message }}</p>
        <form method="POST" action="{{ route('admin.leads.update-status', $lead) }}" class="mt-6 space-y-4">
            @csrf
            <select name="status" class="rounded-xl border px-3 py-2">
                @foreach(['new','contacted','quoted','won','lost'] as $status)
                    <option value="{{ $status }}" @selected($lead->status === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <textarea name="notes" rows="4" class="w-full rounded-2xl border px-4 py-3" placeholder="Notes"></textarea>
            <button class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Mettre a jour</button>
        </form>
    </div>
</section>
@endsection
