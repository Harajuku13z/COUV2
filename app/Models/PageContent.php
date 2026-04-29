<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSchema;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageContent extends BaseModel
{
    use HasSchema;
    use HasSeo;

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'faq' => 'array',
            'internal_links' => 'array',
            'schema_local_business' => 'array',
            'schema_service' => 'array',
            'schema_faq' => 'array',
            'readability_score' => 'decimal:1',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function toSchemaArray(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                $this->schema_local_business,
                $this->schema_service,
                $this->schema_faq,
            ])),
        ];
    }
}
