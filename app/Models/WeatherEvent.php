<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherEvent extends BaseModel
{
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'used_for_content' => 'boolean',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereDate('event_date', '>=', now()->subDays(7));
    }
}
