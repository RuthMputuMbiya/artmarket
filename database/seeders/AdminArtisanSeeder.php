<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminArtisanSeeder extends Seeder
{
    public function run(): void
    {
        // ===== RÉCUPÉRER LES RÔLES AVEC VÉRIFICATION =====
        $roleAdmin = Role::where('slug', 'admin')->first();
        $roleArtisan = Role::where('slug', 'artisan')->first();

        // Si les rôles n'existent pas, les créer
        if (!$roleAdmin) {
            $roleAdmin = Role::create([
                'nom' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Administrateur du système avec tous les droits',
                'permissions' => json_encode(['all']),
                'is_default' => false,
            ]);
        }

        if (!$roleArtisan) {
            $roleArtisan = Role::create([
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
            ]);
        }

        // ===== CRÉER L'ADMIN =====
        User::updateOrCreate(
            ['email' => 'admin@artmarket.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('password123'),
                'role_id' => $roleAdmin->id,
                'telephone' => '0123456789',
                'statut' => 'actif',
                'is_verified' => true,
                'email_verified_at' => now(),
                'adresse' => '1 Rue de l\'Administration',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'pays' => 'France',
            ]
        );

        // ===== CRÉER LES ARTISANS =====
        $artisans = [
            [
                'name' => 'Artisan',
                'email' => 'artisan@artmarket.com',
                'password' => Hash::make('password123'),
                'telephone' => '0612345678',
                'entreprise' => 'Atelier Dupont',
                'siret' => '12345678901234',
                'bio' => 'Sculpteur sur bois depuis 20 ans.',
                'adresse' => '10 Rue des Artisans',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'pays' => 'France',
                'statut' => 'actif',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie.martin@artmarket.com',
                'password' => Hash::make('password123'),
                'telephone' => '0687654321',
                'entreprise' => 'Créations Martin',
                'siret' => '23456789012345',
                'bio' => 'Céramiste d\'art',
                'adresse' => '25 Rue des Potiers',
                'ville' => 'Lyon',
                'code_postal' => '69001',
                'pays' => 'France',
                'statut' => 'actif',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pierre Lefevre',
                'email' => 'pierre.lefevre@artmarket.com',
                'password' => Hash::make('password123'),
                'telephone' => '0678912345',
                'entreprise' => 'Bijouterie Lefevre',
                'siret' => '34567890123456',
                'bio' => 'Bijoutier joaillier',
                'adresse' => '5 Rue de la Joaillerie',
                'ville' => 'Bordeaux',
                'code_postal' => '33000',
                'pays' => 'France',
                'statut' => 'actif',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($artisans as $artisanData) {
            User::updateOrCreate(
                ['email' => $artisanData['email']],
                array_merge($artisanData, ['role_id' => $roleArtisan->id])
            );
        }
    }
}