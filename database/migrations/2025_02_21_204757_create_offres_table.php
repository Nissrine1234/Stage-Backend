<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appel_offre_id')->constrained('appels_offres')->onDelete('cascade');
            $table->foreignId('fournisseur_physique_id')->nullable()->constrained('fournisseurs_physiques')->onDelete('cascade');
            $table->foreignId('fournisseur_morale_id')->nullable()->constrained('fournisseurs_morales')->onDelete('cascade');
            $table->decimal('montant', 10, 2); // Montant de l'offre
            $table->integer('delai'); // Délai en nombre de jours
            $table->enum('status', ['en attente', 'accepté', 'refusé'])->default('en attente'); // Statut de l'offre
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
