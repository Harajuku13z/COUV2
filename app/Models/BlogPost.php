<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use App\Traits\HasSeo;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends BaseModel
{
    use HasSchema;
    use HasSeo;
    use HasSlug;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
