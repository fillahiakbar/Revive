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
        // Add codec field to batch_links
        Schema::table('batch_links', function (Blueprint $table) {
            $table->enum('codec', ['x264', 'x265'])->nullable()->after('resolution');
        });

        // Add subtitle fields to anime_links
        Schema::table('anime_links', function (Blueprint $table) {
            $table->string('subtitle_url')->nullable()->after('imdb_id');
            $table->string('subtitle_file')->nullable()->after('subtitle_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_links', function (Blueprint $table) {
            $table->dropColumn('codec');
        });

        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropColumn(['subtitle_url', 'subtitle_file']);
        });
    }
};
