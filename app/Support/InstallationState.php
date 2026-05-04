<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class InstallationState
{
    public function isConfigured(): bool
    {
        try {
            if (! Schema::hasTable('settings')) {
                return false;
            }

            return Setting::query()->where('key', 'setup_completed')->value('value') === '1';
        } catch (Throwable) {
            return false;
        }
    }
}
