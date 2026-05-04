<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Realization;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\HomePageComposerService;
use App\Support\CentralAppUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __construct(private readonly HomePageComposerService $composer)
    {
    }

    public function edit(): View
    {
        $settings = $this->composer->settings();

        $stats = [
            'services' => Service::query()->whereHas('websiteServices', fn ($query) => $query->where('is_active', true))->count(),
            'realizations' => Realization::query()->count(),
            'testimonials' => Testimonial::query()->active()->count(),
            'departments' => count($this->departmentCodes()),
        ];

        $company = Company::query()->first();

        $templates = [
            'template-1' => ['name' => 'Atelier éditorial', 'description' => 'Grand hero, points de confiance et blocs contenus très lisibles.'],
            'template-2' => ['name' => 'Preuve terrain', 'description' => 'Accent sur les réalisations, la réactivité et les zones couvertes.'],
            'template-3' => ['name' => 'Conversion locale', 'description' => 'Parcours plus direct avec appels à l’action visibles et cartes d’arguments.'],
            'template-4' => ['name' => 'Sobriété premium', 'description' => 'Style plus posé, fort sur la confiance, les services et la présentation.'],
            'template-5' => ['name' => 'Portfolio artisan', 'description' => 'Mise en avant visuelle des chantiers, des services et des avis clients.'],
        ];

        return view('admin.homepage.edit', compact('settings', 'stats', 'company', 'templates'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_template' => ['required', 'in:'.implode(',', HomePageComposerService::TEMPLATE_KEYS)],
            'hero_kicker' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_description' => ['required', 'string'],
            'primary_cta' => ['required', 'string', 'max:80'],
            'secondary_cta' => ['nullable', 'string', 'max:80'],
            'services_title' => ['required', 'string', 'max:160'],
            'services_intro' => ['required', 'string'],
            'realizations_title' => ['required', 'string', 'max:160'],
            'realizations_intro' => ['required', 'string'],
            'testimonials_title' => ['required', 'string', 'max:160'],
            'blog_title' => ['required', 'string', 'max:160'],
            'trust_items_input' => ['nullable', 'string'],
            'highlight_items_input' => ['nullable', 'string'],
        ]);

        $this->composer->save([
            'selected_template' => $validated['selected_template'],
            'hero_kicker' => $validated['hero_kicker'] ?? '',
            'hero_title' => $validated['hero_title'],
            'hero_description' => $validated['hero_description'],
            'primary_cta' => $validated['primary_cta'],
            'secondary_cta' => $validated['secondary_cta'] ?? '',
            'services_title' => $validated['services_title'],
            'services_intro' => $validated['services_intro'],
            'realizations_title' => $validated['realizations_title'],
            'realizations_intro' => $validated['realizations_intro'],
            'testimonials_title' => $validated['testimonials_title'],
            'blog_title' => $validated['blog_title'],
            'trust_items' => $this->linesToArray($validated['trust_items_input'] ?? ''),
            'highlight_items' => $this->linesToArray($validated['highlight_items_input'] ?? ''),
        ]);

        return redirect()->to(CentralAppUrl::admin('homepage'))->with('status', 'Configuration de la page d’accueil enregistrée.');
    }

    public function generateAi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_template' => ['required', 'in:'.implode(',', HomePageComposerService::TEMPLATE_KEYS)],
        ]);

        $draft = $this->composer->generateAiDraft($validated['selected_template']);
        $this->composer->save($draft);

        return redirect()->to(CentralAppUrl::admin('homepage'))->with('status', 'Le contenu de la page d’accueil a été prérempli avec l’IA.');
    }

    private function linesToArray(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    private function departmentCodes(): array
    {
        $rawCodes = Setting::query()->where('key', 'department_codes')->value('value');

        if (! is_string($rawCodes) || trim($rawCodes) === '') {
            return [];
        }

        $decoded = json_decode($rawCodes, true);

        return collect(is_array($decoded) ? $decoded : [])
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }
}
