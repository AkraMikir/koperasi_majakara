<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\JanjiTemuPinjaman;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use App\Models\JnsLokasiPerusahaan;
use App\Models\BuktiFoto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PinjamanSeeder extends Seeder
{
    /**
     * Seed pinjaman berjalan:
     * 1. Transfer: 3 bulan, Rp 1.000.000
     * 2. Tunai/Janji Temu: 6 bulan, Rp 3.000.000
     * 3. Transfer: 3 bulan, Rp 4.000.000 — diajuan 17 Jan 2026, cair 20 Jan (sudah berjalan)
     * 4. Transfer: 3 bulan, Rp 4.000.000 — dicairkan 10 Jan 2026 (angsuran pertama sudah lewat tempo)
     */
    public function run(): void
    {
        $nasabah = Nasabah::first();
        if (!$nasabah) {
            $this->command->warn('PinjamanSeeder: Tidak ada nasabah. Jalankan UserSeeder dan NasabahSeeder terlebih dahulu.');
            return;
        }

        $masterDenda = MasterDendaPinjaman::getDendaAktif();
        if (!$masterDenda) {
            $this->command->warn('PinjamanSeeder: Master denda pinjaman belum ada. Jalankan MasterDataSeeder.');
            return;
        }

        $lokasi = JnsLokasiPerusahaan::first();
        if (!$lokasi) {
            $this->command->warn('PinjamanSeeder: Lokasi perusahaan belum ada. Jalankan MasterDataSeeder.');
            return;
        }

        $bunga3 = MasterBungaPinjaman::getBungaByDurasi(3);
        $bunga6 = MasterBungaPinjaman::getBungaByDurasi(6);
        $bunga3 = MasterBungaPinjaman::getBungaByDurasi(3);
        $bunga6 = MasterBungaPinjaman::getBungaByDurasi(6);
        if (!$bunga3 || !$bunga6) {
            $this->command->warn('PinjamanSeeder: Master bunga untuk durasi 3 dan 6 bulan belum ada.');
            return;
        }

        // ---- Seeding MasterTujuanPinjaman ----
        $tujuanModal = \App\Models\MasterTujuanPinjaman::updateOrCreate(
            ['tujuan' => 'Modal Usaha'],
            ['status' => true, 'keterangan' => 'Untuk keperluan modal usaha nasabah']
        );
        $tujuanRenov = \App\Models\MasterTujuanPinjaman::updateOrCreate(
            ['tujuan' => 'Renovasi Rumah'],
            ['status' => true, 'keterangan' => 'Untuk biaya renovasi tempat tinggal']
        );

        DB::beginTransaction();
        try {
            $tglCair1 = Carbon::parse('2026-02-01');
            $tglCair2 = Carbon::parse('2026-02-02');
            $dateStr1 = $tglCair1->format('dmY'); // 01022026
            $dateStr2 = $tglCair2->format('dmY'); // 02022026

            // ---- 1. Pinjaman Transfer: 3 bulan, Rp 1.000.000 ----
            $idPengajuan1 = $dateStr1 . '0001PTFPNJ';
            $idPinjaman1 = $dateStr1 . '0001PTFDPNJM';
            $nominal1 = 1_000_000;
            $durasi1 = 3;
            $bungaPersen1 = (float) $bunga3->bunga_persen;
            $bungaRp1 = $nominal1 * ($bungaPersen1 / 100);
            $totalKewajiban1 = $nominal1 + $bungaRp1;
            $agsBulan1 = round($totalKewajiban1 / $durasi1, 2);

            PengajuanPinjaman::updateOrCreate(
                ['id' => $idPengajuan1],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'tgl_pengajuan' => $tglCair1->copy()->subDays(2),
                    'nominal' => $nominal1,
                    'jenis' => 'bulanan',
                    'durasi' => $durasi1,
                    'jenis_pencairan' => 'transfer',
                    'status' => '4',
                    'tgl_cair' => $tglCair1,
                    'bunga_persen' => $bungaPersen1,
                ]
            );

            PinjamanH::updateOrCreate(
                ['id' => $idPinjaman1],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'id_pengajuan' => $idPengajuan1,
                    'jumlah_pinjam' => $nominal1,
                    'lama_pinjam' => $durasi1,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen1,
                    'bunga_rp' => $bungaRp1,
                    'denda_persen' => $masterDenda->denda_persen,
                    'ags_bulan' => $agsBulan1,
                    'tgl_pinjam' => $tglCair1,
                    'lunas' => 'belum',
                ]
            );

            $this->createTempoBulanan($idPinjaman1, $totalKewajiban1, $durasi1, $dateStr1, '0001');

            // Bukti pencairan transfer (placeholder path)
            $idBukti1 = $dateStr1 . '0001PTFPNCR';
            BuktiFoto::updateOrCreate(
                ['id' => $idBukti1],
                [
                    'owner_id' => $idPengajuan1,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => 'bukti-pencairan-pinjaman/seed-transfer-placeholder.jpg',
                    'keterangan' => 'Bukti pencairan pinjaman (seeder - transfer)',
                ]
            );

            // ---- 2. Pinjaman Tunai/Janji Temu: 6 bulan, Rp 3.000.000 ----
            $idPengajuan2 = $dateStr2 . '0001PTNPNJ';
            $idJanjiTemu = $dateStr2 . '0001PTNJNJT';
            $idPinjaman2 = $dateStr2 . '0001PTNDPNJM';
            $nominal2 = 3_000_000;
            $durasi2 = 6;
            $bungaPersen2 = (float) $bunga6->bunga_persen;
            $bungaRp2 = $nominal2 * ($bungaPersen2 / 100);
            $totalKewajiban2 = $nominal2 + $bungaRp2;
            $agsBulan2 = round($totalKewajiban2 / $durasi2, 2);

            $tanggalJanjiTemu = $tglCair2->copy()->subDays(1);
            $waktuJanjiTemu = '10:00:00';

            PengajuanPinjaman::updateOrCreate(
                ['id' => $idPengajuan2],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanRenov->id,
                    'tgl_pengajuan' => $tanggalJanjiTemu->copy()->subDays(3),
                    'nominal' => $nominal2,
                    'jenis' => 'bulanan',
                    'durasi' => $durasi2,
                    'jenis_pencairan' => 'tunai',
                    'status' => '4',
                    'tgl_cair' => $tglCair2,
                    'bunga_persen' => $bungaPersen2,
                ]
            );

            JanjiTemuPinjaman::updateOrCreate(
                ['id' => $idJanjiTemu],
                [
                    'id_pengajuan' => $idPengajuan2,
                    'id_nasabah' => $nasabah->id,
                    'lokasi_temu' => $lokasi->id,
                    'nominal' => $nominal2,
                    'tanggal_janji_temu' => $tanggalJanjiTemu->format('Y-m-d') . ' ' . $waktuJanjiTemu,
                    'waktu_janji_temu' => $waktuJanjiTemu,
                    'status' => '2',
                    'keterangan_admin' => 'Janji temu selesai (seeder)',
                ]
            );

            PinjamanH::updateOrCreate(
                ['id' => $idPinjaman2],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanRenov->id,
                    'id_pengajuan' => $idPengajuan2,
                    'jumlah_pinjam' => $nominal2,
                    'lama_pinjam' => $durasi2,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen2,
                    'bunga_rp' => $bungaRp2,
                    'denda_persen' => $masterDenda->denda_persen,
                    'ags_bulan' => $agsBulan2,
                    'tgl_pinjam' => $tglCair2,
                    'lunas' => 'belum',
                ]
            );

            $this->createTempoBulanan($idPinjaman2, $totalKewajiban2, $durasi2, $dateStr2, '0001');

            $idBukti2 = $dateStr2 . '0001PTNPNCR';
            BuktiFoto::updateOrCreate(
                ['id' => $idBukti2],
                [
                    'owner_id' => $idJanjiTemu,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => 'bukti-pencairan-pinjaman/seed-tunai-placeholder.jpg',
                    'keterangan' => 'Bukti pencairan pinjaman janji temu (seeder)',
                ]
            );

            // ---- 3. Pinjaman Transfer: 3 bulan, Rp 4.000.000 — diajukan 17 Jan 2026, cair 20 Jan (sudah berjalan) ----
            $tglPengajuan3 = Carbon::parse('2026-01-17');
            $tglCair3 = Carbon::parse('2026-01-20');
            $dateStr3 = $tglCair3->format('dmY'); // 20012026
            $idPengajuan3 = $dateStr3 . '0001PTFPNJ';
            $idPinjaman3 = $dateStr3 . '0001PTFDPNJM';
            $nominal3 = 4_000_000;
            $durasi3 = 3;
            $bungaRp3 = $nominal3 * ($bungaPersen1 / 100);
            $totalKewajiban3 = $nominal3 + $bungaRp3;
            $agsBulan3 = round($totalKewajiban3 / $durasi3, 2);

            PengajuanPinjaman::updateOrCreate(
                ['id' => $idPengajuan3],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'tgl_pengajuan' => $tglPengajuan3,
                    'nominal' => $nominal3,
                    'jenis' => 'bulanan',
                    'durasi' => $durasi3,
                    'jenis_pencairan' => 'transfer',
                    'status' => '4',
                    'tgl_cair' => $tglCair3,
                    'bunga_persen' => $bungaPersen1,
                ]
            );
            PinjamanH::updateOrCreate(
                ['id' => $idPinjaman3],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'id_pengajuan' => $idPengajuan3,
                    'jumlah_pinjam' => $nominal3,
                    'lama_pinjam' => $durasi3,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen1,
                    'bunga_rp' => $bungaRp3,
                    'denda_persen' => $masterDenda->denda_persen,
                    'ags_bulan' => $agsBulan3,
                    'tgl_pinjam' => $tglCair3,
                    'lunas' => 'belum',
                ]
            );
            $this->createTempoBulanan($idPinjaman3, $totalKewajiban3, $durasi3, $dateStr3, '0001');
            BuktiFoto::updateOrCreate(
                ['id' => $dateStr3 . '0001PTFPNCR'],
                [
                    'owner_id' => $idPengajuan3,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => 'bukti-pencairan-pinjaman/seed-4jt-jan20-placeholder.jpg',
                    'keterangan' => 'Bukti pencairan pinjaman 4jt (seeder - diajukan 17 Jan, cair 20 Jan)',
                ]
            );

            // ---- 4. Pinjaman Transfer: 3 bulan, Rp 4.000.000 — dicairkan 10 Jan 2026 (angsuran pertama sudah lewat tempo) ----
            $tglCair4 = Carbon::parse('2026-01-10');
            $dateStr4 = $tglCair4->format('dmY'); // 10012026
            $idPengajuan4 = $dateStr4 . '0001PTFPNJ';
            $idPinjaman4 = $dateStr4 . '0001PTFDPNJM';
            $nominal4 = 4_000_000;
            $durasi4 = 3;
            $bungaRp4 = $nominal4 * ($bungaPersen1 / 100);
            $totalKewajiban4 = $nominal4 + $bungaRp4;
            $agsBulan4 = round($totalKewajiban4 / $durasi4, 2);

            PengajuanPinjaman::updateOrCreate(
                ['id' => $idPengajuan4],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'tgl_pengajuan' => $tglCair4->copy()->subDays(3),
                    'nominal' => $nominal4,
                    'jenis' => 'bulanan',
                    'durasi' => $durasi4,
                    'jenis_pencairan' => 'transfer',
                    'status' => '4',
                    'tgl_cair' => $tglCair4,
                    'bunga_persen' => $bungaPersen1,
                ]
            );
            PinjamanH::updateOrCreate(
                ['id' => $idPinjaman4],
                [
                    'id_anggota' => $nasabah->id,
                    'id_tujuan' => $tujuanModal->id,
                    'id_pengajuan' => $idPengajuan4,
                    'jumlah_pinjam' => $nominal4,
                    'lama_pinjam' => $durasi4,
                    'jenis' => 'bulanan',
                    'bunga' => $bungaPersen1,
                    'bunga_rp' => $bungaRp4,
                    'denda_persen' => $masterDenda->denda_persen,
                    'ags_bulan' => $agsBulan4,
                    'tgl_pinjam' => $tglCair4,
                    'lunas' => 'belum',
                ]
            );
            $this->createTempoBulanan($idPinjaman4, $totalKewajiban4, $durasi4, $dateStr4, '0001');
            BuktiFoto::updateOrCreate(
                ['id' => $dateStr4 . '0001PTFPNCR'],
                [
                    'owner_id' => $idPengajuan4,
                    'owner_fitur' => 'P',
                    'owner_trans' => 'PNCR',
                    'file_path' => 'bukti-pencairan-pinjaman/seed-4jt-jan10-placeholder.jpg',
                    'keterangan' => 'Bukti pencairan pinjaman 4jt (seeder - cair 10 Jan, angsuran lewat tempo)',
                ]
            );

            DB::commit();
            $this->command->info('PinjamanSeeder: 4 pinjaman berjalan dibuat (1jt/3bln transfer, 3jt/6bln tunai, 4jt/3bln transfer 17-Jan, 4jt/3bln transfer cair 10-Jan).');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate jadwal angsuran bulanan (sama logika dengan PinjamanController).
     */
    private function createTempoBulanan(string $pinjamanId, float $totalKewajiban, int $jumlahAngsuran, string $datePrefix, string $seqStart): void
    {
        $pinjaman = PinjamanH::find($pinjamanId);
        $tanggalMulai = Carbon::parse($pinjaman->tgl_pinjam);

        $angsuranBulanan = (int) floor($totalKewajiban / $jumlahAngsuran / 100) * 100;
        $akumulasi = $angsuranBulanan * ($jumlahAngsuran - 1);
        $angsuranTerakhir = (int) round($totalKewajiban - $akumulasi, 0);

        $suffix = 'PTTPNJM';
        $seq = (int) $seqStart;

        for ($i = 1; $i <= $jumlahAngsuran; $i++) {
            $tanggalJatuhTempo = $tanggalMulai->copy()->addMonths($i);
            $seqStr = str_pad($seq + $i - 1, 4, '0', STR_PAD_LEFT);
            $currentId = $datePrefix . $seqStr . $suffix;
            $jumlahTagihan = ($i < $jumlahAngsuran) ? $angsuranBulanan : $angsuranTerakhir;

            $statusBayar = $tanggalJatuhTempo->isPast() ? 'telat' : 'belum';
            
            $tempo = TempoPinjamanB::updateOrCreate(
                ['id' => $currentId],
                [
                    'pinjaman_id' => $pinjamanId,
                    'no_urut' => $i,
                    'tgl_jatuh_tempo' => $tanggalJatuhTempo,
                    'jumlah_tagihan' => $jumlahTagihan,
                    'jumlah_terbayar' => 0,
                    'denda' => 0,
                    'status_bayar' => $statusBayar,
                ]
            );

            // Jika statusnya telat, hitung denda
            if ($statusBayar === 'telat') {
                $denda = $tempo->hitungDenda();
                $tempo->update(['denda' => $denda]);
            }
        }
    }
}
