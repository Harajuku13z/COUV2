<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()->orderByDesc('created_at')->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'author_name'   => ['required', 'string', 'max:100'],
            'author_city'   => ['nullable', 'string', 'max:100'],
            'service_label' => ['nullable', 'string', 'max:150'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'content'       => ['required', 'string', 'max:1000'],
            'source'        => ['required', 'string', 'in:manual,google'],
        ]);
        Testimonial::query()->create(
            $request->only(['author_name', 'author_city', 'service_label', 'rating', 'content', 'source'])
            + ['is_visible' => true]
        );
        return redirect()->route('admin.testimonials.index')->with('status', 'Témoignage ajouté.');
    }

    public function edit(int $id): View
    {
        $testimonial = Testimonial::query()->findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'author_name'   => ['required', 'string', 'max:100'],
            'author_city'   => ['nullable', 'string', 'max:100'],
            'service_label' => ['nullable', 'string', 'max:150'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'content'       => ['required', 'string', 'max:1000'],
        ]);
        Testimonial::query()->findOrFail($id)->update(
            $request->only(['author_name', 'author_city', 'service_label', 'rating', 'content'])
        );
        return back()->with('status', 'Témoignage mis à jour.');
    }

    public function toggleVisible(int $id): RedirectResponse
    {
        $t = Testimonial::query()->findOrFail($id);
        $t->update(['is_visible' => ! $t->is_visible]);
        return back()->with('status', 'Visibilité modifiée.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Testimonial::query()->findOrFail($id)->delete();
        return redirect()->route('admin.testimonials.index')->with('status', 'Témoignage supprimé.');
    }
}
