<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force the column to be a string
        DB::statement("ALTER TABLE payment_methods MODIFY COLUMN type VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to enum if absolutely necessary, but string is safer general purpose
        // DB::statement("ALTER TABLE payment_methods MODIFY COLUMN type ENUM('link', 'manual') NOT NULL");
    }
};
