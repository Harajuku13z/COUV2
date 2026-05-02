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
    public string $department_search = '';
    public array $selected_departments = [];
    public array $department_suggestions = [];
    public string $priority_cities = '';
    public int $intervention_radius_km = 30;

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

        $this->priority_cities = (string) (Setting::query()->where('key', 'priority_cities')->value('value') ?? '');
        $this->intervention_radius_km = (int) (Setting::query()->where('key', 'intervention_radius_km')->value('value') ?? 30);
        $this->department_suggestions = $this->searchDepartmentOptions();
    }

    public function importAndContinue(): void
    {
        $validated = $this->validate([
            'selected_departments' => ['required', 'array', 'min:1'],
            'selected_departments.*.code' => ['required', 'string', 'max:3'],
            'selected_departments.*.name' => ['required', 'string', 'max:255'],
            'priority_cities' => ['nullable', 'string'],
            'intervention_radius_km' => ['required', 'integer', 'min:10', 'max:100'],
        ]);

        $departmentCodes = collect($validated['selected_departments'])
            ->pluck('code')
            ->unique()
            ->values()
            ->all();

        Setting::query()->updateOrCreate(
            ['key' => 'department_code'],
            ['value' => (string) ($departmentCodes[0] ?? ''), 'group' => 'zones']
        );
        Setting::query()->updateOrCreate(
            ['key' => 'department_codes'],
            ['value' => json_encode($departmentCodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'group' => 'zones']
        );
        Setting::query()->updateOrCreate(['key' => 'priority_cities'], ['value' => $validated['priority_cities'] ?? '', 'group' => 'zones']);
        Setting::query()->updateOrCreate(['key' => 'intervention_radius_km'], ['value' => (string) $validated['intervention_radius_km'], 'group' => 'zones']);

        foreach ($departmentCodes as $departmentCode) {
            ImportDepartmentCitiesJob::dispatch($departmentCode);
        }

        $this->nextStep();
    }

    public function getImportedCitiesCountProperty(): int
    {
        $departmentCodes = collect($this->selected_departments)->pluck('code')->filter()->values()->all();

        if ($departmentCodes === []) {
            return 0;
        }

        return City::query()->whereIn('department_code', $departmentCodes)->count();
    }

    public function updatedDepartmentSearch(): void
    {
        $this->department_suggestions = $this->searchDepartmentOptions();
    }

    public function addDepartment(string $code, string $name): void
    {
        $exists = collect($this->selected_departments)->contains(fn (array $department): bool => $department['code'] === $code);

        if (! $exists) {
            $this->selected_departments[] = ['code' => $code, 'name' => $name];
        }

        $this->department_search = '';
        $this->department_suggestions = $this->searchDepartmentOptions();
    }

    public function removeDepartment(string $code): void
    {
        $this->selected_departments = collect($this->selected_departments)
            ->reject(fn (array $department): bool => $department['code'] === $code)
            ->values()
            ->all();
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

    private function searchDepartmentOptions(): array
    {
        /** @var GeoGouvServiceInterface $geoGouvService */
        $geoGouvService = app(GeoGouvServiceInterface::class);
        $selectedCodes = collect($this->selected_departments)->pluck('code')->all();

        return $geoGouvService
            ->searchDepartments($this->department_search, 20)
            ->reject(fn (array $department): bool => in_array($department['code'], $selectedCodes, true))
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.onboarding.zones-step');
    }
}
