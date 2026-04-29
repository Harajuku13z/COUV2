<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Company;
use Illuminate\Support\Facades\Schema;
use Throwable;

class InstallationState
{
    public function isConfigured(): bool
    {
        try {
            if (! Schema::hasTable('companies')) {
                return false;
            }

            return Company::query()->exists();
        } catch (Throwable) {
            return false;
        }
    }
}
