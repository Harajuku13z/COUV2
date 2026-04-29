<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends BaseModel
{
    use HasSeo;
    use HasSlug;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'last_generated_at' => 'datetime',
            'similarity_score' => 'decimal:2',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function content(): HasOne
    {
        return $this->hasOne(PageContent::class);
    }

    public function seoKeywords(): HasMany
    {
        return $this->hasMany(SeoKeyword::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function aiGenerations(): HasMany
    {
        return $this->hasMany(AiGeneration::class);
    }

    public function outgoingInternalLinks(): HasMany
    {
        return $this->hasMany(InternalLink::class, 'from_page_id');
    }

    public function incomingInternalLinks(): HasMany
    {
        return $this->hasMany(InternalLink::class, 'to_page_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeByDepartment(Builder $query, string $departmentCode): Builder
    {
        return $query->whereHas('city', fn (Builder $cityQuery) => $cityQuery->where('department_code', $departmentCode));
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->whereHas('city', fn (Builder $cityQuery) => $cityQuery->where('seo_priority', '>=', 7));
    }
}
