<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function edit(): View
    {
        $company = Company::query()->first();
        return view('admin.company.edit', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'siret'               => ['nullable', 'string', 'size:14'],
            'activity_type'       => ['required', 'string', 'in:couvreur,plombier,peintre,electricien,elagueur,facadier,autre'],
            'phone'               => ['required', 'string', 'max:20'],
            'email'               => ['required', 'email', 'max:255'],
            'address'             => ['required', 'string', 'max:255'],
            'city'                => ['required', 'string', 'max:100'],
            'postal_code'         => ['required', 'string', 'size:5'],
            'certifications'      => ['nullable', 'array'],
            'certifications.*'    => ['string'],
            'emergency_available' => ['boolean'],
            'tone'                => ['required', 'string', 'in:professionnel,chaleureux,urgent'],
            'offer_text'          => ['nullable', 'string', 'max:500'],
            'gbp_url'             => ['nullable', 'url', 'max:500'],
            'facebook_url'        => ['nullable', 'url', 'max:500'],
            'instagram_url'       => ['nullable', 'url', 'max:500'],
        ]);

        $validated['emergency_available'] = $request->boolean('emergency_available');
        $validated['certifications']      = $request->input('certifications', []);

        $company = Company::query()->first();
        if ($company) {
            $company->update($validated);
        } else {
            Company::query()->create($validated);
        }

        return back()->with('status', 'Informations de l\'entreprise mises à jour.');
    }
}
