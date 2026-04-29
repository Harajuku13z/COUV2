<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Setting;
use Spatie\LivewireWizard\Components\StepComponent;

class ApiKeysStep extends StepComponent
{
    public ?string $openai_api_key = null;
    public ?string $serpapi_key = null;
    public ?string $openweather_api_key = null;
    public bool $openai_valid = false;
    public bool $serpapi_valid = false;
    public bool $openweather_valid = false;

    public function testKeys(): void
    {
        $this->openai_valid = filled($this->openai_api_key) && str_starts_with($this->openai_api_key, 'sk-');
        $this->serpapi_valid = filled($this->serpapi_key) && strlen($this->serpapi_key) > 20;
        $this->openweather_valid = blank($this->openweather_api_key) || strlen((string) $this->openweather_api_key) > 10;
    }

    public function saveAndContinue(): void
    {
        $this->validate([
            'openai_api_key' => ['required', 'string'],
            'serpapi_key' => ['required', 'string'],
            'openweather_api_key' => ['nullable', 'string'],
        ]);

        $this->testKeys();

        foreach ([
            'openai_api_key' => $this->openai_api_key,
            'serpapi_key' => $this->serpapi_key,
            'openweather_api_key' => $this->openweather_api_key,
        ] as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value !== null ? encrypt($value) : null, 'group' => 'api']
            );
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('livewire.onboarding.api-keys-step');
    }
}
