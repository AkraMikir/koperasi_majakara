<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $limits = DB::table('tbl_limit_pinjaman')->get();
        foreach ($limits as $limit) {
            $activeLoansSum = DB::table('tbl_pinjaman_h')
                ->where('id_anggota', $limit->id_nasabah)
                ->where('lunas', 'belum')
                ->sum('jumlah_pinjam');
            
            DB::table('tbl_limit_pinjaman')
                ->where('id', $limit->id)
                ->update(['nominal_terpakai' => $activeLoansSum ?: 0.00]);
        }
    }

    public function down(): void
    {
        // Data fix tidak membutuhkan rollback
    }
};
