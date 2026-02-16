<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->decimal('mal_score', 3, 1)->nullable();
            $table->decimal('imdb_score', 3, 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropColumn(['mal_score', 'imdb_score']);
        });
    }
};