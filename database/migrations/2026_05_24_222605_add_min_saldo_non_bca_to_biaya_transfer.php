<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biaya_transfer', function (Blueprint $table) {
            // Minimum saldo tabungan nasabah non-BCA agar dapat mengakses fitur premium
            // (pinjaman, deposito, gadai). Jika 0 maka nasabah non-BCA selalu di-lock.
            $table->decimal('min_saldo_non_bca', 15, 2)->default(0)->after('biaya_admin');
        });
    }

    public function down(): void
    {
        Schema::table('biaya_transfer', function (Blueprint $table) {
            $table->dropColumn('min_saldo_non_bca');
        });
    }
};
