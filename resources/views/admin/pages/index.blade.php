@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-semibold">Pages</h1>
        <form method="POST" action="{{ route('admin.pages.generate-all') }}">@csrf<button class="rounded-full px-4 py-2 text-white" style="background: var(--brand-primary)">Generer toutes les pages</button></form>
    </div>
    <div class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-left">Slug</th><th class="px-4 py-3 text-left">Type</th><th class="px-4 py-3 text-left">Statut</th><th class="px-4 py-3 text-left"></th></tr></thead>
            <tbody>
                @foreach($pages as $page)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $page->slug }}</td>
                        <td class="px-4 py-3">{{ $page->page_type }}</td>
                        <td class="px-4 py-3">{{ $page->status }}</td>
                        <td class="px-4 py-3"><a href="{{ route('admin.pages.show', $page) }}">Voir</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $pages->links() }}</div>
</section>
@endsection
