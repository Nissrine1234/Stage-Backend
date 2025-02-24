<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appels_offres', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id'); // Ajout de la clé étrangère pour l'utilisateur
            $table->unsignedBigInteger('demande_id')->after('user_id'); // Clé étrangère pour la demande

            // Définir les clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('demande_id')->references('id')->on('demandes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appels_offres', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['demande_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('demande_id');
        });
    }
};