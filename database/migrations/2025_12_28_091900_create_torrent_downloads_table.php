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
        Schema::create('torrent_downloads', function (Blueprint $table) {
	    $table->id();
	    $table->foreignId('user_id')->constrained()->onDelete('cascade');
	    $table->string('filename');
	    $table->ipAddress('ip_address');
	    $table->text('user_agent')->nullable();
	    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torrent_downloads');
    }
};
