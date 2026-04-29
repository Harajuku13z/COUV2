<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CachePublicPages
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET')
            || $request->query('preview') === '1'
            || auth()->check()
            || $request->expectsJson()
            || $request->is('livewire/*')
            || $request->is('horizon/*')
            || $request->is('storage/*')
            || $request->routeIs('tenant.health')) {
            return $next($request);
        }

        $ttl = $request->path() === '/' ? now()->addHour() : now()->addDay();
        $key = sprintf('page:%s:%s', $request->url(), $request->getQueryString());

        $cached = Cache::get($key);

        if (is_array($cached)) {
            return response($cached['content'], $cached['status'], $cached['headers']);
        }

        /** @var Response $response */
        $response = $next($request);

        if ($response->getStatusCode() === 200 && $response instanceof HttpResponse) {
            Cache::put($key, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => collect($response->headers->allPreserveCaseWithoutCookies())
                    ->map(fn (array $values) => implode(', ', $values))
                    ->all(),
            ], $ttl);
        }

        return $response;
    }
}
