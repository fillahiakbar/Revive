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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ref_code', 20)->nullable()->unique()->after('email');
        });

        \Illuminate\Support\Facades\DB::table('users')->whereNull('ref_code')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                do {
                    $code = \Illuminate\Support\Str::random(8);
                } while (\Illuminate\Support\Facades\DB::table('users')->where('ref_code', $code)->exists());

                \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['ref_code' => $code]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ref_code');
        });
    }
};
