<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizationService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function optimizeLogo(UploadedFile $file): array
    {
        $baseName = pathinfo($file->hashName(), PATHINFO_FILENAME);
        $directory = 'branding/logos';

        $fullPath    = $directory.'/'.$baseName.'-full.png';
        $mediumPath  = $directory.'/'.$baseName.'-200.png';
        $faviconPath = $directory.'/'.$baseName.'-64.png';

        $sourcePath = $file->getRealPath();

        Storage::disk('public')->put($fullPath, (string) $this->manager->read($sourcePath)->scaleDown(width: 400)->toPng());
        Storage::disk('public')->put($mediumPath, (string) $this->manager->read($sourcePath)->scaleDown(width: 200)->toPng());
        Storage::disk('public')->put($faviconPath, (string) $this->manager->read($sourcePath)->scaleDown(width: 64, height: 64)->toPng());

        return [
            'full'    => $fullPath,
            'medium'  => $mediumPath,
            'favicon' => $faviconPath,
        ];
    }

    public function optimizePhoto(UploadedFile $file): array
    {
        $baseName  = pathinfo($file->hashName(), PATHINFO_FILENAME);
        $directory = 'optimized/photos';

        $jpegPath = $directory.'/'.$baseName.'.jpg';
        $webpPath = $directory.'/'.$baseName.'.webp';

        $image = $this->manager->read($file->getRealPath())->scaleDown(width: 1200, height: 1200);

        Storage::disk('public')->put($jpegPath, (string) $image->toJpeg(82));
        Storage::disk('public')->put($webpPath, (string) $image->toWebp(82));

        return [
            'jpeg'        => $jpegPath,
            'webp'        => $webpPath,
            'placeholder' => $this->generatePlaceholder($jpegPath),
        ];
    }

    public function generateWebP(string $path): string
    {
        $webpPath = preg_replace('/\.[a-zA-Z0-9]+$/', '.webp', $path) ?? $path.'.webp';

        if (Storage::disk('public')->exists($webpPath)) {
            return $webpPath;
        }

        $image = $this->manager->read(Storage::disk('public')->path($path));
        Storage::disk('public')->put($webpPath, (string) $image->toWebp(82));

        return $webpPath;
    }

    public function generatePlaceholder(string $path): string
    {
        $placeholderPath = preg_replace('/\.[a-zA-Z0-9]+$/', '-placeholder.jpg', $path) ?? $path.'-placeholder.jpg';

        if (Storage::disk('public')->exists($placeholderPath)) {
            return $placeholderPath;
        }

        $image = $this->manager->read(Storage::disk('public')->path($path))->scaleDown(width: 20, height: 20)->blur(8);
        Storage::disk('public')->put($placeholderPath, (string) $image->toJpeg(35));

        return $placeholderPath;
    }
}
