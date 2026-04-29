<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorResult extends BaseModel
{
    protected function casts(): array
    {
        return [
            'google_rating' => 'decimal:1',
        ];
    }

    public function serpResult(): BelongsTo
    {
        return $this->belongsTo(SerpResult::class);
    }
}
