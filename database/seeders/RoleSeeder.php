<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ===== AU LIEU DE TRUNCATE, UTILISER DELETE =====
        // Supprimer les anciens rôles sans truncate
        Role::query()->delete();

        // Créer les rôles
        $roles = [
            [
                'nom' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Administrateur du système avec tous les droits',
                'permissions' => json_encode(['all']),
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Artisan',
                'slug' => 'artisan',
                'description' => 'Artisan peut publier et gérer ses œuvres',
                'permissions' => json_encode([
                    'create_oeuvre',
                    'edit_oeuvre',
                    'delete_oeuvre',
                    'view_oeuvre',
                    'manage_commande',
                    'manage_stock',
                    'view_statistiques'
                ]),
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Client',
                'slug' => 'client',
                'description' => 'Client peut acheter des œuvres',
                'permissions' => json_encode([
                    'view_oeuvre',
                    'add_to_cart',
                    'pass_commande',
                    'view_commande',
                    'add_avis',
                    'add_favoris'
                ]),
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}