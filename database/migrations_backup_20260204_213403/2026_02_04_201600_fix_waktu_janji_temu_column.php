<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Change waktu_janji_temu from timestamp to time
            $table->time('waktu_janji_temu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Revert back to timestamp
            $table->timestamp('waktu_janji_temu')->change();
        });
    }
};
