<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLeadForm
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = sprintf('lead-form:%s', $request->ip() ?? 'unknown');

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Trop de soumissions ont ete detectees. Merci de reessayer plus tard.');
        }

        RateLimiter::hit($key, 3600);

        return $next($request);
    }
}
