<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_janji_temu_universal AS 
            SELECT 
                UUID() AS id_view,
                'Tabungan' AS fitur,
                jtt.id AS id_asli,
                pt.id_anggota,
                u.nama AS nama_anggota,
                jtt.tanggal_janji_temu,
                jtt.waktu_janji_temu,
                jtt.nominal,
                jl.nama_lokasi AS lokasi,
                pt.keterangan,
                jtt.created_at
            FROM tbl_janji_temu_tabungan jtt
            JOIN tbl_pengajuan_tabungan pt ON jtt.id_pengajuan = pt.id
            JOIN tbl_nasabah n ON pt.id_anggota = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jtt.lokasi_temu = jl.id
            
            UNION ALL
            
            SELECT 
                UUID() AS id_view,
                'Pinjaman' AS fitur,
                jtp.id AS id_asli,
                pp.id_anggota,
                u.nama AS nama_anggota,
                jtp.tanggal_janji_temu,
                jtp.waktu_janji_temu,
                jtp.nominal,
                jl.nama_lokasi AS lokasi,
                jtp.keterangan,
                jtp.created_at
            FROM tbl_janji_temu_pinjaman jtp
            JOIN tbl_pengajuan_pinjaman pp ON jtp.id_pengajuan = pp.id
            JOIN tbl_nasabah n ON pp.id_anggota = n.id
            JOIN users u ON n.user_id = u.id
            JOIN jns_lokasi_perusahaan jl ON jtp.lokasi_temu = jl.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_janji_temu_universal");
    }
};
