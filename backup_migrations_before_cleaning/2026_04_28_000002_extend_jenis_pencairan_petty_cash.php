<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ALTER ENUM dengan menambahkan nilai baru (tidak destructive)
        DB::statement("
            ALTER TABLE tbl_pencairan_deposito
            MODIFY COLUMN jenis_pencairan ENUM('rek_nasabah', 'saldo_tabungan', 'petty_cash_operator')
            NOT NULL DEFAULT 'rek_nasabah'
        ");
    }

    public function down(): void
    {
        // Kembalikan ke nilai semula (data 'petty_cash_operator' harus sudah dihapus)
        DB::statement("
            ALTER TABLE tbl_pencairan_deposito
            MODIFY COLUMN jenis_pencairan ENUM('rek_nasabah', 'saldo_tabungan')
            NOT NULL DEFAULT 'rek_nasabah'
        ");
    }
};
