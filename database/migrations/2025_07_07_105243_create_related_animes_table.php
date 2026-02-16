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
        Schema::create('related_animes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('relate_anime_group_id')->constrained()->onDelete('cascade');
    $table->unsignedBigInteger('mal_id');
    $table->string('poster');
    $table->string('title');
    $table->string('title_english')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_animes');
    }
};
