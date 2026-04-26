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
        DB::statement("DROP VIEW IF EXISTS vw_saldo_owner_detail");
        DB::statement("
            CREATE VIEW vw_saldo_owner_detail AS
            SELECT 
                t.*,
                SUM(t.mutasi) OVER (ORDER BY t.created_at ASC, t.id ASC) as running_balance
            FROM (
                SELECT 
                    id, user_id, tipe, sumber, 
                    CASE WHEN tipe IN ('keluar', 'kirim_admin_hold') THEN -nominal_cash ELSE nominal_cash END as nominal_cash,
                    CASE WHEN tipe IN ('keluar', 'kirim_admin_hold') THEN -nominal_tf ELSE nominal_tf END as nominal_tf,
                    CASE WHEN tipe IN ('keluar', 'kirim_admin_hold') THEN -(nominal_cash + nominal_tf) ELSE (nominal_cash + nominal_tf) END as mutasi,
                    keterangan, created_at,
                    bukti_foto_cash, bukti_foto_tf
                FROM petty_cash_owner_transaksi
                UNION ALL
                SELECT 
                    id, user_id, 'keluar' as tipe, sumber, -nominal_cash, -nominal_tf, 
                    -(nominal_cash + nominal_tf) as mutasi,
                    keterangan, created_at,
                    CASE WHEN nominal_cash > 0 THEN bukti_foto ELSE NULL END as bukti_foto_cash,
                    CASE WHEN nominal_tf > 0 THEN bukti_foto ELSE NULL END as bukti_foto_tf
                FROM owner_withdrawals
            ) t
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_saldo_owner_detail");
    }
};
