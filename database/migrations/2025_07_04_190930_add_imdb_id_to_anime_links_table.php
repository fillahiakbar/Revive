<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('anime_links', function (Blueprint $table) {
        $table->string('imdb_id')->nullable()->after('mal_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            //
        });
    }
};
