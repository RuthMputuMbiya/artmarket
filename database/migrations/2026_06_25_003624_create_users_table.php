<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // ===== CLÉ ÉTRANGÈRE VERS ROLES =====
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            
            // ===== INFOS PERSONNELLES =====
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // ===== COORDONNÉES =====
            $table->string('telephone', 20)->nullable();
            $table->string('photo_profil')->nullable();
            $table->text('bio')->nullable();
            
            // ===== INFOS ARTISAN =====
            $table->string('entreprise')->nullable();
            $table->string('siret', 14)->nullable();
            
            // ===== ADRESSE =====
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('pays')->default('France');
            
            // ===== STATUT =====
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_login_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};