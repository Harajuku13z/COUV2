@extends('layouts.admin')
@section('title', 'Blog')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Blog</h1>
            <p class="mt-1 text-sm text-slate-500">Gérez les articles de votre blog.</p>
        </div>
        <a href="{{ route('admin.blog.create') }}"
           class="rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition-colors">
            + Nouvel article
        </a>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Posts table --}}
    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        @if ($posts->isEmpty())
            <p class="text-sm text-slate-500 text-center py-8">Aucun article pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 font-medium text-slate-500">Titre</th>
                            <th class="pb-3 font-medium text-slate-500">Catégorie</th>
                            <th class="pb-3 font-medium text-slate-500 text-center">Statut</th>
                            <th class="pb-3 font-medium text-slate-500">Date</th>
                            <th class="pb-3 font-medium text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($posts as $post)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 font-medium text-slate-900 max-w-[280px]">
                                    <span class="truncate block">{{ $post->title }}</span>
                                </td>
                                <td class="py-3 text-slate-600">{{ $post->category ?: '—' }}</td>
                                <td class="py-3 text-center">
                                    @if ($post->status === 'published')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                            Publié
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                            Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-slate-500 text-xs">
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.blog.edit', $post->id) }}"
                                           class="rounded-2xl border border-slate-300 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                            Modifier
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer définitivement cet article ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-2xl border border-red-200 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
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

            @if ($posts->hasPages())
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
