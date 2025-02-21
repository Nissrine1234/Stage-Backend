<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la table des services d'achats
        Schema::table('service_achats', function (Blueprint $table) {
            $table->string('nom_service')->after('id');
            $table->string('responsable')->after('nom_service');
            $table->string('email')->after('responsable');
        });

        // Créer la table pivot pour relier les services d'achats aux appels d'offres
        Schema::create('appel_offre_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_achat_id')->constrained('service_achats')->onDelete('cascade');
            $table->foreignId('appel_offre_id')->constrained('appels_offres')->onDelete('cascade');
            $table->timestamps();
        });

        // Créer la table pivot pour relier les services aux fournisseurs physiques
        Schema::create('fournisseur_physique_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_achat_id')->constrained('service_achats')->onDelete('cascade');
            $table->foreignId('fournisseur_physique_id')->constrained('fournisseurs_physiques')->onDelete('cascade');
            $table->timestamps();
        });

        // Créer la table pivot pour relier les services aux fournisseurs morales
        Schema::create('fournisseur_morale_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_achat_id')->constrained('service_achats')->onDelete('cascade');
            $table->foreignId('fournisseur_morale_id')->constrained('fournisseurs_morales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appel_offre_service');
        Schema::dropIfExists('fournisseur_physique_service');
        Schema::dropIfExists('fournisseur_morale_service');

        Schema::table('service_achats', function (Blueprint $table) {
            $table->dropColumn(['nom_service', 'responsable', 'email']);
        });
    }
};
