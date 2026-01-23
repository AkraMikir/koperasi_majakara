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

        // Pengajuan setoran dan penarikan tabungan untuk nasabah ketiga (Ahmad Fauzi)
        if ($nasabahList->count() >= 3) {
            $nasabah = $nasabahList[2]; // Ahmad Fauzi

            // Buat beberapa pengajuan setoran untuk Ahmad Fauzi (total 12.5 juta)
            $setoranData = [
                ['nominal' => 5000000, 'hari' => 5, 'keterangan' => 'Setoran pertama'],
                ['nominal' => 3000000, 'hari' => 3, 'keterangan' => 'Setoran kedua'],
                ['nominal' => 2500000, 'hari' => 1, 'keterangan' => 'Setoran ketiga'],
                ['nominal' => 2000000, 'hari' => 0, 'keterangan' => 'Setoran keempat'],
            ];

            foreach ($setoranData as $idx => $setoran) {
                $pengajuanSetor = PengajuanTabungan::create([
                    'id_anggota' => $nasabah->id,
                    'foto_bukti_tf' => "setoran_ahmad_{$idx}.jpg",
                    'keterangan' => $setoran['keterangan'] . ' untuk Ahmad Fauzi',
                    'status' => '2', // disetujui
                ]);

                // Bukti foto setoran
                BuktiFotoTabungan::create([
                    'id_pengajuan' => $pengajuanSetor->id,
                    'file_photo' => "bukti_setoran_ahmad_{$idx}.jpg",
                    'jenis' => 'tabungan',
                    'nominal' => $setoran['nominal'],
                    'keterangan' => 'Setoran via transfer',
                ]);

                // Transaksi setoran untuk Ahmad Fauzi
                TransTabungan::create([
                    'id_pengajuan_setor' => $pengajuanSetor->id,
                    'id_anggota' => $nasabah->id,
                    'nominal' => $setoran['nominal'],
                    'keterangan' => $setoran['keterangan'] . ' untuk Ahmad Fauzi',
                    'jenis' => 'setoran',
                    'via' => 'transfer',
                    'tgl_transaksi' => now()->subDays($setoran['hari']),
                ]);
            }

            // Pengajuan penarikan tabungan untuk Ahmad Fauzi
            $pengajuanTarik = PengajuanPenarikanTabungan::create([
                'id_anggota' => $nasabah->id,
                'tgl_pengajuan' => now(),
                'nominal' => 750000,
                'keterangan' => 'Penarikan sebagian saldo',
                'status' => '1', // menunggu
            ]);

                $pengajuanTarik = PengajuanPenarikanTabungan::create([
                    'id_anggota' => $nasabah->id,
                    'tgl_pengajuan' => now(),
                    'nominal' => 50000,
                    'keterangan' => 'Penarikan baru',
                    'status' => '1', // menunggu
            ]);

            // Jangan buat transaksi penarikan dulu, karena masih pending
            // Transaksi penarikan akan dibuat saat admin approve
        }
    }
}

