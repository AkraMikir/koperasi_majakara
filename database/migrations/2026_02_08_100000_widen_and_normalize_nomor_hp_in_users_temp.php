<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Perlebar nomor_hp agar format 08xxx/62xxx tidak terpotong (char 12 → varchar 20).
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users_temp MODIFY nomor_hp VARCHAR(20) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users_temp MODIFY nomor_hp CHAR(12) NULL');
    }
};
