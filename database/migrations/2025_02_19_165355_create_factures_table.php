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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade'); // Relation avec la demande
            $table->decimal('montant', 10, 2); 
            $table->date('date_emission'); 
            $table->date('date_paiement'); 
            $table->enum('statut', ['en attente', 'payée', 'annulée'])->default('en attente'); // Statut de la facture

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
