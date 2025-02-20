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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email_verified_at');
            $table->string('adresse')->nullable();  // Colonne adresse
            $table->string('telephone')->nullable(); // Colonne téléphone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restauration des colonnes supprimées en cas de rollback
            $table->string('name');
            $table->timestamp('email_verified_at')->nullable();

            // Suppression des nouvelles colonnes ajoutées
            $table->dropColumn('adresse');
            $table->dropColumn('telephone');
        });
    }
};
