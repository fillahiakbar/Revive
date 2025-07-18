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
        Schema::create('batch_links', function (Blueprint $table) {
    $table->id();
    $table->foreignId('batch_id')->constrained()->onDelete('cascade');
    $table->string('resolution');
    $table->text('url_torrent')->nullable();
    $table->text('url_mega')->nullable();
    $table->text('url_gdrive')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_links');
    }
};
