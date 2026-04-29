<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SerpResult extends BaseModel
{
    protected function casts(): array
    {
        return [
            'analyzed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function competitorResults(): HasMany
    {
        return $this->hasMany(CompetitorResult::class);
    }

    public function localPackResults(): HasMany
    {
        return $this->hasMany(LocalPackResult::class);
    }

    public function peopleAlsoAsk(): HasMany
    {
        return $this->hasMany(PeopleAlsoAsk::class);
    }

    public function relatedSearches(): HasMany
    {
        return $this->hasMany(RelatedSearch::class);
    }
}
