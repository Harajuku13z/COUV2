<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Contracts\LeadServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\StoreDevisRequest;
use App\Http\Requests\StoreUrgenceRequest;
use App\Models\Page;
use App\Services\ImageOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class LeadPublicController extends Controller
{
    public function __construct(
        private readonly LeadServiceInterface $leadService,
        private readonly ImageOptimizationService $imageOptimizationService,
    )
    {
    }

    public function storeDevis(StoreDevisRequest $request)
    {
        $page = $request->filled('page_id')
            ? Page::query()->findOrFail((int) $request->input('page_id'))
            : null;
        $lead = $this->leadService->createFromRequest($request, $page);

        foreach ($request->file('uploaded_files', []) as $file) {
            $files = $lead->uploaded_files ?? [];

            if (str_starts_with((string) $file->getMimeType(), 'image/')) {
                $optimized = $this->imageOptimizationService->optimizePhoto($file);
                $files[] = [
                    'url' => Storage::disk('public')->url($optimized['jpeg']),
                    'webp_url' => Storage::disk('public')->url($optimized['webp']),
                    'placeholder_url' => Storage::disk('public')->url($optimized['placeholder']),
                ];
            } else {
                $path = $file->store('leads', 'public');
                $files[] = [
                    'url' => Storage::disk('public')->url($path),
                    'webp_url' => null,
                    'placeholder_url' => null,
                ];
            }

            $lead->update(['uploaded_files' => $files]);
        }

        return $request->expectsJson()
            ? new JsonResponse(['success' => true, 'message' => 'Votre demande de devis a bien ete envoyee.'])
            : back()->with('status', 'Votre demande de devis a bien ete envoyee.');
    }

    public function storeUrgence(StoreUrgenceRequest $request)
    {
        $page = $request->filled('page_id')
            ? Page::query()->findOrFail((int) $request->input('page_id'))
            : null;
        $payload = $request->merge(['urgency_level' => 'emergency']);
        $this->leadService->createFromRequest($payload, $page);

        return $request->expectsJson()
            ? new JsonResponse(['success' => true, 'message' => 'Votre demande urgente a bien ete transmise.'])
            : back()->with('status', 'Votre demande urgente a bien ete transmise.');
    }

    public function storeContact(StoreContactRequest $request)
    {
        $page = $request->filled('page_id')
            ? Page::query()->findOrFail((int) $request->input('page_id'))
            : null;
        $payload = $request->merge(['urgency_level' => 'low']);
        $this->leadService->createFromRequest($payload, $page);

        return $request->expectsJson()
            ? new JsonResponse(['success' => true, 'message' => 'Votre message a bien ete envoye.'])
            : back()->with('status', 'Votre message a bien ete envoye.');
    }
}
