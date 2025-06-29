<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('likes')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};

