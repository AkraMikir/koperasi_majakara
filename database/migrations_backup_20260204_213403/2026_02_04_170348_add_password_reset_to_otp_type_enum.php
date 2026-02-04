<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to add 'password_reset' value
        DB::statement("ALTER TABLE tbl_otp MODIFY COLUMN type ENUM('registration', 'transaction', 'login', 'pin', 'password_reset') NOT NULL DEFAULT 'registration'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'password_reset' from ENUM
        DB::statement("ALTER TABLE tbl_otp MODIFY COLUMN type ENUM('registration', 'transaction', 'login', 'pin') NOT NULL DEFAULT 'registration'");
    }
};
