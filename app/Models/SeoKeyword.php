<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoKeyword extends BaseModel
{
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
