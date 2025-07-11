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
       Schema::create('animes', function (Blueprint $table) {
    $table->id();
    $table->integer('mal_id')->unique();
    $table->string('title');
    $table->string('title_english')->nullable();
    $table->string('poster')->nullable();
    $table->string('background')->nullable();
    $table->text('genres')->nullable(); 
    $table->integer('progress')->default(0);
    $table->enum('type', ['work_in_progress', 'recommendation']); 
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
