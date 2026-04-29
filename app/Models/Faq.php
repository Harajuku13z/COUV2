<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends BaseModel
{
    use HasSchema;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function toSchemaArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [[
                '@type' => 'Question',
                'name' => $this->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $this->answer,
                ],
            ]],
        ];
    }
}
