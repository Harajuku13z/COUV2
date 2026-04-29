<?php

declare(strict_types=1);

namespace App\Contracts;

interface OpenAiServiceInterface
{
    public function chat(array $messages, ?string $model = null, int $maxTokens = 1500, float $temperature = 0.7): string;

    public function generateJson(string $systemPrompt, string $userPrompt, int $maxRetries = 3): array;

    public function similarityScore(string $contentA, string $contentB): float;

    public function estimateCost(int $promptTokens, int $completionTokens, string $model): float;
}
