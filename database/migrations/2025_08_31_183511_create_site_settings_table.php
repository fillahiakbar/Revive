<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('most_visited_period', ['weekly', 'monthly', 'all_time'])->default('all_time');
            $table->timestamps();
        });

        // Seed default 1 baris
        DB::table('site_settings')->insert([
            'most_visited_period' => 'all_time',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Index bantu untuk filter berdasarkan waktu (dipakai di scope period)
        Schema::table('anime_links', function (Blueprint $table) {
            $table->index('created_at', 'anime_links_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('anime_links', function (Blueprint $table) {
            $table->dropIndex('anime_links_created_at_idx');
        });

        Schema::dropIfExists('site_settings');
    }
};
