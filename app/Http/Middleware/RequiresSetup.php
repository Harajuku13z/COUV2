<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequiresSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! Schema::hasTable('settings')) {
                return redirect()->route('onboarding');
            }

            $completed = Setting::query()->where('key', 'setup_completed')->value('value');

            if ($completed !== '1') {
                return redirect()->route('onboarding');
            }
        } catch (Throwable) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
