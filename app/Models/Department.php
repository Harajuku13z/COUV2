<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends BaseModel
{
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'department_code', 'code');
    }

    public function scopeByDepartment(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }
}
