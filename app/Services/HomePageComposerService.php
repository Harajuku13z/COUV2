<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\OpenAiServiceInterface;
use App\Models\Company;
use App\Models\Realization;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;

class HomePageComposerService
{
    public const TEMPLATE_KEYS = ['template-1', 'template-2', 'template-3', 'template-4', 'template-5'];

    public function __construct(private readonly OpenAiServiceInterface $openAi)
    {
    }

    public function settings(): array
    {
        $settings = Setting::query()->where('group', 'homepage')->pluck('value', 'key')->all();
        $settings['trust_items'] = $this->decodeArray($settings['trust_items'] ?? null);
        $settings['highlight_items'] = $this->decodeArray($settings['highlight_items'] ?? null);
        $settings['selected_template'] = in_array(($settings['selected_template'] ?? ''), self::TEMPLATE_KEYS, true)
            ? $settings['selected_template']
            : 'template-1';

        return $settings;
    }

    public function resolvedSettings(): array
    {
        $settings = $this->settings();
        $company = Company::query()->first();

        if (! $company) {
            return $settings;
        }

        $services = Service::query()->whereHas('websiteServices', fn ($query) => $query->where('is_active', true))->take(3)->get();
        $fallback = $this->fallbackDraft($company, $services->pluck('name')->all(), (string) ($settings['selected_template'] ?? 'template-1'));

        return [
            'selected_template' => $settings['selected_template'] ?? $fallback['selected_template'],
            'hero_kicker' => $settings['hero_kicker'] ?? $fallback['hero_kicker'],
            'hero_title' => $settings['hero_title'] ?? $fallback['hero_title'],
            'hero_description' => $settings['hero_description'] ?? $fallback['hero_description'],
            'primary_cta' => $settings['primary_cta'] ?? $fallback['primary_cta'],
            'secondary_cta' => $settings['secondary_cta'] ?? $fallback['secondary_cta'],
            'services_title' => $settings['services_title'] ?? $fallback['services_title'],
            'services_intro' => $settings['services_intro'] ?? $fallback['services_intro'],
            'realizations_title' => $settings['realizations_title'] ?? $fallback['realizations_title'],
            'realizations_intro' => $settings['realizations_intro'] ?? $fallback['realizations_intro'],
            'testimonials_title' => $settings['testimonials_title'] ?? $fallback['testimonials_title'],
            'blog_title' => $settings['blog_title'] ?? $fallback['blog_title'],
            'trust_items' => $settings['trust_items'] !== [] ? $settings['trust_items'] : $fallback['trust_items'],
            'highlight_items' => $settings['highlight_items'] !== [] ? $settings['highlight_items'] : $fallback['highlight_items'],
        ];
    }

    public function save(array $payload): void
    {
        foreach ($payload as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group' => 'homepage',
                    'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string) $value,
                ]
            );
        }
    }

    public function generateAiDraft(string $selectedTemplate): array
    {
        $company = Company::query()->firstOrFail();
        $services = Service::query()->whereHas('websiteServices', fn ($query) => $query->where('is_active', true))->take(6)->get();
        $testimonials = Testimonial::query()->active()->latest()->take(3)->get();
        $realizations = Realization::query()->featured()->latest()->take(3)->get();
        $departmentCodes = $this->decodeArray(Setting::query()->where('key', 'department_codes')->value('value'));

        $fallback = $this->fallbackDraft($company, $services->pluck('name')->all(), $selectedTemplate);

        try {
            $systemPrompt = <<<'PROMPT'
You are a French conversion copywriter for artisan websites.
Return valid JSON only.
Keep the tone clear, local, trustworthy and sales-oriented.
PROMPT;

            $userPrompt = json_encode([
                'goal' => 'Create a homepage content configuration for an artisan website admin.',
                'selected_template' => $selectedTemplate,
                'required_keys' => [
                    'hero_kicker',
                    'hero_title',
                    'hero_description',
                    'primary_cta',
                    'secondary_cta',
                    'services_title',
                    'services_intro',
                    'realizations_title',
                    'realizations_intro',
                    'testimonials_title',
                    'blog_title',
                    'trust_items',
                    'highlight_items',
                ],
                'format_rules' => [
                    'trust_items' => 'array of 3 short trust points',
                    'highlight_items' => 'array of 3 short highlight points',
                ],
                'company' => [
                    'name' => $company->name,
                    'activity_type' => $company->activity_type,
                    'city' => $company->city,
                    'offer_text' => $company->offer_text,
                    'certifications' => $company->certifications,
                    'emergency_available' => $company->emergency_available,
                ],
                'services' => $services->map(fn (Service $service): array => [
                    'name' => $service->name,
                    'description' => $service->description,
                ])->all(),
                'departments' => $departmentCodes,
                'testimonials' => $testimonials->pluck('content')->all(),
                'realizations' => $realizations->pluck('title')->all(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            $generated = $this->openAi->generateJson($systemPrompt, $userPrompt ?: '{}');

            return [
                'selected_template' => $selectedTemplate,
                'hero_kicker' => $generated['hero_kicker'] ?? $fallback['hero_kicker'],
                'hero_title' => $generated['hero_title'] ?? $fallback['hero_title'],
                'hero_description' => $generated['hero_description'] ?? $fallback['hero_description'],
                'primary_cta' => $generated['primary_cta'] ?? $fallback['primary_cta'],
                'secondary_cta' => $generated['secondary_cta'] ?? $fallback['secondary_cta'],
                'services_title' => $generated['services_title'] ?? $fallback['services_title'],
                'services_intro' => $generated['services_intro'] ?? $fallback['services_intro'],
                'realizations_title' => $generated['realizations_title'] ?? $fallback['realizations_title'],
                'realizations_intro' => $generated['realizations_intro'] ?? $fallback['realizations_intro'],
                'testimonials_title' => $generated['testimonials_title'] ?? $fallback['testimonials_title'],
                'blog_title' => $generated['blog_title'] ?? $fallback['blog_title'],
                'trust_items' => is_array($generated['trust_items'] ?? null) ? array_values($generated['trust_items']) : $fallback['trust_items'],
                'highlight_items' => is_array($generated['highlight_items'] ?? null) ? array_values($generated['highlight_items']) : $fallback['highlight_items'],
            ];
        } catch (\Throwable) {
            return $fallback;
        }
    }

    private function fallbackDraft(Company $company, array $serviceNames, string $selectedTemplate): array
    {
        $activity = $company->activity_type ? ucfirst(str_replace('_', ' ', $company->activity_type)) : 'Artisan';

        return [
            'selected_template' => $selectedTemplate,
            'hero_kicker' => "{$activity} local",
            'hero_title' => "{$company->name}, votre partenaire de confiance à {$company->city}",
            'hero_description' => $company->offer_text ?: "Interventions locales, accompagnement clair et devis précis pour vos besoins en {$activity}.",
            'primary_cta' => 'Demander un devis',
            'secondary_cta' => 'Voir nos réalisations',
            'services_title' => 'Des services pensés pour vos besoins du quotidien',
            'services_intro' => 'Une sélection de prestations réellement proposées par notre entreprise, avec une approche locale et réactive.',
            'realizations_title' => 'Des chantiers concrets, visibles et documentés',
            'realizations_intro' => 'Découvrez des réalisations récentes pour mieux comprendre notre méthode et notre niveau de finition.',
            'testimonials_title' => 'Des clients satisfaits sur le terrain',
            'blog_title' => 'Conseils, méthodes et réponses utiles',
            'trust_items' => [
                "Basé à {$company->city}",
                $company->emergency_available ? 'Intervention rapide disponible' : 'Accompagnement sur mesure',
                ! empty($company->certifications) ? 'Certifications et garanties mises en avant' : 'Devis clair et personnalisé',
            ],
            'highlight_items' => array_values(array_filter([
                $serviceNames[0] ?? null,
                $serviceNames[1] ?? null,
                $serviceNames[2] ?? null,
            ])),
        ];
    }

    private function decodeArray(mixed $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? array_values($decoded) : [];
    }
}
