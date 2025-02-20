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
        Schema::create('demandes_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->enum('type_fournisseur', ['moral', 'physique']); // Définir le type de fournisseur
            $table->string('email');
            $table->string('telephone');
            $table->timestamp('date_demande')->useCurrent();

            // Colonnes spécifiques aux fournisseurs moraux
            $table->string('nom_entreprise')->nullable();
            $table->string('code_postal')->nullable();

            // Colonnes spécifiques aux fournisseurs physiques
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('cin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_inscriptions');
    }
};
