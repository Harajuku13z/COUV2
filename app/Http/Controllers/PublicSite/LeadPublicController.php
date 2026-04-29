<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Contracts\LeadServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\StoreDevisRequest;
use App\Http\Requests\StoreUrgenceRequest;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class LeadPublicController extends Controller
{
    public function __construct(private readonly LeadServiceInterface $leadService)
    {
    }

    public function storeDevis(StoreDevisRequest $request)
    {
        $page = $request->filled('page_id')
            ? Page::query()->findOrFail((int) $request->input('page_id'))
            : null;
        $lead = $this->leadService->createFromRequest($request, $page);

        foreach ($request->file('uploaded_files', []) as $file) {
            $path = 'leads/'.uniqid('lead_', true).'.jpg';
            $image = Image::read($file->getRealPath())->scaleDown(width: 1600);
            Storage::disk('public')->put($path, (string) $image->encode());
            $files = $lead->uploaded_files ?? [];
            $files[] = Storage::disk('public')->url($path);
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
