<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AiContentGeneratorServiceInterface;
use App\Contracts\OpenAiServiceInterface;
use App\Models\AiGeneration;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\PeopleAlsoAsk;
use App\Models\LocalPackResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AiContentGeneratorService implements AiContentGeneratorServiceInterface
{
    public const TEMPLATE_EXPERTISE = 'expertise';
    public const TEMPLATE_PROBLEMATIQUE = 'problematique';
    public const TEMPLATE_CAS_CLIENT = 'cas_client';
    public const TEMPLATE_REGLEMENTATION = 'reglementation';

    public function __construct(
        private readonly OpenAiServiceInterface $openAi,
        private readonly WeatherService $weatherService,
    ) {
    }

    public function generatePage(Page $page, Company $company): array
    {
        $page->loadMissing(['city', 'service', 'content']);

        if ($page->city === null || $page->service === null) {
            throw new \RuntimeException('Page must have an associated city and service.');
        }

        $templates = [
            self::TEMPLATE_EXPERTISE,
            self::TEMPLATE_PROBLEMATIQUE,
            self::TEMPLATE_CAS_CLIENT,
            self::TEMPLATE_REGLEMENTATION,
        ];

        $hash = crc32($page->service->slug.$page->city->code_insee);
        $template = $templates[$hash % count($templates)];

        $attempt = 0;
        $generated = null;
        $maxSimilarity = 1.0;

        while ($attempt < 3 && $maxSimilarity > 0.70) {
            $attempt++;
            $systemPrompt = $this->buildSystemPrompt($template);
            $userPrompt = $this->buildUserPrompt($page, $company, $template, $attempt);
            $generated = $this->openAi->generateJson($systemPrompt, $userPrompt);

            if (! $this->validateJsonOutput($generated)) {
                throw new \RuntimeException('Generated payload is missing required keys.');
            }

            $newContent = $generated['intro']."\n".collect($generated['sections'])->pluck('content')->implode("\n");
            $maxSimilarity = $this->computeSimilarity($newContent, (int) $page->service_id);
        }

        if (! is_array($generated)) {
            throw new \RuntimeException('Failed to generate page content.');
        }

        $page->update([
            'slug' => $generated['slug'] ?? $page->slug,
            'similarity_score' => round($maxSimilarity, 2),
            'last_generated_at' => now(),
            'published_at' => $page->published_at ?? now(),
        ]);

        $pageContent = PageContent::query()->updateOrCreate(
            ['page_id' => $page->id],
            [
                'meta_title' => $generated['meta_title'],
                'meta_description' => $generated['meta_description'],
                'h1' => $generated['h1'],
                'intro' => $generated['intro'],
                'sections' => $generated['sections'],
                'faq' => $generated['faq'],
                'cta_primary' => $generated['cta_primary'],
                'cta_secondary' => $generated['cta_secondary'] ?? 'Demander un devis',
                'short_excerpt' => $generated['short_excerpt'] ?? Str::limit(strip_tags($generated['intro']), 180),
                'internal_links' => [],
                'schema_local_business' => $generated['schema_local_business'] ?? $company->toSchemaArray(),
                'schema_service' => $generated['schema_service'] ?? $page->service->toSchemaArray(),
                'schema_faq' => $generated['schema_faq'] ?? [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $generated['faq'],
                ],
                'word_count' => str_word_count(strip_tags($generated['intro'].' '.collect($generated['sections'])->pluck('content')->implode(' '))),
                'readability_score' => $generated['readability_score'] ?? 0,
            ]
        );

        $usage = method_exists($this->openAi, 'getLastUsage') ? $this->openAi->getLastUsage() : [];

        AiGeneration::query()->create([
            'page_id' => $page->id,
            'model' => (string) ($usage['model'] ?? config('services.openai_local.default_model')),
            'prompt_system' => $systemPrompt,
            'prompt_user' => $userPrompt,
            'raw_response' => json_encode($generated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'template_used' => $template,
            'prompt_tokens' => $usage['prompt_tokens'] ?? null,
            'completion_tokens' => $usage['completion_tokens'] ?? null,
            'cost_usd' => $usage['cost_usd'] ?? null,
            'similarity_score' => $maxSimilarity,
            'status' => $maxSimilarity > 0.70 ? 'too_similar' : 'success',
        ]);

        return $pageContent->fresh()?->toArray() ?? [];
    }

    public function generateBlogPost(string $topic, string $service, Company $company): array
    {
        $systemPrompt = $this->buildSystemPrompt(self::TEMPLATE_EXPERTISE);
        $userPrompt = <<<PROMPT
        Generate a French blog post as valid JSON for a local artisan website.
        Topic: {$topic}
        Service: {$service}
        Company: {$company->name}
        Return keys: title, slug, meta_title, meta_description, intro, sections, conclusion, faq, excerpt.
        PROMPT;

        $generated = $this->openAi->generateJson($systemPrompt, $userPrompt);

        BlogPost::query()->create([
            'title' => $generated['title'] ?? $topic,
            'slug' => $generated['slug'] ?? Str::slug($topic),
            'excerpt' => $generated['excerpt'] ?? null,
            'content' => collect($generated['sections'] ?? [])->map(fn (array $section): string => ($section['title'] ?? '')."\n".($section['content'] ?? ''))->implode("\n\n"),
            'meta_title' => $generated['meta_title'] ?? Str::limit($topic, 70, ''),
            'meta_description' => $generated['meta_description'] ?? Str::limit($topic, 160),
            'category' => $service,
            'tags' => [$service, $company->activity_type],
            'status' => 'draft',
        ]);

        return $generated;
    }

    private function buildSystemPrompt(string $template): string
    {
        return <<<PROMPT
        You are a senior French SEO copywriter for local artisan websites.
        Output valid JSON only.
        Anti-duplicate rules:
        - vary structure and examples
        - include local data, weather, competition and city context
        - keep each page specific and commercially useful
        Selected template: {$template}
        PROMPT;
    }

    private function buildUserPrompt(Page $page, Company $company, string $template, int $seed): string
    {
        $page->loadMissing(['city', 'service']);

        $paa = PeopleAlsoAsk::query()
            ->whereHas('serpResult', fn ($query) => $query
                ->where('city_name', $page->city?->name)
                ->where('service_name', $page->service?->name))
            ->latest()
            ->take(5)
            ->get(['question', 'answer_snippet'])
            ->toArray();

        $competitors = LocalPackResult::query()
            ->whereHas('serpResult', fn ($query) => $query
                ->where('city_name', $page->city?->name)
                ->where('service_name', $page->service?->name))
            ->latest()
            ->take(5)
            ->get(['name', 'rating', 'review_count', 'category'])
            ->toArray();

        $nearbyCities = $page->city?->nearby_cities ?? [];
        $weatherContext = $page->city !== null ? $this->weatherService->getWeatherContext($page->city) : '';

        return json_encode([
            'seed' => $seed,
            'template' => $template,
            'page' => [
                'type' => $page->page_type,
                'slug' => $page->slug,
            ],
            'company' => [
                'name' => $company->name,
                'activity' => $company->activity_main,
                'tone' => $company->tone,
                'certifications' => $company->certifications,
                'offer_text' => $company->offer_text,
                'phone' => $company->phone,
                'city' => $company->city,
            ],
            'service' => [
                'name' => $page->service?->name,
                'slug' => $page->service?->slug,
                'category' => $page->service?->category,
            ],
            'city' => [
                'name' => $page->city?->name,
                'postal_code' => $page->city?->postal_code,
                'population' => $page->city?->population,
                'department_code' => $page->city?->department_code,
                'nearby_cities' => $nearbyCities,
                'weather_context' => $weatherContext,
            ],
            'people_also_ask' => $paa,
            'competitors' => $competitors,
            'required_keys' => [
                'meta_title',
                'meta_description',
                'slug',
                'h1',
                'intro',
                'sections',
                'faq',
                'cta_primary',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: '';
    }

    private function validateJsonOutput(array $data): bool
    {
        $required = ['meta_title', 'meta_description', 'slug', 'h1', 'intro', 'sections', 'faq', 'cta_primary'];

        foreach ($required as $key) {
            if (! array_key_exists($key, $data)) {
                return false;
            }
        }

        return is_array($data['sections']) && count($data['sections']) >= 4
            && is_array($data['faq']) && count($data['faq']) >= 3;
    }

    private function computeSimilarity(string $newContent, int $serviceId): float
    {
        $contents = PageContent::query()
            ->whereHas('page', fn ($query) => $query->where('service_id', $serviceId))
            ->latest()
            ->take(5)
            ->get();

        if ($contents->isEmpty()) {
            return 0.0;
        }

        return $contents->map(function (PageContent $content) use ($newContent): float {
            $existing = $content->intro.' '.collect($content->sections)->pluck('content')->implode(' ');
            return $this->openAi->similarityScore($newContent, $existing);
        })->max() ?? 0.0;
    }
}
