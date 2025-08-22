<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->foreignId('related_anime_group_id')
                ->nullable()
                ->constrained('relate_anime_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropForeign(['related_anime_group_id']);
            $table->dropColumn('related_anime_group_id');
        });
    }
};
