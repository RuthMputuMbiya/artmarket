<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nom', 100);
            $table->string('adresse', 255);
            $table->string('complement', 255)->nullable();
            $table->string('ville', 100);
            $table->string('code_postal', 10);
            $table->string('pays', 50)->default('France');
            $table->string('telephone', 20)->nullable();
            $table->boolean('is_principal')->default(false);
            $table->enum('type', ['facturation', 'livraison'])->default('livraison');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adresses');
    }
};