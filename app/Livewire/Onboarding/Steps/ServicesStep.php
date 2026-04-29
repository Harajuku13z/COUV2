<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Service;
use App\Models\WebsiteService;
use Spatie\LivewireWizard\Components\StepComponent;

class ServicesStep extends StepComponent
{
    public array $selected = [];
    public array $descriptions = [];
    public array $prices = [];

    public function mount(): void
    {
        WebsiteService::query()->get()->each(function (WebsiteService $websiteService): void {
            $this->selected[$websiteService->service_id] = $websiteService->is_active;
            $this->descriptions[$websiteService->service_id] = $websiteService->custom_description;
            $this->prices[$websiteService->service_id] = $websiteService->custom_price;
        });
    }

    public function saveAndContinue(): void
    {
        foreach (Service::query()->get() as $service) {
            WebsiteService::query()->updateOrCreate(
                ['service_id' => $service->id],
                [
                    'is_active' => (bool) ($this->selected[$service->id] ?? false),
                    'custom_description' => $this->descriptions[$service->id] ?? null,
                    'custom_price' => $this->prices[$service->id] ?? null,
                    'sort_order' => $service->id,
                ]
            );
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('livewire.onboarding.services-step', [
            'services' => Service::query()->orderBy('name')->get(),
        ]);
    }
}
