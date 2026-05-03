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
            // ── ELAGUEUR ─────────────────────────────────────────────────────
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Élagage d\'arbres'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Abattage d\'arbres'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Taille de haies'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Dessouchage'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Broyage de végétaux'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Recépage et étêtage'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Taille de fruitiers'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Démontage de branches en hauteur'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => false, 'name' => 'Élagage en milieu urbain'],
            ['activity_type' => 'elagueur', 'category' => 'elagage', 'is_emergency' => true,  'name' => 'Urgence arbre dangereux'],

            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Entretien de jardin'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Tonte de pelouse'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Débroussaillage'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Création de massifs'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Plantation d\'arbres et arbustes'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Engazonnement et semis'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Bêchage et amendement du sol'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Nettoyage de jardin après tempête'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Création de jardin paysager'],
            ['activity_type' => 'elagueur', 'category' => 'jardinage', 'is_emergency' => false, 'name' => 'Devis jardinage gratuit'],

            // ── COUVREUR ─────────────────────────────────────────────────────
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Réparation de toiture'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Pose de toiture neuve'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Réfection de toiture'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Couverture en tuiles'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Couverture en ardoise'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Nettoyage et traitement anti-mousse'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Pose de Velux et fenêtres de toit'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Isolation des combles par le toit'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => false, 'name' => 'Démoussage toiture'],
            ['activity_type' => 'couvreur', 'category' => 'toiture', 'is_emergency' => true,  'name' => 'Urgence fuite toiture'],

            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de gouttières'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Réparation de gouttières'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de faîtage et arêtier'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Habillage de souche de cheminée'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Noue et bavette en zinc'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Solin et étanchéité'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Chenaux et descentes pluviales'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Toiture terrasse et étanchéité'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Pose de bac acier'],
            ['activity_type' => 'couvreur', 'category' => 'zinguerie', 'is_emergency' => false, 'name' => 'Devis toiture gratuit'],

            // ── PLOMBIER ─────────────────────────────────────────────────────
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Installation sanitaire'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Remplacement de chauffe-eau'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Débouchage de canalisation'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Réparation de fuite'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Remplacement de robinetterie'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Installation de salle de bain'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Pose de douche italienne'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Entretien chaudière'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => false, 'name' => 'Adoucisseur d\'eau'],
            ['activity_type' => 'plombier', 'category' => 'plomberie', 'is_emergency' => true,  'name' => 'Urgence fuite et dégât des eaux'],

            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de chauffage central'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Remplacement de radiateurs'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Pose de plancher chauffant'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de pompe à chaleur'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Entretien et dépannage chaudière gaz'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Désembouage de circuit de chauffage'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Remplacement de chaudière fioul'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Installation de chaudière à condensation'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Pose de thermostat connecté'],
            ['activity_type' => 'plombier', 'category' => 'chauffage', 'is_emergency' => false, 'name' => 'Devis chauffage gratuit'],

            // ── PEINTRE ──────────────────────────────────────────────────────
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture de murs et plafonds'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Pose de papier peint'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Enduit et ratissage'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture de menuiseries intérieures'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Ragréage et préparation de sol'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture décorative et effets'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Rénovation complète d\'appartement'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Peinture anti-humidité'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Placo et doublage'],
            ['activity_type' => 'peintre', 'category' => 'peinture_interieure', 'is_emergency' => false, 'name' => 'Devis peinture intérieure gratuit'],

            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de façade'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Ravalement de façade'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Traitement hydrofuge de façade'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de volets et portails'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de clôtures en bois'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Enduit de façade'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture d\'escaliers extérieurs'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Peinture de toiture et tuiles'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Nettoyage haute pression façade'],
            ['activity_type' => 'peintre', 'category' => 'peinture_exterieure', 'is_emergency' => false, 'name' => 'Devis peinture extérieure gratuit'],

            // ── ELECTRICIEN ──────────────────────────────────────────────────
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Mise aux normes électriques'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Pose de tableau électrique'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Installation de prises et interrupteurs'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Câblage et tirage de gaines'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Installation d\'éclairage LED'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Volets roulants électriques'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Interphone et visiophone'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Alarme et sécurité'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => false, 'name' => 'Domotique et maison connectée'],
            ['activity_type' => 'electricien', 'category' => 'electricite', 'is_emergency' => true,  'name' => 'Urgence panne électrique'],

            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Installation de panneaux solaires'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Borne de recharge véhicule électrique'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Bilan énergétique'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Pompe à chaleur air/air'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Chauffe-eau thermodynamique'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Climatisation réversible'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Régulation et thermostat intelligent'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Audit électrique'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Installation de groupe électrogène'],
            ['activity_type' => 'electricien', 'category' => 'energie', 'is_emergency' => false, 'name' => 'Devis électricité gratuit'],

            // ── FACADIER ─────────────────────────────────────────────────────
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Ravalement de façade'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Nettoyage de façade haute pression'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Enduit monocouche'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Enduit à la chaux'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Traitement anti-humidité façade'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Peinture de façade'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Hydrofugation et imperméabilisation'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Réparation de fissures façade'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Bardage et isolation par l\'extérieur'],
            ['activity_type' => 'facadier', 'category' => 'facade', 'is_emergency' => false, 'name' => 'Urgence fissure ou dégradation'],

            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation thermique par l\'extérieur (ITE)'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de combles perdus'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de plancher bas'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation de toiture-terrasse'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Doublage intérieur des murs'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Soufflage de ouate de cellulose'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Audit thermique'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Dossier CEE et MaPrimeRénov'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Isolation phonique'],
            ['activity_type' => 'facadier', 'category' => 'isolation', 'is_emergency' => false, 'name' => 'Devis isolation gratuit'],
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
                    'activity_type' => $service['activity_type'],
                    'is_emergency' => $service['is_emergency'] ? 1 : 0,
                    'icon' => null,
                    'description' => null,
                    'seasonal_triggers' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
