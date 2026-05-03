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
        // Drop the old ENUM codec column and add a new VARCHAR column
        Schema::table('batch_links', function (Blueprint $table) {
            $table->dropColumn('codec');
        });

        Schema::table('batch_links', function (Blueprint $table) {
            $table->string('codec')->default('x264')->after('resolution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to ENUM
        Schema::table('batch_links', function (Blueprint $table) {
            $table->dropColumn('codec');
        });

        Schema::table('batch_links', function (Blueprint $table) {
            $table->enum('codec', ['x264', 'x265'])->nullable()->after('resolution');
        });
    }
};
