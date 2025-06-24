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
  Schema::create('sliders', function (Blueprint $table) {
    $table->id();
    $table->string('title')->nullable();
    $table->text('description')->nullable();
    $table->string('image');
    $table->string('type')->nullable();       // Contoh: TV, OVA, etc
    $table->string('duration')->nullable();   // Contoh: 24m
    $table->string('year')->nullable();       // Contoh: 2024
    $table->string('quality')->nullable();    // Contoh: HD, 1080p
    $table->string('episodes')->nullable();   // Contoh: 12 Eps
    $table->boolean('is_active')->default(true);
    $table->integer('order')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
