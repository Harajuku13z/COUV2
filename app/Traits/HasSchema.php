<?php

declare(strict_types=1);

namespace App\Traits;

trait HasSchema
{
    public function schemaArray(): array
    {
        if (method_exists($this, 'toSchemaArray')) {
            return $this->toSchemaArray();
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Thing',
            'name' => $this->name ?? $this->title ?? null,
            'url' => method_exists($this, 'canonicalUrl') ? $this->canonicalUrl() : rtrim(config('app.url'), '/'),
        ];
    }

    public function schemaJson(): string
    {
        return (string) json_encode($this->schemaArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
