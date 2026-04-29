<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelatedSearch extends BaseModel
{
    public function serpResult(): BelongsTo
    {
        return $this->belongsTo(SerpResult::class);
    }
}
