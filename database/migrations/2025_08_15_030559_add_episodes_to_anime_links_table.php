<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('anime_links', function (Blueprint $table) {
        $table->unsignedBigInteger('click_count')->default(0)->after('episodes');
    });
}

public function down(): void
{
    Schema::table('anime_links', function (Blueprint $table) {
        $table->dropColumn('click_count');
    });
}
};
