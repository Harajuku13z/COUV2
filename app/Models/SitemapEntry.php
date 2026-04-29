<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class SitemapEntry extends BaseModel
{
    protected function casts(): array
    {
        return [
            'lastmod' => 'date',
            'priority' => 'decimal:1',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
