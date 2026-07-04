<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            
            // ===== CLÉS ÉTRANGÈRES =====
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('devise_id')->nullable()->constrained('devises')->onDelete('set null');
            
            // ===== INFOS CLIENT =====
            $table->string('client_nom')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_telephone')->nullable(); // 📱 Numéro de téléphone
            
            // ===== RÉFÉRENCES =====
            $table->string('reference')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('shwary_transaction_id')->nullable(); // ID Shwary
            
            // ===== MONTANTS =====
            $table->decimal('montant', 15, 2);
            $table->decimal('montant_cdf', 15, 2)->nullable();
            $table->decimal('montant_usd', 15, 2)->nullable();
            $table->decimal('frais', 15, 2)->default(0);
            
            // ===== STATUT =====
            $table->enum('statut', [
                'en_attente',    // En attente de paiement
                'paye',          // Paiement réussi
                'echoue',        // Paiement échoué
                'annule',        // Annulé par l'utilisateur
                'rembourse',     // Remboursé
                'en_cours'       // En cours de traitement
            ])->default('en_attente');
            
            // ===== MÉTHODE =====
            $table->enum('methode', [
                'carte', 
                'paypal', 
                'virement', 
                'stripe', 
                'shwary', 
                'mobile_money'
            ])->default('shwary');
            
            // ===== DONNÉES =====
            $table->json('payload')->nullable();        // Données brutes Shwary
            $table->json('callback_data')->nullable();  // Données de callback
            
            // ===== NOTIFICATIONS =====
            $table->boolean('sms_envoye')->default(false);
            $table->boolean('email_envoye')->default(false);
            $table->timestamp('sms_envoye_at')->nullable();
            $table->timestamp('email_envoye_at')->nullable();
            
            $table->timestamp('date_paiement')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};