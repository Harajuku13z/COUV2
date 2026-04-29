<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends BaseModel
{
    use HasSchema;
    use HasSeo;
    use HasSlug;

    protected function casts(): array
    {
        return [
            'nearby_cities' => 'array',
            'weather_data' => 'array',
            'is_active' => 'boolean',
            'weather_updated_at' => 'datetime',
            'lat' => 'decimal:7',
            'lon' => 'decimal:7',
            'surface' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_code', 'code');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function weatherEvents(): HasMany
    {
        return $this->hasMany(WeatherEvent::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartment(Builder $query, string $code): Builder
    {
        return $query->where('department_code', $code);
    }

    public function scopeHighPriority(Builder $query, int $threshold = 7): Builder
    {
        return $query->where('seo_priority', '>=', $threshold);
    }

    public function fullAddress(): string
    {
        return collect([$this->postal_code, $this->name, $this->department_code])->filter()->implode(' ');
    }
}
