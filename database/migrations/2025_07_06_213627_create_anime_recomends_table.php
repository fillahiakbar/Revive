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
        Schema::create('anime_recomends', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('mal_id')->unique();
    $table->string('title');
    $table->string('title_english')->nullable();
    $table->string('poster');
    $table->string('genres');
    $table->string('background'); // diinput manual
    $table->unsignedTinyInteger('progress')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_recomends');
    }
};
