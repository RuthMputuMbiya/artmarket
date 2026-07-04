<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        // ===== SUPPRIMER LES ANCIENNES CATÉGORIES =====
        Categorie::query()->delete();

        $categories = [
            [
                'nom' => 'Peintures artistiques',
                'icone' => '🎨',
                'description' => 'Découvrez notre collection de peintures artistiques uniques',
                'order' => 1,
                'enfants' => [
                    ['nom' => 'Tableau abstrait', 'icone' => '🖼️', 'description' => 'Art abstrait moderne et contemporain'],
                    ['nom' => 'Tableau de paysage', 'icone' => '🌄', 'description' => 'Paysages naturels et urbains'],
                    ['nom' => 'Portrait artistique', 'icone' => '👤', 'description' => 'Portraits uniques et personnalisés'],
                    ['nom' => 'Peinture contemporaine', 'icone' => '⭐', 'description' => 'Art contemporain et moderne'],
                    ['nom' => 'Peinture murale', 'icone' => '🧱', 'description' => 'Peintures pour murs et grands formats'],
                ]
            ],
            [
                'nom' => 'Sculptures artistiques',
                'icone' => '🗿',
                'description' => 'Sculptures uniques en différents matériaux',
                'order' => 2,
                'enfants' => [
                    ['nom' => 'Sculpture en bois', 'icone' => '🪵', 'description' => 'Sculptures en bois massif'],
                    ['nom' => 'Sculpture en pierre', 'icone' => '🪨', 'description' => 'Sculptures en pierre naturelle'],
                    ['nom' => 'Statue artistique', 'icone' => '🏛️', 'description' => 'Statues et figurines artistiques'],
                    ['nom' => 'Sculpture en métal', 'icone' => '⚙️', 'description' => 'Sculptures en métal et fer forgé'],
                    ['nom' => 'Sculpture décorative', 'icone' => '🏠', 'description' => 'Sculptures pour la décoration intérieure'],
                ]
            ],
            [
                'nom' => 'Bijoux artistiques',
                'icone' => '💎',
                'description' => 'Bijoux faits main par des artisans talentueux',
                'order' => 3,
                'enfants' => [
                    ['nom' => 'Collier', 'icone' => '📿', 'description' => 'Colliers artisanaux en perles et métaux'],
                    ['nom' => 'Bracelet', 'icone' => '📿', 'description' => 'Bracelets uniques pour tous les styles'],
                    ['nom' => 'Bague', 'icone' => '💍', 'description' => 'Bagues artisanales en or, argent et pierres'],
                    ['nom' => "Boucles d'oreilles", 'icone' => '👂', 'description' => 'Boucles d\'oreilles créatives'],
                    ['nom' => 'Pendentif', 'icone' => '🔮', 'description' => 'Pendentifs uniques en pierres et métaux'],
                    ['nom' => 'Parure', 'icone' => '👑', 'description' => 'Parures complètes pour toutes les occasions'],
                ]
            ],
            [
                'nom' => 'Céramiques et poteries',
                'icone' => '🏺',
                'description' => 'Céramiques et poteries artisanales',
                'order' => 4,
                'enfants' => [
                    ['nom' => 'Vase décoratif', 'icone' => '🏺', 'description' => 'Vases décoratifs pour la maison'],
                    ['nom' => 'Jarre', 'icone' => '🏺', 'description' => 'Jarres artisanales pour la décoration'],
                    ['nom' => 'Pot de fleurs', 'icone' => '🌱', 'description' => 'Pots pour fleurs et plantes'],
                    ['nom' => 'Bol', 'icone' => '🥣', 'description' => 'Bol artisanaux pour la cuisine'],
                    ['nom' => 'Tasse', 'icone' => '☕', 'description' => 'Tasses uniques et personnalisées'],
                    ['nom' => 'Assiette artisanale', 'icone' => '🍽️', 'description' => 'Assiettes artisanales pour repas'],
                ]
            ],
        ];

        foreach ($categories as $categorieData) {
            $enfants = $categorieData['enfants'] ?? [];
            unset($categorieData['enfants']);
            
            // Créer la catégorie parente
            $parent = Categorie::create([
                'nom' => $categorieData['nom'],
                'slug' => Str::slug($categorieData['nom']),
                'description' => $categorieData['description'],
                'icone' => $categorieData['icone'],
                'is_active' => true,
                'order' => $categorieData['order'] ?? 0,
            ]);
            
            // Créer les sous-catégories
            foreach ($enfants as $index => $enfantData) {
                Categorie::create([
                    'parent_id' => $parent->id,
                    'nom' => $enfantData['nom'],
                    'slug' => Str::slug($enfantData['nom']),
                    'description' => $enfantData['description'],
                    'icone' => $enfantData['icone'],
                    'is_active' => true,
                    'order' => $index + 1,
                ]);
            }
        }

        $this->command->info('✅ Catégories et sous-catégories créées avec succès !');
    }
}