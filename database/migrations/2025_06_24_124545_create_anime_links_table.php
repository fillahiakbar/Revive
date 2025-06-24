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
        Schema::create('anime_links', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('mal_id');
    $table->string('title');
    $table->string('poster')->nullable();
    $table->text('synopsis')->nullable();
    $table->string('season')->nullable();
    $table->string('year')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_links');
    }
};
