<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->enum('statut', ['non expédié', 'en cours', 'livré'])->default('non expédié'); // Statut de la livraison
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};
