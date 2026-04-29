<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use HasSchema;
    use HasSeo;
    use HasSlug;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'certifications' => 'array',
            'opening_hours' => 'array',
            'emergency_available' => 'boolean',
            'lat' => 'decimal:7',
            'lon' => 'decimal:7',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function fullAddress(): string
    {
        return collect([$this->address, $this->postal_code, $this->city])->filter()->implode(', ');
    }

    public function toSchemaArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $this->name,
            'telephone' => $this->phone,
            'email' => $this->email,
            'url' => $this->canonicalUrl(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->address,
                'addressLocality' => $this->city,
                'postalCode' => $this->postal_code,
                'addressCountry' => 'FR',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->lat,
                'longitude' => $this->lon,
            ],
        ];
    }
}
