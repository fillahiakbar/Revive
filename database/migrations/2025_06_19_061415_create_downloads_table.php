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
Schema::create('downloads', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('mal_id'); // id dari MyAnimeList via Jikan
    $table->string('title')->nullable();  // Judul (boleh auto isi dari API)
    $table->json('links');                // Menyimpan semua link
    $table->boolean('is_spotlight')->default(false); // untuk fitur spotlight
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
