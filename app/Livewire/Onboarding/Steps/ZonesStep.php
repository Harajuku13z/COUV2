<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Jobs\ImportDepartmentCitiesJob;
use App\Models\City;
use App\Models\Setting;
use Spatie\LivewireWizard\Components\StepComponent;

class ZonesStep extends StepComponent
{
    public string $department_code = '';
    public string $priority_cities = '';
    public int $intervention_radius_km = 30;

    public function mount(): void
    {
        $this->department_code = (string) (Setting::query()->where('key', 'department_code')->value('value') ?? '');
        $this->priority_cities = (string) (Setting::query()->where('key', 'priority_cities')->value('value') ?? '');
        $this->intervention_radius_km = (int) (Setting::query()->where('key', 'intervention_radius_km')->value('value') ?? 30);
    }

    public function importAndContinue(): void
    {
        $validated = $this->validate([
            'department_code' => ['required', 'string', 'max:3'],
            'priority_cities' => ['nullable', 'string'],
            'intervention_radius_km' => ['required', 'integer', 'min:10', 'max:100'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => (string) $value, 'group' => 'zones']);
        }

        ImportDepartmentCitiesJob::dispatch($this->department_code);

        $this->nextStep();
    }

    public function getImportedCitiesCountProperty(): int
    {
        return $this->department_code === '' ? 0 : City::query()->where('department_code', $this->department_code)->count();
    }

    public function render()
    {
        return view('livewire.onboarding.zones-step');
    }
}
