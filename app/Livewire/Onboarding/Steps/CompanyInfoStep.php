<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Company;
use Spatie\LivewireWizard\Components\StepComponent;

class CompanyInfoStep extends StepComponent
{
    public string $name = '';
    public ?string $siret = null;
    public string $activity_type = 'couvreur';
    public string $activity_main = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $city = '';
    public string $postal_code = '';
    public array $certifications = [];
    public bool $emergency_available = false;
    public string $tone = 'professionnel';
    public ?string $offer_text = null;

    public function mount(): void
    {
        $company = Company::query()->first();

        if ($company === null) {
            return;
        }

        $this->fill($company->only([
            'name',
            'siret',
            'activity_type',
            'activity_main',
            'phone',
            'email',
            'address',
            'city',
            'postal_code',
            'certifications',
            'emergency_available',
            'tone',
            'offer_text',
        ]));
    }

    public function saveAndContinue(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'siret' => ['nullable', 'digits:14'],
            'activity_type' => ['required', 'in:couvreur,plombier,peintre,electricien,elagueur,facadier,custom'],
            'activity_main' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^(0[1-9])(?:[\s.-]?\d{2}){4}$/'],
            'email' => ['required', 'email'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'digits:5'],
            'certifications' => ['array'],
            'tone' => ['required', 'in:professionnel,chaleureux,urgent'],
            'offer_text' => ['nullable', 'string', 'max:500'],
        ]);

        Company::query()->updateOrCreate(
            ['id' => Company::query()->value('id') ?? 1],
            [...$validated, 'slug' => $validated['name'], 'emergency_available' => $this->emergency_available]
        );

        $this->nextStep();
    }

    public function render()
    {
        return view('livewire.onboarding.company-info-step');
    }
}
