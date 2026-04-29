<?php

declare(strict_types=1);

namespace App\Models;

class ApiErrorLog extends BaseModel
{
    protected $connection = 'central';

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'context' => 'array',
            'occurred_at' => 'datetime',
        ];
    }
}
