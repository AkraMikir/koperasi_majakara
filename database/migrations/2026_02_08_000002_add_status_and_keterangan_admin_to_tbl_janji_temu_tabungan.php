<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//ikkkkm
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            // Add status if it doesn't exist
            if (!Schema::hasColumn('tbl_janji_temu_tabungan', 'status')) {
                $table->enum('status', ['1', '2', '3'])->default('1')->after('keterangan'); 
                // 1=Menunggu, 2=Selesai, 3=Batal
            }

            // Add keterangan_admin if it doesn't exist
            if (!Schema::hasColumn('tbl_janji_temu_tabungan', 'keterangan_admin')) {
                $table->text('keterangan_admin')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_janji_temu_tabungan', 'keterangan_admin')) {
                $table->dropColumn('keterangan_admin');
            }
            if (Schema::hasColumn('tbl_janji_temu_tabungan', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
