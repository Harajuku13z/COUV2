<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends BaseModel
{
    use HasSchema;
    use HasSeo;
    use HasSlug;

    protected function casts(): array
    {
        return [
            'is_emergency' => 'boolean',
            'seasonal_triggers' => 'array',
        ];
    }

    public function websiteServices(): HasMany
    {
        return $this->hasMany(WebsiteService::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query;
    }

    public function toSchemaArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->canonicalUrl(),
        ];
    }
}
