<?php

namespace Database\Seeders;

use App\Models\Devise;
use Illuminate\Database\Seeder;

class DeviseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== AU LIEU DE TRUNCATE, UTILISER DELETE =====
        Devise::query()->delete();

        $devises = [
            [
                'code' => 'USD',
                'nom' => 'Dollar Américain',
                'symbole' => '$',
                'taux_change' => 1.000000,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'CDF',
                'nom' => 'Franc Congolais',
                'symbole' => 'FC',
                'taux_change' => 2800.000000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'nom' => 'Euro',
                'symbole' => '€',
                'taux_change' => 0.920000,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'nom' => 'Livre Sterling',
                'symbole' => '£',
                'taux_change' => 0.790000,
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($devises as $devise) {
            Devise::create($devise);
        }
    }
}