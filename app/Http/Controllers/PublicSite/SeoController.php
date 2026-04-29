<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Contracts\SeoServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{
    public function __construct(private readonly SeoServiceInterface $seoService)
    {
    }

    public function sitemap()
    {
        $this->seoService->generateSitemap();

        return response(Storage::disk('public')->get('sitemap.xml'), 200, ['Content-Type' => 'text/xml']);
    }

    public function robots()
    {
        return response($this->seoService->generateRobotsTxt(), 200, ['Content-Type' => 'text/plain']);
    }
}
