<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('related_animes', function (Blueprint $table) {
        $table->unsignedBigInteger('anime_link_id')->nullable()->after('id');
        $table->foreign('anime_link_id')->references('id')->on('anime_links')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('related_animes', function (Blueprint $table) {
        $table->dropForeign(['anime_link_id']);
        $table->dropColumn('anime_link_id');
    });
}

};
