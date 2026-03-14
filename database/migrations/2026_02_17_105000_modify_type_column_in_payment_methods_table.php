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
        // Using raw SQL to avoid doctrine/dbal dependency issues with ENUMs
        DB::statement("ALTER TABLE payment_methods MODIFY COLUMN type VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum if needed (be careful with data loss if existing data doesn't match)
        // For safety, we can just leave it as string or revert to enum if we are sure
         DB::statement("ALTER TABLE payment_methods MODIFY COLUMN type ENUM('link', 'manual') NOT NULL");
    }
};
