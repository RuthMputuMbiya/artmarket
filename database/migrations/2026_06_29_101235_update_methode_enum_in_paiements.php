<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier la colonne methode pour accepter les nouvelles valeurs
        DB::statement("ALTER TABLE paiements MODIFY methode ENUM('carte', 'paypal', 'virement', 'stripe', 'shwary', 'mobile_money', 'orange_money', 'airtel_money', 'mpesa') DEFAULT 'shwary'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE paiements MODIFY methode ENUM('carte', 'paypal', 'virement', 'stripe', 'shwary', 'mobile_money') DEFAULT 'shwary'");
    }
};