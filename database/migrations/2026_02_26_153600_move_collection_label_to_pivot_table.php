<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add collection_label to pivot table
        Schema::table('anime_link_collection', function (Blueprint $table) {
            $table->string('collection_label')->nullable()->after('sort_order');
        });

        // Remove collection_label from collections table
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('collection_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_link_collection', function (Blueprint $table) {
            $table->dropColumn('collection_label');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->string('collection_label')->nullable()->after('poster');
        });
    }
};
