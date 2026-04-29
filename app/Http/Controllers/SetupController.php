<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\InstallationState;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    public function __invoke(InstallationState $installationState)
    {
        if ($this->hasOnboardingTables()) {
            return view('setup.wizard');
        }

        return view('setup.index', [
            'isConfigured' => $installationState->isConfigured(),
            'checks' => [
                'companies' => Schema::hasTable('companies'),
                'settings' => Schema::hasTable('settings'),
                'services' => Schema::hasTable('services'),
                'cities' => Schema::hasTable('cities'),
            ],
            'envInfo' => [
                'app_url' => config('app.url'),
                'app_domain' => env('APP_DOMAIN'),
                'admin_domain' => env('APP_ADMIN_DOMAIN'),
                'db_database' => env('DB_DATABASE'),
                'mail_from' => env('MAIL_FROM_ADDRESS'),
                'queue' => env('QUEUE_CONNECTION'),
                'cache' => env('CACHE_STORE'),
            ],
        ]);
    }

    private function hasOnboardingTables(): bool
    {
        foreach (['companies', 'settings', 'services', 'cities'] as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }
}
