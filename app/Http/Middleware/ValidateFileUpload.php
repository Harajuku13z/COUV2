<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ValidateFileUpload
{
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->allFiles() as $files) {
            foreach ((array) $files as $file) {
                if (! $file instanceof UploadedFile) {
                    continue;
                }

                $originalName = $file->getClientOriginalName();

                if (preg_match('/[^\w.\-\s]/', $originalName) === 1) {
                    abort(422, 'Le nom de fichier contient des caracteres non autorises.');
                }

                if (! in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
                    abort(422, 'Le type de fichier n est pas autorise.');
                }

                if ($file->getSize() > (5 * 1024 * 1024)) {
                    abort(422, 'Le fichier depasse la taille maximale autorisee.');
                }
            }
        }

        return $next($request);
    }
}
