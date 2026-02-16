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
        Schema::table('related_animes', function (Blueprint $table) {
            $table->string('relation_title', 64)
                  ->nullable()
                  ->after('title_english')
                  ->comment('Season, Movie, OVA, Special, dll.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('related_animes', function (Blueprint $table) {
            $table->dropColumn('relation_title');
        });
    }
};
