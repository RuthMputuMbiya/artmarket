<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oeuvre_id')->constrained('oeuvres')->onDelete('cascade');
            
            // ===== MOUVEMENT DE STOCK =====
            $table->enum('type', ['entree', 'sortie', 'ajustement'])->default('entree');
            $table->integer('quantite');
            $table->integer('stock_avant');
            $table->integer('stock_apres');
            $table->text('motif')->nullable();
            
            // ===== RÉFÉRENCE =====
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};