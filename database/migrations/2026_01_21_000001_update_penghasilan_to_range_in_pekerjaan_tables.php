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
        // Update tbl_pekerjaan_temp
        Schema::table('tbl_pekerjaan_temp', function (Blueprint $table) {
            $table->string('penghasilan', 50)->nullable()->change();
        });

        // Update tbl_pekerjaan
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            $table->string('penghasilan', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tbl_pekerjaan_temp
        Schema::table('tbl_pekerjaan_temp', function (Blueprint $table) {
            $table->decimal('penghasilan', 10, 2)->nullable()->change();
        });

        // Revert tbl_pekerjaan
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            $table->decimal('penghasilan', 10, 2)->nullable()->change();
        });
    }
};
