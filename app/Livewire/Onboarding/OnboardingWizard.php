<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding;

use App\Livewire\Onboarding\Steps\ApiKeysStep;
use App\Livewire\Onboarding\Steps\BrandingStep;
use App\Livewire\Onboarding\Steps\CompanyInfoStep;
use App\Livewire\Onboarding\Steps\LaunchStep;
use App\Livewire\Onboarding\Steps\ServicesStep;
use App\Livewire\Onboarding\Steps\ZonesStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class OnboardingWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            CompanyInfoStep::class,
            ZonesStep::class,
            ServicesStep::class,
            BrandingStep::class,
            ApiKeysStep::class,
            LaunchStep::class,
        ];
    }
}
