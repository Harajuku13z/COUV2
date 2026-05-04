<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\CentralAppUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalCentralDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonicalHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $currentHost = $request->getHost();

        if (! is_string($canonicalHost) || $canonicalHost === '' || $currentHost === $canonicalHost) {
            return $next($request);
        }

        $targetUrl = CentralAppUrl::app(ltrim($request->getRequestUri(), '/'));

        return redirect()->to($targetUrl, 308);
    }
}
