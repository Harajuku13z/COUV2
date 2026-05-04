@extends('layouts.admin')
@section('title', 'Informations de l\'entreprise')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Informations de l'entreprise</h1>
        <p class="mt-1 text-sm text-slate-500">Gérez les informations générales de votre entreprise.</p>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.company.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Informations générales --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Informations générales</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nom de l'entreprise <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $company->name ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="siret" class="block text-sm font-medium text-slate-700 mb-1">SIRET (14 chiffres)</label>
                    <input type="text" id="siret" name="siret"
                           value="{{ old('siret', $company->siret ?? '') }}"
                           maxlength="14"
                           placeholder="12345678901234"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                </div>

                <div>
                    <label for="activity_type" class="block text-sm font-medium text-slate-700 mb-1">Type d'activité <span class="text-red-500">*</span></label>
                    <select id="activity_type" name="activity_type"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required>
                        @foreach (['couvreur' => 'Couvreur', 'plombier' => 'Plombier', 'peintre' => 'Peintre', 'electricien' => 'Électricien', 'elagueur' => 'Élagueur', 'facadier' => 'Façadier', 'autre' => 'Autre'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('activity_type', $company->activity_type ?? '') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone', $company->phone ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $company->email ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>
            </div>
        </div>

        {{-- Adresse --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Adresse</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Adresse <span class="text-red-500">*</span></label>
                    <input type="text" id="address" name="address"
                           value="{{ old('address', $company->address ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ville <span class="text-red-500">*</span></label>
                    <input type="text" id="city" name="city"
                           value="{{ old('city', $company->city ?? '') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-slate-700 mb-1">Code postal <span class="text-red-500">*</span></label>
                    <input type="text" id="postal_code" name="postal_code"
                           value="{{ old('postal_code', $company->postal_code ?? '') }}"
                           maxlength="5"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                </div>
            </div>
        </div>

        {{-- Certifications & Urgence --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Certifications & Urgence</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Certifications</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['RGE' => 'RGE', 'Qualibat' => 'Qualibat', 'Decennale' => 'Décennale'] as $val => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="certifications[]" value="{{ $val }}"
                                   @checked(in_array($val, old('certifications', $company->certifications ?? [])))
                                   class="rounded border-slate-300 text-slate-900">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="hidden" name="emergency_available" value="0">
                        <input type="checkbox" name="emergency_available" value="1"
                               @checked(old('emergency_available', $company->emergency_available ?? false))
                               class="sr-only peer">
                        <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:bg-slate-900 transition-colors"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm font-medium text-slate-700">Disponible pour les urgences</span>
                </label>
            </div>
        </div>

        {{-- Ton de communication --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Ton de communication</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Ton utilisé sur le site <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['professionnel' => 'Professionnel', 'chaleureux' => 'Chaleureux', 'urgent' => 'Urgent'] as $val => $label)
                        <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="radio" name="tone" value="{{ $val }}"
                                   @checked(old('tone', $company->tone ?? 'professionnel') === $val)
                                   class="border-slate-300 text-slate-900">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="offer_text" class="block text-sm font-medium text-slate-700 mb-1">Texte d'accroche / offre (max 500 caractères)</label>
                <textarea id="offer_text" name="offer_text" rows="3" maxlength="500"
                          placeholder="Ex : Devis gratuit sous 24h, intervention rapide sur toute la région..."
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">{{ old('offer_text', $company->offer_text ?? '') }}</textarea>
                <p class="mt-1 text-xs text-slate-400">{{ strlen(old('offer_text', $company->offer_text ?? '')) }}/500</p>
            </div>
        </div>

        {{-- Réseaux sociaux --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm space-y-5">
            <h2 class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-3">Présence en ligne</h2>

            <div>
                <label for="gbp_url" class="block text-sm font-medium text-slate-700 mb-1">Google Business Profile (URL)</label>
                <input type="url" id="gbp_url" name="gbp_url"
                       value="{{ old('gbp_url', $company->gbp_url ?? '') }}"
                       placeholder="https://maps.google.com/..."
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
            </div>

            <div>
                <label for="facebook_url" class="block text-sm font-medium text-slate-700 mb-1">Facebook (URL)</label>
                <input type="url" id="facebook_url" name="facebook_url"
                       value="{{ old('facebook_url', $company->facebook_url ?? '') }}"
                       placeholder="https://www.facebook.com/..."
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
            </div>

            <div>
                <label for="instagram_url" class="block text-sm font-medium text-slate-700 mb-1">Instagram (URL)</label>
                <input type="url" id="instagram_url" name="instagram_url"
                       value="{{ old('instagram_url', $company->instagram_url ?? '') }}"
                       placeholder="https://www.instagram.com/..."
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-2xl bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition-colors">
                Enregistrer les modifications
            </button>
        </div>
    </form>

</div>
@endsection
