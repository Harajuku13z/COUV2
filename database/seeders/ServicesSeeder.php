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
            // ── ELAGUEUR – Élagage ───────────────────────────────────────────
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Élagage d\'arbres'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Abattage d\'arbres'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Taille de haies'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Dessouchage'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Broyage de végétaux'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Recépage et étêtage'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Taille de fruitiers'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Démontage de branches en hauteur'],
            ['category' => 'elagage', 'is_emergency' => false, 'name' => 'Élagage en milieu urbain'],
            ['category' => 'elagage', 'is_emergency' => true,  'name' => 'Urgence arbre dangereux'],

            // ── ELAGUEUR – Jardinage ─────────────────────────────────────────
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Entretien de jardin'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Tonte de pelouse'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Débroussaillage'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Création de massifs'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Plantation d\'arbres et arbustes'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Engazonnement et semis'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Bêchage et amendement du sol'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Nettoyage de jardin après tempête'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Création de jardin paysager'],
            ['category' => 'jardinage', 'is_emergency' => false, 'name' => 'Devis jardinage gratuit'],

            // ── COUVREUR – Toiture ───────────────────────────────────────────
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Réparation de toiture'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Pose de toiture neuve'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Réfection de toiture'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Couverture en tuiles'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Couverture en ardoise'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Nettoyage et traitement anti-mousse'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Pose de Velux et fenêtres de toit'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Isolation des combles par le toit'],
            ['category' => 'toiture', 'is_emergency' => false, 'name' => 'Démoussage toiture'],
            ['category' => 'toiture', 'is_emergency' => true,  'name' => 'Urgence fuite toiture'],

            // ── COUVREUR – Zinguerie ─────────────────────────────────────────
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de gouttières'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Réparation de gouttières'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de faîtage et arêtier'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Habillage de souche de cheminée'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Noue et bavette en zinc'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Solin et étanchéité'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Chenaux et descentes pluviales'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Toiture terrasse et étanchéité'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de bac acier'],
            ['category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Devis toiture gratuit'],

            // ── PLOMBIER – Plomberie ─────────────────────────────────────────
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Installation sanitaire'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Remplacement de chauffe-eau'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Débouchage de canalisation'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Réparation de fuite'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Remplacement de robinetterie'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Installation de salle de bain'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Pose de douche italienne'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Entretien chaudière'],
            ['category' => 'plomberie', 'is_emergency' => false, 'name' => 'Adoucisseur d\'eau'],
            ['category' => 'plomberie', 'is_emergency' => true,  'name' => 'Urgence fuite et dégât des eaux'],

            // ── PLOMBIER – Chauffage ─────────────────────────────────────────
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de chauffage central'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Remplacement de radiateurs'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Pose de plancher chauffant'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de pompe à chaleur'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Entretien et dépannage chaudière gaz'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Désembouage de circuit de chauffage'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Remplacement de chaudière fioul'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de chaudière à condensation'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Pose de thermostat connecté'],
            ['category' => 'chauffage', 'is_emergency' => false, 'name' => 'Devis chauffage gratuit'],

            // ── PEINTRE – Intérieur ──────────────────────────────────────────
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture de murs et plafonds'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Pose de papier peint'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Enduit et ratissage'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture de menuiseries intérieures'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Ragréage et préparation de sol'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture décorative et effets'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Rénovation complète d\'appartement'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture anti-humidité'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Placo et doublage'],
            ['category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Devis peinture intérieure gratuit'],

            // ── PEINTRE – Extérieur ──────────────────────────────────────────
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture extérieure de maison'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de ravalement'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Traitement hydrofuge et imperméabilisant'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de volets et portails'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de clôtures en bois'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Enduit de façade'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture d\'escaliers extérieurs'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de toiture et tuiles'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Nettoyage haute pression façade'],
            ['category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Devis peinture extérieure gratuit'],

            // ── ELECTRICIEN – Électricité ─────────────────────────────────────
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Mise aux normes électriques'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Pose de tableau électrique'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Installation de prises et interrupteurs'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Câblage et tirage de gaines'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Installation d\'éclairage LED'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Volets roulants électriques'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Interphone et visiophone'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Alarme et sécurité'],
            ['category' => 'electricite', 'is_emergency' => false, 'name' => 'Domotique et maison connectée'],
            ['category' => 'electricite', 'is_emergency' => true,  'name' => 'Urgence panne électrique'],

            // ── ELECTRICIEN – Énergie ─────────────────────────────────────────
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Installation de panneaux solaires'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Borne de recharge véhicule électrique'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Bilan énergétique'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Pompe à chaleur air/air'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Chauffe-eau thermodynamique'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Climatisation réversible'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Régulation et thermostat intelligent'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Audit électrique'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Installation de groupe électrogène'],
            ['category' => 'energie', 'is_emergency' => false, 'name' => 'Devis électricité gratuit'],

            // ── FACADIER – Façade ─────────────────────────────────────────────
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Ravalement de façade'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Nettoyage de façade haute pression'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Enduit monocouche'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Enduit à la chaux'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Traitement anti-humidité façade'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Peinture de façade'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Hydrofugation et imperméabilisation'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Réparation de fissures façade'],
            ['category' => 'facade', 'is_emergency' => false, 'name' => 'Bardage et isolation par l\'extérieur'],
            ['category' => 'facade', 'is_emergency' => true,  'name' => 'Urgence fissure ou dégradation'],

            // ── FACADIER – Isolation ──────────────────────────────────────────
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation thermique par l\'extérieur (ITE)'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de combles perdus'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de plancher bas'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de toiture-terrasse'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Doublage intérieur des murs'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Soufflage de ouate de cellulose'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Audit thermique'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Dossier CEE et MaPrimeRénov'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation phonique'],
            ['category' => 'isolation', 'is_emergency' => false, 'name' => 'Devis isolation gratuit'],
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
                    'name'             => $service['name'],
                    'slug'             => Str::slug($service['name']),
                    'category'         => $service['category'],
                    'is_emergency'     => $service['is_emergency'] ? 1 : 0,
                    'icon'             => null,
                    'description'      => null,
                    'seasonal_triggers' => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );
        }
    }
}
