<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function (object $model): void {
            if (! method_exists($model, 'setAttribute')) {
                return;
            }

            $source = null;

            foreach (['name', 'title', 'question'] as $field) {
                if (filled($model->{$field} ?? null)) {
                    $source = (string) $model->{$field};
                    break;
                }
            }

            if ($source === null) {
                return;
            }

            $currentSlug = method_exists($model, 'getAttribute') ? $model->getAttribute('slug') : null;

            if (blank($currentSlug) || $model->isDirty('name') || $model->isDirty('title')) {
                $model->setAttribute('slug', Str::slug($source));
            }
        });
    }

    public function setSlugAttribute(?string $value): void
    {
        $this->attributes['slug'] = filled($value) ? Str::slug($value) : null;
    }
}
