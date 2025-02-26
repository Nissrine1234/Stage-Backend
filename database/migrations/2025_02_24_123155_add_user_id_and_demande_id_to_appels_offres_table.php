<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('appels_offres', function (Blueprint $table) {
            if (!Schema::hasColumn('appels_offres', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('appels_offres', 'demande_id')) {
                $table->unsignedBigInteger('demande_id')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('appels_offres', function (Blueprint $table) {
            if (Schema::hasColumn('appels_offres', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('appels_offres', 'demande_id')) {
                $table->dropColumn('demande_id');
            }
        });
    }
};
