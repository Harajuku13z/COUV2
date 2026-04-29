<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageOptimizationService
{
    public function optimizeLogo(UploadedFile $file): array
    {
        $baseName = pathinfo($file->hashName(), PATHINFO_FILENAME);
        $directory = 'branding/logos';

        $fullPath = $directory.'/'.$baseName.'-full.png';
        $mediumPath = $directory.'/'.$baseName.'-200.png';
        $faviconPath = $directory.'/'.$baseName.'-64.png';

        $source = Image::read($file->getRealPath());

        Storage::disk('public')->put($fullPath, (string) $source->scaleDown(width: 400)->toPng());
        Storage::disk('public')->put($mediumPath, (string) $source->scaleDown(width: 200)->toPng());
        Storage::disk('public')->put($faviconPath, (string) $source->scaleDown(width: 64, height: 64)->toPng());

        return [
            'full' => $fullPath,
            'medium' => $mediumPath,
            'favicon' => $faviconPath,
        ];
    }

    public function optimizePhoto(UploadedFile $file): array
    {
        $baseName = pathinfo($file->hashName(), PATHINFO_FILENAME);
        $directory = 'optimized/photos';

        $jpegPath = $directory.'/'.$baseName.'.jpg';
        $webpPath = $directory.'/'.$baseName.'.webp';

        $image = Image::read($file->getRealPath())->scaleDown(width: 1200, height: 1200);

        Storage::disk('public')->put($jpegPath, (string) $image->toJpeg(82));
        Storage::disk('public')->put($webpPath, (string) $image->toWebp(82));

        return [
            'jpeg' => $jpegPath,
            'webp' => $webpPath,
            'placeholder' => $this->generatePlaceholder($jpegPath),
        ];
    }

    public function generateWebP(string $path): string
    {
        $webpPath = preg_replace('/\.[a-zA-Z0-9]+$/', '.webp', $path) ?? $path.'.webp';

        if (Storage::disk('public')->exists($webpPath)) {
            return $webpPath;
        }

        $image = Image::read(Storage::disk('public')->path($path));
        Storage::disk('public')->put($webpPath, (string) $image->toWebp(82));

        return $webpPath;
    }

    public function generatePlaceholder(string $path): string
    {
        $placeholderPath = preg_replace('/\.[a-zA-Z0-9]+$/', '-placeholder.jpg', $path) ?? $path.'-placeholder.jpg';

        if (Storage::disk('public')->exists($placeholderPath)) {
            return $placeholderPath;
        }

        $image = Image::read(Storage::disk('public')->path($path))->scaleDown(width: 20, height: 20)->blur(8);
        Storage::disk('public')->put($placeholderPath, (string) $image->toJpeg(35));

        return $placeholderPath;
    }
}
