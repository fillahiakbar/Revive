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
    Schema::table('batch_links', function (Blueprint $table) {
        $table->text('url_megaHard')->nullable()->after('url_gdrive');
        $table->text('url_gdriveHard')->nullable()->after('url_megaHard');
    });
}

public function down(): void
{
    Schema::table('batch_links', function (Blueprint $table) {
        $table->dropColumn(['url_megaHard', 'url_gdriveHard']);
    });
}

};
