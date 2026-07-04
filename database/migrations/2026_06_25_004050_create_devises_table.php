<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devises', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();        // USD, EUR, CDF, GBP
            $table->string('nom', 50);                  // Dollar US, Euro, Franc Congolais
            $table->string('symbole', 10);              // $, €, FC, £
            $table->decimal('taux_change', 15, 6)->default(1.000000);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devises');
    }
};