<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Réparation de toiture', 'category' => 'toiture', 'is_emergency' => true, 'icon' => 'tool'],
            ['name' => 'Pose de toiture', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'home'],
            ['name' => 'Nettoyage de toiture', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'droplet'],
            ['name' => 'Traitement anti-mousse', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'shield'],
            ['name' => 'Zinguerie', 'category' => 'zinguerie', 'is_emergency' => false, 'icon' => 'layers'],
            ['name' => 'Isolation des combles', 'category' => 'isolation', 'is_emergency' => false, 'icon' => 'thermometer'],
            ['name' => 'Réfection de toiture', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'refresh-cw'],
            ['name' => 'Urgence fuite toiture', 'category' => 'urgence', 'is_emergency' => true, 'icon' => 'alert-triangle'],
            ['name' => 'Devis toiture gratuit', 'category' => 'devis', 'is_emergency' => false, 'icon' => 'file-text'],
            ['name' => 'Couverture tuiles', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'grid'],
            ['name' => 'Couverture ardoise', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'square'],
            ['name' => 'Velux et fenêtres de toit', 'category' => 'toiture', 'is_emergency' => false, 'icon' => 'sun'],
        ];

        $now = now()->toDateTimeString();

        foreach ($services as $service) {
            DB::connection('central')->table('services')->updateOrInsert(
                ['slug' => Str::slug($service['name'])],
                [
                    'name' => $service['name'],
                    'slug' => Str::slug($service['name']),
                    'category' => $service['category'],
                    'is_emergency' => $service['is_emergency'] ? 1 : 0,
                    'icon' => $service['icon'],
                    'description' => null,
                    'seasonal_triggers' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
