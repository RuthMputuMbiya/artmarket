<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('oeuvre_id')->constrained('oeuvres')->onDelete('cascade');
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('set null');
            $table->integer('note')->unsigned();
            $table->text('commentaire')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};