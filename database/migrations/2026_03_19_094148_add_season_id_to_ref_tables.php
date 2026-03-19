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
        Schema::table('ref_clicks', function (Blueprint $table) {
            $table->foreignId('season_id')->nullable()->constrained('leaderboard_seasons')->onDelete('set null');
        });

        Schema::table('ref_stats', function (Blueprint $table) {
            $table->foreignId('season_id')->nullable()->constrained('leaderboard_seasons')->onDelete('cascade');
            // Remove old unique if it exists or just add new one
            $table->unique(['user_id', 'season_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ref_stats', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'season_id']);
            $table->dropForeign(['season_id']);
            $table->dropColumn('season_id');
        });

        Schema::table('ref_clicks', function (Blueprint $table) {
            $table->dropForeign(['season_id']);
            $table->dropColumn('season_id');
        });
    }
};
