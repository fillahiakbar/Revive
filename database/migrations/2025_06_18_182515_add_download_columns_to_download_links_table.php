<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
    {
        Schema::table('download_links', function (Blueprint $table) {
            $table->string('torrent_url')->nullable();
            $table->string('mp4upload_url')->nullable();
            $table->string('gdrive_url')->nullable();
            $table->string('subtitle_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('download_links', function (Blueprint $table) {
            $table->dropColumn(['torrent_url', 'mp4upload_url', 'gdrive_url', 'subtitle_url']);
        });
    }
};
