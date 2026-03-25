<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill anime_link_id for existing related_animes records
     * that have a mal_id matching an anime_links row.
     */
    public function up(): void
    {
        DB::statement("
            UPDATE related_animes
            SET anime_link_id = (
                SELECT anime_links.id
                FROM anime_links
                WHERE anime_links.mal_id = related_animes.mal_id
                LIMIT 1
            )
            WHERE related_animes.anime_link_id IS NULL
              AND related_animes.mal_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        // No rollback needed — this is a data backfill
    }
};
