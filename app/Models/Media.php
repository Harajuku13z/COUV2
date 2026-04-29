<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends BaseModel
{
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
