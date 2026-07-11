<?php

/**
 * Backfill id_tujuan di tbl_pinjaman_h dari tbl_pengajuan_pinjaman.
 * 
 * Jalankan dari root project:
 *   php scratch/backfill_id_tujuan.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Backfill id_tujuan tbl_pinjaman_h ===\n\n";

// Cek dulu berapa record yang akan diupdate
$rows = DB::table('tbl_pinjaman_h as ph')
    ->join('tbl_pengajuan_pinjaman as pp', 'ph.id_pengajuan', '=', 'pp.id')
    ->whereNull('ph.id_tujuan')
    ->whereNotNull('pp.id_tujuan')
    ->whereNotNull('ph.id_pengajuan')
    ->select('ph.id', 'pp.id_tujuan')
    ->get();

echo "Ditemukan: " . $rows->count() . " record dengan id_tujuan null\n\n";

if ($rows->count() === 0) {
    echo "Tidak ada yang perlu diupdate.\n";
    exit(0);
}

DB::beginTransaction();

try {
    foreach ($rows as $row) {
        DB::table('tbl_pinjaman_h')
            ->where('id', $row->id)
            ->update(['id_tujuan' => $row->id_tujuan]);

        echo "Updated: {$row->id} -> id_tujuan = {$row->id_tujuan}\n";
    }

    DB::commit();
    echo "\nSelesai. Total diupdate: " . $rows->count() . " record.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\nGagal: " . $e->getMessage() . "\n";
    exit(1);
}
