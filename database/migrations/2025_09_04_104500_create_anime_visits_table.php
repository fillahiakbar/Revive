<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anime_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_link_id')->constrained('anime_links')->cascadeOnDelete();
            $table->date('visited_date');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();

            $table->unique(['anime_link_id', 'visited_date'], 'anime_visits_unique');
            $table->index('visited_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anime_visits');
    }
};
