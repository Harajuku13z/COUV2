@extends('layouts.admin')
@section('title', 'Témoignages')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Témoignages</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez les avis clients affichés sur votre site.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}"
           class="rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition-colors">
            + Ajouter
        </a>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        @if ($testimonials->isEmpty())
            <p class="text-sm text-slate-500 text-center py-8">Aucun témoignage pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-medium text-slate-500">Auteur</th>
                            <th class="pb-3 font-medium text-slate-500">Ville</th>
                            <th class="pb-3 font-medium text-slate-500">Service</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Note</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Visible</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Source</th>
                            <th class="pb-3 font-medium text-slate-500">Date</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($testimonials as $testimonial)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 font-medium text-slate-900">{{ $testimonial->author_name }}</td>
                                <td class="py-3 text-slate-600">{{ $testimonial->author_city ?: '—' }}</td>
                                <td class="py-3 text-slate-600 max-w-[140px] truncate">{{ $testimonial->service_label ?: '—' }}</td>
                                <td class="py-3 text-center">
                                    <span class="text-amber-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $testimonial->rating ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    @if ($testimonial->is_visible)
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Visible</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">Masqué</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if ($testimonial->source === 'google')
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">Google</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Manuel</span>
                                    @endif
                                </td>
                                <td class="py-3 text-slate-500 text-xs">{{ $testimonial->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.testimonials.toggle', $testimonial->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-2xl border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                                {{ $testimonial->is_visible ? 'Masquer' : 'Afficher' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                                           class="rounded-2xl border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                            Modifier
                                        </a>
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer ce témoignage ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-2xl border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($testimonials->hasPages())
                <div class="mt-6">
                    {{ $testimonials->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
