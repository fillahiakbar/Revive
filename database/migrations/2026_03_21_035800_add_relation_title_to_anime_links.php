<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add relation_title to anime_links
        Schema::table('anime_links', function (Blueprint $table) {
            $table->string('relation_title', 64)
                  ->nullable()
                  ->after('related_anime_group_id')
                  ->comment('Season 1, Movie, OVA, Special, dll.');
        });

        // 2. Migrate existing relation_title from related_animes to anime_links
        DB::statement("
            UPDATE anime_links
            SET relation_title = (
                SELECT related_animes.relation_title
                FROM related_animes
                WHERE related_animes.mal_id = anime_links.mal_id
                  AND related_animes.relation_title IS NOT NULL
                LIMIT 1
            )
            WHERE anime_links.related_anime_group_id IS NOT NULL
              AND anime_links.relation_title IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropColumn('relation_title');
        });
    }
};
