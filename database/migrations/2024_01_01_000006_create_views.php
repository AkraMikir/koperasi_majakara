<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // View untuk menggabungkan semua janji temu dari berbagai fitur
        DB::statement("
            CREATE OR REPLACE VIEW v_janji_temu_universal AS 
            SELECT 
                UUID() AS id_view,
                'Tabungan' AS fitur,
                jt.id AS id_asli,
                jt.id_nasabah AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                jt.keterangan_admin,
                jt.status,
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
                jt.id_nasabah AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                jt.keterangan_admin,
                jt.status,
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
                jt.id_nasabah AS id_anggota,
                u.nama AS nama_anggota,
                jt.tanggal_janji_temu,
                jt.waktu_janji_temu,
                jt.nominal,
                jl.nama_lokasi AS lokasi,
                jt.keterangan,
                jt.keterangan_admin,
                jt.status,
                jt.created_at
            FROM tbl_janji_temu_pembayaran_pinjaman jt
            JOIN tbl_nasabah n ON jt.id_nasabah = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jt.lokasi_temu = jl.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_janji_temu_universal");
    }
};
