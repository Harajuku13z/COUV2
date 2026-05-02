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
        foreach ($this->getDepartmentCodes() as $departmentCode) {
            GenerateAllPagesForDepartmentJob::dispatch($departmentCode);
        }

        return Redirect::route('admin.dashboard');
    }

    public function getEstimatedPagesProperty(): int
    {
        $departmentCodes = $this->getDepartmentCodes();
        $cities = $departmentCodes === []
            ? 0
            : City::query()->whereIn('department_code', $departmentCodes)->active()->count();
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

    private function getDepartmentCodes(): array
    {
        $rawCodes = Setting::query()->where('key', 'department_codes')->value('value');
        $decodedCodes = is_string($rawCodes) ? json_decode($rawCodes, true) : null;

        if (is_array($decodedCodes) && $decodedCodes !== []) {
            return collect($decodedCodes)
                ->map(fn (mixed $code): string => trim((string) $code))
                ->filter()
                ->values()
                ->all();
        }

        $legacyCode = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');

        return $legacyCode === '' ? [] : [$legacyCode];
    }
}
