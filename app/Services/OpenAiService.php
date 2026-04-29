<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\OpenAiServiceInterface;
use App\Traits\TracksApiCost;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiService implements OpenAiServiceInterface
{
    use TracksApiCost;

    private array $lastUsage = [];

    public function chat(array $messages, ?string $model = null, int $maxTokens = 1500, float $temperature = 0.7): string
    {
        $model ??= (string) config('services.openai_local.default_model', 'gpt-4o-mini');
        $startedAt = microtime(true);

        try {
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

            $durationMs = (int) ((microtime(true) - $startedAt) * 1000);
            $this->lastUsage = [
                'prompt_tokens' => $response->usage->promptTokens ?? 0,
                'completion_tokens' => $response->usage->completionTokens ?? 0,
                'model' => $model,
                'cost_usd' => $this->estimateCost(
                    (int) ($response->usage->promptTokens ?? 0),
                    (int) ($response->usage->completionTokens ?? 0),
                    $model,
                ),
                'duration_ms' => $durationMs,
            ];

            $this->logApiCall('openai', 'chat.completions', ['model' => $model], ['usage' => $this->lastUsage], $durationMs);

            return trim((string) ($response->choices[0]->message->content ?? ''));
        } catch (\Throwable $exception) {
            $this->logApiCall('openai', 'chat.completions', ['model' => $model], null, (int) ((microtime(true) - $startedAt) * 1000), $exception);
            throw $exception;
        }
    }

    public function generateJson(string $systemPrompt, string $userPrompt, int $maxRetries = 3): array
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $content = $this->chat([
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ], maxTokens: 2200, temperature: 0.7);

                $decoded = json_decode($content, true);

                if (is_array($decoded)) {
                    return $decoded;
                }

                $lastException = new \RuntimeException('OpenAI returned invalid JSON.');
            } catch (\Throwable $exception) {
                $lastException = $exception;
            }
        }

        throw $lastException ?? new \RuntimeException('Unable to generate valid JSON response.');
    }

    public function similarityScore(string $contentA, string $contentB): float
    {
        $normalizedA = mb_strtolower(trim($contentA));
        $normalizedB = mb_strtolower(trim($contentB));

        if (mb_strlen($normalizedA) > 5000) {
            $normalizedA = mb_substr($normalizedA, 0, 2500);
        }

        if (mb_strlen($normalizedB) > 5000) {
            $normalizedB = mb_substr($normalizedB, 0, 2500);
        }

        if ($normalizedA === '' && $normalizedB === '') {
            return 1.0;
        }

        $maxLength = max(strlen($normalizedA), strlen($normalizedB));

        if ($maxLength === 0) {
            return 0.0;
        }

        return max(0.0, min(1.0, 1 - (levenshtein($normalizedA, $normalizedB) / $maxLength)));
    }

    public function estimateCost(int $promptTokens, int $completionTokens, string $model): float
    {
        $pricing = match ($model) {
            'gpt-4o' => ['input' => 5.00 / 1_000_000, 'output' => 15.00 / 1_000_000],
            default => ['input' => 0.15 / 1_000_000, 'output' => 0.60 / 1_000_000],
        };

        return round(($promptTokens * $pricing['input']) + ($completionTokens * $pricing['output']), 6);
    }

    public function getLastUsage(): array
    {
        return $this->lastUsage;
    }
}
