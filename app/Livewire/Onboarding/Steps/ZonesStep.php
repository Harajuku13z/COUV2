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
    public string $selected_department_option = '';
    public array $available_departments = [];
    public array $department_rows = [''];
    public array $selected_departments = [];
    public string $priority_cities = '';
    public int $intervention_radius_km = 0;

    public function mount(): void
    {
        $storedDepartmentCodes = $this->getStoredDepartmentCodes();
        $knownDepartments = Department::query()
            ->whereIn('code', $storedDepartmentCodes)
            ->get(['code', 'name'])
            ->keyBy('code');

        $this->department_rows = $storedDepartmentCodes !== [] ? array_values($storedDepartmentCodes) : [''];
        $this->syncSelectedDepartmentsFromRows($knownDepartments->map(fn ($department) => $department->name)->all());
        $this->department_code = (string) ($storedDepartmentCodes[0] ?? '');
        $this->priority_cities = (string) (Setting::query()->where('key', 'priority_cities')->value('value') ?? '');
        $this->available_departments = $this->loadDepartmentOptions();
    }

    public function importAndContinue(): void
    {
        $validated = $this->validate([
            'department_rows' => ['required', 'array', 'min:1'],
            'department_rows.*' => ['required', 'string', 'max:3'],
            'priority_cities' => ['nullable', 'string'],
        ]);

        $departmentCodes = collect($validated['department_rows'])
            ->map(fn (string $code): string => trim($code))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($departmentCodes === []) {
            $this->addError('department_rows', 'Choisis au moins un departement.');

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
        Setting::query()->updateOrCreate(['key' => 'priority_cities'], ['value' => $validated['priority_cities'] ?? '', 'group' => 'zones']);
        Setting::query()->where('key', 'intervention_radius_km')->delete();

        foreach ($departmentCodes as $departmentCode) {
            ImportDepartmentCitiesJob::dispatch($departmentCode);
        }

        $this->nextStep();
    }

    public function getImportedCitiesCountProperty(): int
    {
        $departmentCodes = collect($this->department_rows)->filter()->unique()->values()->all();

        if ($departmentCodes === []) {
            return 0;
        }

        return City::query()->whereIn('department_code', $departmentCodes)->count();
    }

    public function addDepartmentRow(): void
    {
        $this->department_rows[] = '';
    }

    public function addSelectedDepartment(): void
    {
        $code = trim($this->selected_department_option);

        if ($code === '') {
            return;
        }

        if (in_array($code, $this->department_rows, true)) {
            $this->selected_department_option = '';

            return;
        }

        $emptyIndex = collect($this->department_rows)->search(fn (mixed $value): bool => trim((string) $value) === '');

        if ($emptyIndex !== false) {
            $this->department_rows[$emptyIndex] = $code;
        } else {
            $this->department_rows[] = $code;
        }

        $this->selected_department_option = '';
        $this->syncSelectedDepartmentsFromRows();
    }

    public function removeDepartmentRow(int $index): void
    {
        if (count($this->department_rows) <= 1) {
            $this->department_rows = [''];
        } else {
            unset($this->department_rows[$index]);
            $this->department_rows = array_values($this->department_rows);
        }

        $this->syncSelectedDepartmentsFromRows();
    }

    public function removeDepartment(string $code): void
    {
        $index = collect($this->department_rows)->search(fn (mixed $value): bool => trim((string) $value) === trim($code));

        if ($index === false) {
            return;
        }

        $this->removeDepartmentRow((int) $index);
    }

    public function updatedDepartmentRows(): void
    {
        $this->department_rows = array_values($this->department_rows);
        $this->syncSelectedDepartmentsFromRows();
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

    private function syncSelectedDepartmentsFromRows(?array $knownNames = null): void
    {
        $knownNames ??= collect($this->available_departments)->pluck('name', 'code')->all();

        $this->selected_departments = collect($this->department_rows)
            ->map(fn (mixed $code): string => trim((string) $code))
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $code): array => [
                'code' => $code,
                'name' => (string) ($knownNames[$code] ?? $code),
            ])
            ->all();

        $this->department_code = (string) ($this->selected_departments[0]['code'] ?? '');
    }

    public function render()
    {
        return view('livewire.onboarding.zones-step');
    }
}
