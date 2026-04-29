<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Media;
use App\Models\Setting;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function __construct(private readonly ImageOptimizationService $imageOptimizationService)
    {
    }

    public function edit()
    {
        $company = Company::query()->first();
        $settings = Setting::query()->where('group', 'branding')->pluck('value', 'key');

        return view('admin.branding.edit', compact('company', 'settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'brand_primary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_secondary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'heading_font' => ['required', 'string'],
            'body_font' => ['required', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'branding']);
        }

        return back()->with('status', 'Branding updated.');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate(['logo' => ['required', 'image', 'max:5120']]);
        $company = Company::query()->firstOrFail();
        $file = $request->file('logo');
        $paths = $this->imageOptimizationService->optimizeLogo($file);
        Media::query()->updateOrCreate(
            ['mediable_type' => Company::class, 'mediable_id' => $company->id, 'type' => 'logo'],
            ['disk' => 'public', 'path' => $paths['full'], 'url' => Storage::disk('public')->url($paths['full'])]
        );
        $company->update(['logo_path' => $paths['full']]);

        return back()->with('status', 'Logo uploaded.');
    }

    public function uploadFavicon(Request $request)
    {
        $request->validate(['favicon' => ['required', 'file', 'mimes:ico,png', 'max:500']]);
        $company = Company::query()->firstOrFail();
        $file = $request->file('favicon');
        $paths = $this->imageOptimizationService->optimizeLogo($file);
        Media::query()->updateOrCreate(
            ['mediable_type' => Company::class, 'mediable_id' => $company->id, 'type' => 'favicon'],
            ['disk' => 'public', 'path' => $paths['favicon'], 'url' => Storage::disk('public')->url($paths['favicon'])]
        );

        return back()->with('status', 'Favicon uploaded.');
    }

    public function preview()
    {
        $settings = Setting::query()->where('group', 'branding')->pluck('value', 'key');

        return response()->json([
            'css' => [
                '--brand-primary' => $settings['brand_primary'] ?? '#4f7465',
                '--brand-secondary' => $settings['brand_secondary'] ?? '#23312d',
                '--brand-accent' => $settings['brand_accent'] ?? '#d97706',
            ],
        ]);
    }
}
