<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Page;
use App\Models\Service;

class ContactController extends Controller
{
    public function show()
    {
        $company = Company::query()->firstOrFail();
        $services = Service::query()->orderBy('name')->take(8)->get();
        $featuredPage = Page::query()->published()->latest('published_at')->first();

        $seo = [
            'title' => 'Contact et devis | '.$company->name,
            'description' => 'Contactez '.$company->name.' pour un devis, une urgence ou une demande d information.',
            'canonical' => url('/contact'),
            'type' => 'website',
            'image' => $company->logo_path ? asset('storage/'.$company->logo_path) : null,
            'robots' => 'index,follow',
        ];

        $breadcrumbs = [
            ['name' => 'Accueil', 'url' => url('/')],
            ['name' => 'Contact', 'url' => null],
        ];

        $schema = [$company->schemaArray()];

        return view('public.contact', compact('company', 'services', 'featuredPage', 'seo', 'breadcrumbs', 'schema'));
    }

    public function devis()
    {
        $company = Company::query()->firstOrFail();
        $services = Service::query()->orderBy('name')->take(8)->get();
        $featuredPage = Page::query()->published()->latest('published_at')->first();

        $seo = [
            'title' => 'Demande de devis | '.$company->name,
            'description' => 'Obtenez un devis detaille et une reponse rapide pour votre projet.',
            'canonical' => url('/devis'),
            'type' => 'website',
            'image' => $company->logo_path ? asset('storage/'.$company->logo_path) : null,
            'robots' => 'index,follow',
        ];

        $breadcrumbs = [
            ['name' => 'Accueil', 'url' => url('/')],
            ['name' => 'Devis', 'url' => null],
        ];

        $schema = [$company->schemaArray()];

        return view('public.devis', compact('company', 'services', 'featuredPage', 'seo', 'breadcrumbs', 'schema'));
    }
}
