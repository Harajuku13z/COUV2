<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Company;
use App\Models\Media;
use App\Models\Setting;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\LivewireWizard\Components\StepComponent;

class BrandingStep extends StepComponent
{
    use WithFileUploads;

    public string $brand_primary = '#4f7465';
    public string $brand_secondary = '#23312d';
    public string $brand_accent = '#d97706';
    public string $heading_font = 'Sora';
    public string $body_font = 'Instrument Sans';
    public ?string $facebook_url = null;
    public ?string $instagram_url = null;
    public ?string $gbp_url = null;
    public $logo;
    public $favicon;

    public function mount(): void
    {
        foreach (['brand_primary', 'brand_secondary', 'brand_accent', 'heading_font', 'body_font'] as $key) {
            $this->{$key} = (string) (Setting::query()->where('key', $key)->value('value') ?? $this->{$key});
        }

        if ($company = Company::query()->first()) {
            $this->facebook_url = $company->facebook_url;
            $this->instagram_url = $company->instagram_url;
            $this->gbp_url = $company->gbp_url;
        }
    }

    public function saveAndContinue(): void
    {
        $validated = $this->validate([
            'brand_primary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_secondary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'heading_font' => ['required', 'string', 'max:255'],
            'body_font' => ['required', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'gbp_url' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png', 'max:500'],
        ]);

        foreach (['brand_primary', 'brand_secondary', 'brand_accent', 'heading_font', 'body_font'] as $key) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $validated[$key], 'group' => 'branding']);
        }

        $company = Company::query()->first();

        if ($company !== null) {
            $company->update([
                'facebook_url' => $this->facebook_url,
                'instagram_url' => $this->instagram_url,
                'gbp_url' => $this->gbp_url,
            ]);

            if ($this->logo !== null) {
                $paths = app(ImageOptimizationService::class)->optimizeLogo($this->logo);
                Media::query()->updateOrCreate(
                    ['mediable_type' => Company::class, 'mediable_id' => $company->id, 'type' => 'logo'],
                    ['disk' => 'public', 'path' => $paths['full'], 'url' => Storage::disk('public')->url($paths['full'])]
                );
                $company->update(['logo_path' => $paths['full']]);
            }

            if ($this->favicon !== null) {
                $paths = app(ImageOptimizationService::class)->optimizeLogo($this->favicon);
                Media::query()->updateOrCreate(
                    ['mediable_type' => Company::class, 'mediable_id' => $company->id, 'type' => 'favicon'],
                    ['disk' => 'public', 'path' => $paths['favicon'], 'url' => Storage::disk('public')->url($paths['favicon'])]
                );
            }
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('livewire.onboarding.branding-step');
    }
}
