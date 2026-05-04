<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Company;
use App\Models\Service;
use App\Models\WebsiteService;
use Spatie\LivewireWizard\Components\StepComponent;

class ServicesStep extends StepComponent
{
    private const ACTIVITY_CATEGORIES = [
        'elagueur'    => ['elagage', 'jardinage'],
        'couvreur'    => ['toiture', 'zinguerie'],
        'plombier'    => ['plomberie', 'chauffage'],
        'peintre'     => ['peinture_interieure', 'peinture_exterieure'],
        'electricien' => ['electricite', 'energie'],
        'facadier'    => ['facade', 'isolation'],
    ];

    private const CATEGORY_LABELS = [
        'elagage'              => 'Élagage',
        'jardinage'            => 'Jardinage',
        'toiture'              => 'Toiture',
        'zinguerie'            => 'Zinguerie',
        'plomberie'            => 'Plomberie',
        'chauffage'            => 'Chauffage',
        'peinture_interieure'  => 'Peinture intérieure',
        'peinture_exterieure'  => 'Peinture extérieure',
        'electricite'          => 'Électricité',
        'energie'              => 'Énergie & solaire',
        'facade'               => 'Façade',
        'isolation'            => 'Isolation',
    ];

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
                    'is_active'          => (bool) ($this->selected[$service->id] ?? false),
                    'custom_description' => $this->descriptions[$service->id] ?? null,
                    'custom_price'       => $this->prices[$service->id] ?? null,
                    'sort_order'         => $service->id,
                ]
            );
        }

        $this->nextStep();
    }

    public function render()
    {
        $activityType = Company::query()->value('activity_type') ?? 'custom';
        $categories   = self::ACTIVITY_CATEGORIES[$activityType] ?? [];

        $servicesByCategory = Service::query()
            ->when($categories !== [], fn ($q) => $q->whereIn('category', $categories))
            ->orderByRaw('FIELD(category, ' . implode(',', array_fill(0, count($categories), '?')) . ')', $categories)
            ->orderBy('is_emergency', 'desc')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('livewire.onboarding.services-step', [
            'servicesByCategory' => $servicesByCategory,
            'categoryLabels'     => self::CATEGORY_LABELS,
            'activityType'       => $activityType,
        ]);
    }
}
