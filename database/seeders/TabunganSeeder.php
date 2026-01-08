<?php

namespace Database\Seeders;

use App\Models\BuktiFotoTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\Nasabah;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\PengajuanTabungan;
use App\Models\TransTabungan;
use App\Models\JnsLokasiPerusahaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel tabungan untuk mencegah duplikasi saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('trans_tabungan')->truncate();
        DB::table('tbl_janji_temu_tabungan')->truncate();
        DB::table('tbl_bukti_foto_tabungan')->truncate();
        DB::table('tbl_pengajuan_penarikan_tabungan')->truncate();
        DB::table('tbl_pengajuan_tabungan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $nasabahList = Nasabah::take(3)->get();
        $lokasi = JnsLokasiPerusahaan::first();

        if ($nasabahList->isEmpty() || !$lokasi) {
            // Pastikan seeder MasterDataSeeder, UserSeeder, dan NasabahSeeder sudah dijalankan.
            return;
        }

        // Pengajuan setoran tabungan untuk dua nasabah pertama
        foreach ($nasabahList->take(2) as $idx => $nasabah) {
            $pengajuan = PengajuanTabungan::create([
                'id_anggota' => $nasabah->id,
                'foto_bukti_tf' => "setoran{$idx}.jpg",
                'keterangan' => 'Setoran saldo awal',
                'status' => '2', // disetujui
            ]);

            // Bukti foto
            BuktiFotoTabungan::create([
                'id_pengajuan' => $pengajuan->id,
                'file_photo' => "bukti_setoran{$idx}.jpg",
                'jenis' => 'tabungan',
                'nominal' => 1000000 + ($idx * 500000),
                'keterangan' => 'Setoran via transfer',
            ]);

            // Janji temu (opsional) untuk verifikasi
            JanjiTemuTabungan::create([
                'id_pengajuan' => $pengajuan->id,
                'lokasi_temu' => $lokasi->id,
                'nominal' => 1000000 + ($idx * 500000),
                'tanggal_janji_temu' => now()->addDays(1 + $idx),
                'waktu_janji_temu' => now()->addDays(1 + $idx)->setTime(10 + $idx, 0),
            ]);

            // Transaksi setoran
            TransTabungan::create([
                'id_pengajuan_setor' => $pengajuan->id,
                'id_anggota' => $nasabah->id,
                'nominal' => 1000000 + ($idx * 500000),
                'keterangan' => 'Setoran awal tabungan',
                'jenis' => 'setoran',
                'via' => 'transfer',
                'tgl_transaksi' => now()->addDays($idx),
            ]);
        }

        // Pengajuan penarikan tabungan untuk nasabah ketiga (jika ada)
        if ($nasabahList->count() >= 3) {
            $nasabah = $nasabahList[2];

            $pengajuanTarik = PengajuanPenarikanTabungan::create([
                'id_anggota' => $nasabah->id,
                'tgl_pengajuan' => now()->addDays(2),
                'nominal' => 750000,
                'keterangan' => 'Penarikan sebagian saldo',
                'status' => '1', // menunggu
            ]);

            // Transaksi penarikan (menunggu) tanpa bukti setoran
            TransTabungan::create([
                'id_pengajuan_tarik' => $pengajuanTarik->id,
                'id_anggota' => $nasabah->id,
                'nominal' => 750000,
                'keterangan' => 'Pengajuan penarikan saldo',
                'jenis' => 'penarikan',
                'via' => 'transfer',
                'tgl_transaksi' => now()->addDays(2),
            ]);
        }
    }
}

