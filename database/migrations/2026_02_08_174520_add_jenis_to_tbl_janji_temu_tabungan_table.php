<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration//adwda   
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tbl_janji_temu_tabungan', 'jenis')) {
            Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
                $table->enum('jenis', ['setoran', 'penarikan'])->default('setoran')->after('lokasi_temu');
            });
        }

        // Use DB::unprepared for raw SQL view creation
        /*
        DB::unprepared("
            CREATE OR REPLACE VIEW v_janji_temu_universal AS 
            ...
        ");
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tbl_janji_temu_tabungan', 'jenis')) {
            Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
                $table->dropColumn('jenis');
            });
        }

        DB::unprepared("
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
};
