<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // 1. View v_janji_temu_universal
        DB::statement("
            CREATE OR REPLACE VIEW v_janji_temu_universal AS 
            SELECT 
                UUID() AS id_view,
                'Tabungan' AS fitur,
                jt.id AS id_asli,
                NULL AS id_pengajuan,
                jt.id_nasabah AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                jt.keterangan_admin,
                jt.status,
                CAST(jt.jenis AS CHAR(50)) AS jenis,
                jt.created_at
            FROM tbl_janji_temu_tabungan jt
            JOIN tbl_nasabah n ON jt.id_nasabah = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
            
            UNION ALL
            
            SELECT 
                UUID() AS id_view,
                'Pinjaman' AS fitur,
                jt.id AS id_asli,
                jt.id_pengajuan AS id_pengajuan,
                jt.id_nasabah AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                IFNULL(jt.keterangan_admin, '') AS keterangan_admin,
                IFNULL(jt.status, '1') AS status,
                CAST('pengajuan' AS CHAR(50)) AS jenis,
                jt.created_at
            FROM tbl_janji_temu_pinjaman jt
            JOIN tbl_nasabah n ON jt.id_nasabah = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
            
            UNION ALL
            
            SELECT 
                UUID() AS id_view,
                'Pembayaran Pinjaman' AS fitur,
                jt.id AS id_asli,
                jt.id_pengajuan AS id_pengajuan,
                p.id_anggota AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                jt.keterangan_admin AS keterangan_admin,
                jt.status AS status,
                CAST('other' AS CHAR(50)) AS jenis,
                jt.created_at
            FROM tbl_janji_temu_pembayaran_pinjaman jt
            JOIN tbl_pengajuan_pembayaran_pinjaman p ON jt.id_pengajuan = p.id
            JOIN tbl_nasabah n ON p.id_anggota = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
        ");

        // 2. View vw_saldo_owner_detail
        DB::statement("
            CREATE OR REPLACE VIEW vw_saldo_owner_detail AS 
            SELECT 
                t.id AS id,
                t.user_id AS user_id,
                t.tipe AS tipe,
                t.sumber AS sumber,
                t.nominal_cash AS nominal_cash,
                t.nominal_tf AS nominal_tf,
                t.mutasi AS mutasi,
                t.keterangan AS keterangan,
                t.created_at AS created_at,
                t.bukti_foto_cash AS bukti_foto_cash,
                t.bukti_foto_tf AS bukti_foto_tf,
                SUM(t.mutasi) OVER (ORDER BY t.created_at, t.id) AS running_balance 
            FROM (
                SELECT 
                    petty_cash_owner_transaksi.id AS id,
                    petty_cash_owner_transaksi.user_id AS user_id,
                    petty_cash_owner_transaksi.tipe AS tipe,
                    petty_cash_owner_transaksi.sumber AS sumber,
                    (CASE WHEN petty_cash_owner_transaksi.tipe IN ('keluar','kirim_admin_hold') THEN -(petty_cash_owner_transaksi.nominal_cash) ELSE petty_cash_owner_transaksi.nominal_cash END) AS nominal_cash,
                    (CASE WHEN petty_cash_owner_transaksi.tipe IN ('keluar','kirim_admin_hold') THEN -(petty_cash_owner_transaksi.nominal_tf) ELSE petty_cash_owner_transaksi.nominal_tf END) AS nominal_tf,
                    (CASE WHEN petty_cash_owner_transaksi.tipe IN ('keluar','kirim_admin_hold') THEN -((petty_cash_owner_transaksi.nominal_cash + petty_cash_owner_transaksi.nominal_tf)) ELSE (petty_cash_owner_transaksi.nominal_cash + petty_cash_owner_transaksi.nominal_tf) END) AS mutasi,
                    petty_cash_owner_transaksi.keterangan AS keterangan,
                    petty_cash_owner_transaksi.created_at AS created_at,
                    petty_cash_owner_transaksi.bukti_foto_cash AS bukti_foto_cash,
                    petty_cash_owner_transaksi.bukti_foto_tf AS bukti_foto_tf 
                FROM petty_cash_owner_transaksi 
                
                UNION ALL 
                
                SELECT 
                    owner_withdrawals.id AS id,
                    owner_withdrawals.user_id AS user_id,
                    'keluar' AS tipe,
                    owner_withdrawals.sumber AS sumber,
                    -(owner_withdrawals.nominal_cash) AS nominal_cash,
                    -(owner_withdrawals.nominal_tf) AS nominal_tf,
                    -((owner_withdrawals.nominal_cash + owner_withdrawals.nominal_tf)) AS mutasi,
                    owner_withdrawals.keterangan AS keterangan,
                    owner_withdrawals.created_at AS created_at,
                    (CASE WHEN owner_withdrawals.nominal_cash > 0 THEN owner_withdrawals.bukti_foto ELSE NULL END) AS bukti_foto_cash,
                    (CASE WHEN owner_withdrawals.nominal_tf > 0 THEN owner_withdrawals.bukti_foto ELSE NULL END) AS bukti_foto_tf 
                FROM owner_withdrawals
            ) t
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        DB::statement("DROP VIEW IF EXISTS vw_saldo_owner_detail");
        DB::statement("DROP VIEW IF EXISTS v_janji_temu_universal");
    }
};
