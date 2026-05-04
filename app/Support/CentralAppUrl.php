<?php

declare(strict_types=1);

namespace App\Support;

class CentralAppUrl
{
    public static function app(string $path = ''): string
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $normalizedPath = ltrim($path, '/');

        if ($normalizedPath === '') {
            return $baseUrl;
        }

        return "{$baseUrl}/{$normalizedPath}";
    }

    public static function admin(string $path = ''): string
    {
        $adminPath = 'admin';

        if ($path !== '') {
            $adminPath .= '/'.ltrim($path, '/');
        }

        return self::app($adminPath);
    }
}
