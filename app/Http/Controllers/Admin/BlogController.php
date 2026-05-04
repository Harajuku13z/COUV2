<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()->orderByDesc('created_at')->paginate(20);
        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'content'          => ['required', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'category'         => ['nullable', 'string', 'max:100'],
            'status'           => ['required', 'in:draft,published'],
        ]);
        $validated['slug']             = Str::slug($validated['title']);
        $validated['meta_title']       = $validated['meta_title'] ?? $validated['title'];
        $validated['meta_description'] = $validated['meta_description'] ?? $validated['excerpt'] ?? '';
        $validated['tags']             = [];
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }
        BlogPost::query()->create($validated);
        return redirect()->route('admin.blog.index')->with('status', 'Article créé.');
    }

    public function edit(int $id): View
    {
        $post = BlogPost::query()->findOrFail($id);
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'content'          => ['required', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'category'         => ['nullable', 'string', 'max:100'],
            'status'           => ['required', 'in:draft,published'],
        ]);
        $post = BlogPost::query()->findOrFail($id);
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        }
        $post->update($validated);
        return back()->with('status', 'Article mis à jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        BlogPost::query()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.blog.index')->with('status', 'Article supprimé.');
    }
}
