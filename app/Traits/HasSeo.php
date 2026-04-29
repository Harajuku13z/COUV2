<?php

declare(strict_types=1);

namespace App\Traits;

trait HasSeo
{
    public function canonicalUrl(): string
    {
        $slug = $this->slug ?? null;

        return $slug
            ? rtrim(config('app.url'), '/').'/'.$slug
            : rtrim(config('app.url'), '/');
    }

    public function openGraphData(): array
    {
        return [
            'title' => $this->meta_title ?? $this->title ?? $this->name ?? config('app.name'),
            'description' => $this->meta_description ?? $this->excerpt ?? $this->description ?? null,
            'url' => $this->canonicalUrl(),
            'type' => property_exists($this, 'page_type') ? 'article' : 'website',
            'image' => $this->featured_image ?? $this->logo_path ?? null,
        ];
    }

    public function twitterCardData(): array
    {
        return [
            'card' => 'summary_large_image',
            'title' => $this->meta_title ?? $this->title ?? $this->name ?? config('app.name'),
            'description' => $this->meta_description ?? $this->excerpt ?? $this->description ?? null,
            'image' => $this->featured_image ?? $this->logo_path ?? null,
        ];
    }
}
