<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('devise_id')->nullable()->constrained('devises')->onDelete('set null');
            $table->string('reference', 50)->unique();
            
            // ===== TOTAUX AVEC DEVISES =====
            $table->decimal('total_ht', 15, 2);
            $table->decimal('total_ttc', 15, 2);
            $table->decimal('frais_livraison', 15, 2)->default(0);
            $table->decimal('commission', 15, 2)->default(0);
            $table->decimal('total_cdf', 15, 2)->nullable();
            $table->decimal('total_usd', 15, 2)->nullable();
            
            // ===== STATUT =====
            $table->enum('statut', ['en_attente', 'en_cours', 'expediee', 'livree', 'annulee', 'rembourse'])->default('en_attente');
            
            // ===== ADRESSE LIVRAISON =====
            $table->string('adresse_livraison', 255);
            $table->string('ville_livraison', 100);
            $table->string('code_postal_livraison', 10);
            $table->string('pays_livraison', 50)->default('France');
            
            $table->text('notes')->nullable();
            $table->timestamp('date_livraison')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};