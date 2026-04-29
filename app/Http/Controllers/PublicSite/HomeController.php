<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Media;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\WeatherEvent;

class HomeController extends Controller
{
    public function index()
    {
        $company = Company::query()->firstOrFail();
        $services = Service::query()->whereHas('websiteServices', fn ($query) => $query->where('is_active', true))->with('websiteServices')->take(6)->get();
        $testimonials = Testimonial::query()->active()->latest()->take(3)->get();
        $media = Media::query()->whereIn('type', ['before', 'after'])->latest()->take(3)->get();
        $weatherAlert = WeatherEvent::query()->whereIn('intensity', ['high', 'extreme'])->where('created_at', '>=', now()->subDay())->latest()->first();

        return view('public.home', compact('company', 'services', 'testimonials', 'media', 'weatherAlert'));
    }
}
