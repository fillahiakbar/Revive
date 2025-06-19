<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('download_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mal_id');
            $table->string('url');
            $table->string('quality');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_links');
    }
};
