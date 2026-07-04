<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oeuvre_id')->constrained('oeuvres')->onDelete('cascade');
            $table->string('url', 255);
            $table->string('url_thumbnail', 255)->nullable();
            $table->string('titre')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('is_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};