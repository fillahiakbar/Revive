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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('poster')->nullable();
            $table->timestamps();
        });

        Schema::create('anime_link_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anime_link_id')->constrained('anime_links')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Optional: Index for faster lookups
            $table->index(['collection_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_link_collection');
        Schema::dropIfExists('collections');
    }
};
