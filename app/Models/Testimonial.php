<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Testimonial extends BaseModel
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
