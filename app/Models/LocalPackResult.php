<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalPackResult extends BaseModel
{
    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'lat' => 'decimal:7',
            'lon' => 'decimal:7',
        ];
    }

    public function serpResult(): BelongsTo
    {
        return $this->belongsTo(SerpResult::class);
    }
}
