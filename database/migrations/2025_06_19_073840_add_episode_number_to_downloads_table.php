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
    Schema::table('downloads', function (Blueprint $table) {
        $table->integer('episode_number')->nullable()->after('mal_id');
    });
}

public function down()
{
    Schema::table('downloads', function (Blueprint $table) {
        $table->dropColumn('episode_number');
    });
}
};
