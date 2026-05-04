<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding\Steps;

use App\Models\Setting;
use Spatie\LivewireWizard\Components\StepComponent;

class AdminSetupStep extends StepComponent
{
    public string $admin_email = '';
    public string $admin_password = '';
    public string $admin_password_confirmation = '';

    public string $mail_host = 'smtp.hostinger.com';
    public string $mail_port = '465';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_encryption = 'ssl';
    public string $mail_from_address = '';
    public string $mail_from_name = '';

    public function mount(): void
    {
        $this->admin_email       = Setting::query()->where('key', 'admin_email')->value('value') ?? '';
        $this->mail_host         = Setting::query()->where('key', 'mail_host')->value('value') ?? 'smtp.hostinger.com';
        $this->mail_port         = Setting::query()->where('key', 'mail_port')->value('value') ?? '465';
        $this->mail_username     = Setting::query()->where('key', 'mail_username')->value('value') ?? '';
        $this->mail_encryption   = Setting::query()->where('key', 'mail_encryption')->value('value') ?? 'ssl';
        $this->mail_from_address = Setting::query()->where('key', 'mail_from_address')->value('value') ?? '';
        $this->mail_from_name    = Setting::query()->where('key', 'mail_from_name')->value('value') ?? '';
    }

    public function saveAndContinue(): void
    {
        $rules = [
            'admin_email'    => ['required', 'email'],
            'admin_password' => ['required', 'min:8', 'confirmed'],
            'mail_host'      => ['nullable', 'string'],
            'mail_port'      => ['nullable', 'integer'],
            'mail_username'  => ['nullable', 'string'],
            'mail_password'  => ['nullable', 'string'],
            'mail_encryption' => ['nullable', 'string'],
            'mail_from_address' => ['nullable', 'email'],
            'mail_from_name'    => ['nullable', 'string'],
        ];

        if (empty($this->admin_password) && Setting::query()->where('key', 'admin_password')->exists()) {
            $rules['admin_password'] = ['nullable', 'min:8', 'confirmed'];
        }

        $this->validate($rules);

        Setting::query()->updateOrCreate(['key' => 'admin_email'], ['value' => $this->admin_email, 'group' => 'auth']);

        if (filled($this->admin_password)) {
            Setting::query()->updateOrCreate(
                ['key' => 'admin_password'],
                ['value' => bcrypt($this->admin_password), 'group' => 'auth']
            );
        }

        foreach ([
            'mail_host'         => $this->mail_host,
            'mail_port'         => $this->mail_port,
            'mail_username'     => $this->mail_username,
            'mail_encryption'   => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name'    => $this->mail_from_name,
        ] as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'mail']);
        }

        if (filled($this->mail_password)) {
            Setting::query()->updateOrCreate(
                ['key' => 'mail_password'],
                ['value' => encrypt($this->mail_password), 'group' => 'mail']
            );
        }

        $this->nextStep();
    }

    public function render()
    {
        return view('livewire.onboarding.admin-setup-step');
    }
}
