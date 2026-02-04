<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * jns_angsuran_bulan: isi data 1-24 bulan untuk dropdown durasi.
     * tbl_pinjaman_h: tambah ags_minggu, saldo_lebih jika belum ada.
     */
    public function up(): void
    {
        // jns_angsuran_bulan: tambah kolom bulan (1-24) jika belum ada
        if (Schema::hasTable('jns_angsuran_bulan')) {
            if (!Schema::hasColumn('jns_angsuran_bulan', 'bulan')) {
                Schema::table('jns_angsuran_bulan', function (Blueprint $table) {
                    $table->unsignedTinyInteger('bulan')->nullable()->after('id');
                });
            }
            try {
                DB::statement('ALTER TABLE jns_angsuran_bulan MODIFY ket VARCHAR(5) NULL');
            } catch (\Throwable $e) {
                // ignore if already varchar
            }
            DB::table('jns_angsuran_bulan')->truncate();
            for ($i = 1; $i <= 24; $i++) {
                DB::table('jns_angsuran_bulan')->insert([
                    'bulan' => $i,
                    'ket' => (string) $i,
                    'aktif' => 'y',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // tbl_pinjaman_h: ags_minggu, saldo_lebih
        if (Schema::hasTable('tbl_pinjaman_h')) {
            if (!Schema::hasColumn('tbl_pinjaman_h', 'ags_minggu')) {
                Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
                    $table->decimal('ags_minggu', 15, 2)->nullable()->after('ags_bulan');
                });
            }
            if (!Schema::hasColumn('tbl_pinjaman_h', 'saldo_lebih')) {
                Schema::table('tbl_pinjaman_h', function (Blueprint $table) {
                    $table->decimal('saldo_lebih', 15, 2)->nullable()->default(0)->after('tgl_pinjam');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tbl_pinjaman_h')) {
            if (Schema::hasColumn('tbl_pinjaman_h', 'ags_minggu')) {
                Schema::table('tbl_pinjaman_h', fn (Blueprint $t) => $t->dropColumn('ags_minggu'));
            }
            if (Schema::hasColumn('tbl_pinjaman_h', 'saldo_lebih')) {
                Schema::table('tbl_pinjaman_h', fn (Blueprint $t) => $t->dropColumn('saldo_lebih'));
            }
        }
        if (Schema::hasTable('jns_angsuran_bulan') && Schema::hasColumn('jns_angsuran_bulan', 'bulan')) {
            Schema::table('jns_angsuran_bulan', fn (Blueprint $t) => $t->dropColumn('bulan'));
        }
    }
};
