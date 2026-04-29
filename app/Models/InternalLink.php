<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalLink extends BaseModel
{
    public function fromPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'from_page_id');
    }

    public function toPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'to_page_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
