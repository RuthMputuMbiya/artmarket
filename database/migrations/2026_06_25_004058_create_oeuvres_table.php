<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oeuvres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('devise_id')->nullable()->constrained('devises')->onDelete('set null');
            
            // ===== INFORMATIONS =====
            $table->string('titre', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            
            // ===== PRIX =====
            $table->decimal('prix', 15, 2);
            $table->decimal('prix_promo', 15, 2)->nullable();
            $table->decimal('prix_cdf', 15, 2)->nullable();
            $table->decimal('prix_usd', 15, 2)->nullable();
            
            // ===== STOCK ET QUANTITÉ =====
            $table->integer('stock')->default(0);           // Stock total
            $table->integer('stock_min')->default(1);       // Stock minimum d'alerte
            $table->integer('stock_max')->nullable();       // Stock maximum
            $table->integer('quantite_par_commande')->default(1); // Quantité max par commande
            
            $table->string('materiaux')->nullable();
            $table->string('dimensions')->nullable();
            
            // ===== STATUT =====
            $table->enum('statut', ['brouillon', 'en_attente', 'publiee', 'archivee'])->default('brouillon');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oeuvres');
    }
};