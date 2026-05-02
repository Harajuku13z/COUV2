<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Contracts\GeoGouvServiceInterface;
use App\Jobs\ImportDepartmentCitiesJob;
use App\Models\City;
use App\Models\Department;
use App\Models\Setting;
use Spatie\LivewireWizard\Components\StepComponent;

class ZonesStep extends StepComponent
{
    public string $department_code = '';
    public string $department_codes_payload = '[]';
    public array $available_departments = [];
    public array $selected_departments = [];
    public int $intervention_radius_km = 0;

    public function mount(): void
    {
        $storedDepartmentCodes = $this->getStoredDepartmentCodes();
        $knownDepartments = Department::query()
            ->whereIn('code', $storedDepartmentCodes)
            ->get(['code', 'name'])
            ->keyBy('code');

        $this->selected_departments = collect($storedDepartmentCodes)
            ->map(fn (string $code): array => [
                'code' => $code,
                'name' => (string) ($knownDepartments->get($code)?->name ?? $code),
            ])
            ->values()
            ->all();

        $this->department_codes_payload = json_encode($storedDepartmentCodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
        $this->department_code = (string) ($storedDepartmentCodes[0] ?? '');
        $this->available_departments = $this->loadDepartmentOptions();
    }

    public function importAndContinue(): void
    {
        $this->validate([
            'department_codes_payload' => ['required', 'string'],
        ]);

        $decoded = json_decode($this->department_codes_payload, true);
        $departmentCodes = collect(is_array($decoded) ? $decoded : [])
            ->map(fn (mixed $code): string => trim((string) $code))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($departmentCodes === []) {
            $this->addError('department_codes_payload', 'Choisis au moins un departement.');

            return;
        }

        Setting::query()->updateOrCreate(
            ['key' => 'department_code'],
            ['value' => (string) ($departmentCodes[0] ?? ''), 'group' => 'zones']
        );
        Setting::query()->updateOrCreate(
            ['key' => 'department_codes'],
            ['value' => json_encode($departmentCodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'group' => 'zones']
        );
        Setting::query()->where('key', 'priority_cities')->delete();
        Setting::query()->where('key', 'intervention_radius_km')->delete();

        foreach ($departmentCodes as $departmentCode) {
            ImportDepartmentCitiesJob::dispatch($departmentCode);
        }

        $this->nextStep();
    }

    public function getImportedCitiesCountProperty(): int
    {
        $decoded = json_decode($this->department_codes_payload, true);
        $departmentCodes = collect(is_array($decoded) ? $decoded : [])->filter()->unique()->values()->all();

        if ($departmentCodes === []) {
            return 0;
        }

        return City::query()->whereIn('department_code', $departmentCodes)->count();
    }

    public function addDepartmentRow(): void
    {
        // Compatibility no-op for older browser state.
    }

    public function addSelectedDepartment(): void
    {
        // Compatibility no-op for older browser state.
    }

    public function removeDepartmentRow(int $index): void
    {
        // Compatibility no-op for older browser state.
    }

    public function removeDepartment(string $code): void
    {
        // Compatibility no-op for older browser state.
    }

    private function getStoredDepartmentCodes(): array
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

    private function loadDepartmentOptions(): array
    {
        /** @var GeoGouvServiceInterface $geoGouvService */
        $geoGouvService = app(GeoGouvServiceInterface::class);

        return $geoGouvService
            ->searchDepartments(null, 120)
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.onboarding.zones-step');
    }
}
