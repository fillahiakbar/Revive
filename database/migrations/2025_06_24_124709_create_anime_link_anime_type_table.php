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
        Schema::create('anime_link_anime_type', function (Blueprint $table) {
    $table->id();
    $table->foreignId('anime_link_id')->constrained()->onDelete('cascade');
    $table->foreignId('anime_type_id')->constrained()->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_link_anime_type');
    }
};
