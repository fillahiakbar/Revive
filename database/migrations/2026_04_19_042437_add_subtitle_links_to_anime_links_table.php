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
        Schema::table('anime_links', function (Blueprint $table) {
            $table->text('subtitle_url_mega')->nullable();
            $table->text('subtitle_url_gdrive')->nullable();
            $table->text('subtitle_url_pixeldrain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropColumn(['subtitle_url_mega', 'subtitle_url_gdrive', 'subtitle_url_pixeldrain']);
        });
    }
};
