<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGeneration extends BaseModel
{
    protected function casts(): array
    {
        return [
            'cost_usd' => 'decimal:6',
            'similarity_score' => 'decimal:2',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'success');
    }
}
