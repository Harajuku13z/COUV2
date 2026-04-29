<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyHoneypot
{
    public function handle(Request $request, Closure $next): Response
    {
        if (filled($request->input('website')) || filled($request->input('company_name'))) {
            abort(422, 'La verification anti-spam a echoue.');
        }

        return $next($request);
    }
}
