<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration//ikkkkm
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_otp', function (Blueprint $table) {
            // 1. Drop Foreign Key Constraint
            $table->dropForeign(['user_id']);
        });

        // 2. Modify Column to be Nullable (MySQL specific)
        DB::statement('ALTER TABLE tbl_otp MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('tbl_otp', function (Blueprint $table) {
            // 3. Re-add Foreign Key Constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_otp', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        DB::statement('ALTER TABLE tbl_otp MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('tbl_otp', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
