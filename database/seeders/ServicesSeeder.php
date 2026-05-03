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
            // Élagage
            ['name' => 'Élagage d\'arbres', 'category' => 'elagage', 'is_emergency' => false, 'icon' => 'scissors'],
            ['name' => 'Abattage d\'arbres', 'category' => 'elagage', 'is_emergency' => false, 'icon' => 'trash-2'],
            ['name' => 'Taille de haies', 'category' => 'elagage', 'is_emergency' => false, 'icon' => 'crop'],
            ['name' => 'Dessouchage', 'category' => 'elagage', 'is_emergency' => false, 'icon' => 'tool'],
            ['name' => 'Urgence arbre dangereux', 'category' => 'urgence', 'is_emergency' => true, 'icon' => 'alert-triangle'],
            ['name' => 'Broyage de végétaux', 'category' => 'elagage', 'is_emergency' => false, 'icon' => 'refresh-cw'],
            // Jardinage
            ['name' => 'Entretien de jardin', 'category' => 'jardinage', 'is_emergency' => false, 'icon' => 'sun'],
            ['name' => 'Tonte de pelouse', 'category' => 'jardinage', 'is_emergency' => false, 'icon' => 'grid'],
            ['name' => 'Création de jardin', 'category' => 'jardinage', 'is_emergency' => false, 'icon' => 'home'],
            ['name' => 'Plantation et engazonnement', 'category' => 'jardinage', 'is_emergency' => false, 'icon' => 'droplet'],
            ['name' => 'Débroussaillage', 'category' => 'jardinage', 'is_emergency' => false, 'icon' => 'layers'],
            ['name' => 'Devis jardinage gratuit', 'category' => 'devis', 'is_emergency' => false, 'icon' => 'file-text'],
        ];

        $now = now()->toDateTimeString();
        $newSlugs = array_map(fn ($s) => Str::slug($s['name']), $services);

        DB::connection('central')->table('services')
            ->whereNotIn('slug', $newSlugs)
            ->delete();

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
