<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Jobs\GenerateAllPagesForDepartmentJob;
use App\Models\City;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use Spatie\LivewireWizard\Components\StepComponent;

class LaunchStep extends StepComponent
{
    public function launch()
    {
        $departmentCode = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');

        if ($departmentCode !== '') {
            GenerateAllPagesForDepartmentJob::dispatch($departmentCode);
        }

        return Redirect::route('admin.dashboard');
    }

    public function getEstimatedPagesProperty(): int
    {
        $departmentCode = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');
        $cities = City::query()->where('department_code', $departmentCode)->active()->count();
        $services = Service::query()->count();

        return $cities * max($services, 1) * 2;
    }

    public function getEstimatedCostProperty(): string
    {
        return '$'.number_format(($this->estimatedPages * 0.0035), 2);
    }

    public function render()
    {
        return view('livewire.onboarding.launch-step');
    }
}
