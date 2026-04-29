<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::query()->where('group', 'api')->pluck('value', 'key')->map(fn ($value) => $value ? '••••••••' : '');

        return view('admin.api-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'openai_api_key' => ['nullable', 'string'],
            'serpapi_key' => ['nullable', 'string'],
            'openweather_api_key' => ['nullable', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            if (filled($value)) {
                Setting::query()->updateOrCreate(['key' => $key], ['value' => encrypt($value), 'group' => 'api']);
            }
        }

        return back()->with('status', 'API settings updated.');
    }

    public function testOpenAi(Request $request)
    {
        return response()->json(['valid' => str_starts_with((string) $request->string('key'), 'sk-')]);
    }

    public function testSerpApi(Request $request)
    {
        return response()->json(['valid' => strlen((string) $request->string('key')) > 20]);
    }

    public function testWeather(Request $request)
    {
        return response()->json(['valid' => strlen((string) $request->string('key')) > 10]);
    }
}
