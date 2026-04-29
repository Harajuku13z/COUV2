<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeopleAlsoAsk extends BaseModel
{
    use HasSchema;

    public function serpResult(): BelongsTo
    {
        return $this->belongsTo(SerpResult::class);
    }

    public function toSchemaArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Question',
            'name' => $this->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $this->answer_snippet,
            ],
        ];
    }
}
