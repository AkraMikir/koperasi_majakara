<?php

namespace Database\Seeders;

use App\Models\DepositoH;
use App\Models\JnsTenorDeposito;
use App\Models\Nasabah;
use App\Models\PencairanDeposito;
use App\Models\PengajuanDeposito;
use App\Models\TransDeposito;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositoTestingSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada nasabah
        $nasabahs = Nasabah::take(3)->get();
        if ($nasabahs->count() < 1) {
            $this->command->warn('Belum ada data nasabah. Silakan seed nasabah terlebih dahulu.');
            return;
        }

        // Pastikan ada admin
        $admin = User::whereIn('role', ['admin_utama', 'admin_operasional'])->first() ?? User::first();

        // Pastikan ada tenor
        $tenors = JnsTenorDeposito::where('aktif', 'y')->get();
        if ($tenors->count() < 1) {
            $this->command->warn('Belum ada data jenis tenor. Silakan seed JnsTenorDepositoSeeder terlebih dahulu.');
            return;
        }

        $budiUser = User::where('nama', 'like', '%Budi Santoso%')->first();
        if ($budiUser && $budiUser->nasabah) {
            $nasabah1 = $budiUser->nasabah;
            $nasabah2 = $budiUser->nasabah;
            $nasabah3 = $budiUser->nasabah;
        } else {
            $this->command->warn('Nasabah Budi Santoso tidak ditemukan, menggunakan data urutan awal.');
            $nasabah1 = $nasabahs[0];
            $nasabah2 = $nasabahs[1] ?? $nasabah1;
            $nasabah3 = $nasabahs[2] ?? $nasabah1;
        }

        $tenor1 = $tenors->where('tenor_bulan', 1)->first() ?? $tenors->first();
        $tenor3 = $tenors->where('tenor_bulan', 3)->first() ?? $tenors->first();
        $tenor6 = $tenors->where('tenor_bulan', 6)->first() ?? $tenors->first();

        DB::beginTransaction();
        try {
            // 1. Pengajuan Pending (TF)
            PengajuanDeposito::create([
                'id_nasabah'   => $nasabah1->id,
                'nominal'      => 5000000,
                'tenor_id'     => $tenor3->id,
                'metode_setor' => 'transfer',
                'status'       => '1',
                'catatan'      => 'Pengajuan baru via transfer',
                'created_at'   => now()->subMinutes(30),
            ]);

            // 2. Pengajuan Pending (Tabungan)
            PengajuanDeposito::create([
                'id_nasabah'   => $nasabah2->id,
                'nominal'      => 2000000,
                'tenor_id'     => $tenor1->id,
                'metode_setor' => 'saldo_tabungan',
                'status'       => '1',
                'catatan'      => 'Pengajuan baru potong tabungan',
                'created_at'   => now()->subHour(),
            ]);

            // 3. Deposito Aktif Baru (Berjalan 5 Hari)
            $pengajuanAktif1 = PengajuanDeposito::create([
                'id_nasabah'   => $nasabah1->id,
                'nominal'      => 10000000,
                'tenor_id'     => $tenor6->id,
                'metode_setor' => 'transfer',
                'status'       => '2',
                'approved_by'  => $admin->id,
            ]);

            $tglMulai1 = now()->subDays(5);
            $depositoAktif1 = DepositoH::create([
                'id_pengajuan'    => $pengajuanAktif1->id,
                'id_nasabah'      => $nasabah1->id,
                'nomor_deposito'  => 'DP' . $tglMulai1->format('ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'nominal_awal'    => 10000000,
                'tenor_id'        => $tenor6->id,
                'bunga'           => 0.0525, // 5.25%
                'tgl_mulai'       => $tglMulai1,
                'tgl_jatuh_tempo' => $tglMulai1->copy()->addDays($tenor6->tenor_hari),
                'metode_pencairan'=> 'pencairan_ke_rekening',
                'status'          => 'aktif',
            ]);

            TransDeposito::create([
                'deposito_id'   => $depositoAktif1->id,
                'jenis'         => 'setor_awal',
                'nominal'       => 10000000,
                'keterangan'    => 'Setoran Awal Deposito',
                'tgl_transaksi' => $tglMulai1,
            ]);

            // Generate Bunga Harian 5 Hari
            $saldoBunga1 = 0;
            for ($i = 0; $i < 5; $i++) {
                $bungaHarian = (10000000 * 0.0525) / 365;
                $pajakHarian = $bungaHarian * 0.2;
                $bungaNet = $bungaHarian - $pajakHarian;
                $saldoBunga1 += $bungaNet;
                
                DB::table('deposito_bunga_harian')->insert([
                    'deposito_id'  => $depositoAktif1->id,
                    'tanggal'      => $tglMulai1->copy()->addDays($i + 1)->format('Y-m-d'),
                    'bunga_harian' => $bungaNet,
                    'saldo_akhir'  => 10000000 + $saldoBunga1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            // 4. Deposito Jatuh Tempo -> Nasabah Ajukan Pencairan Tabungan (Pending)
            $tglMulai2 = now()->subDays($tenor1->tenor_hari); // Mulai 1 bulan lalu (sudah jatuh tempo)
            $pengajuanAktif2 = PengajuanDeposito::create([
                'id_nasabah'   => $nasabah2->id,
                'nominal'      => 5000000,
                'tenor_id'     => $tenor1->id,
                'metode_setor' => 'transfer',
                'status'       => '2',
                'approved_by'  => $admin->id,
            ]);

            $depositoJT1 = DepositoH::create([
                'id_pengajuan'    => $pengajuanAktif2->id,
                'id_nasabah'      => $nasabah2->id,
                'nomor_deposito'  => 'DP' . $tglMulai2->format('ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'nominal_awal'    => 5000000,
                'tenor_id'        => $tenor1->id,
                'bunga'           => 0.0375, // 3.75%
                'tgl_mulai'       => $tglMulai2,
                'tgl_jatuh_tempo' => now()->subDay(), // Kemarin
                'metode_pencairan'=> 'pencairan_ke_rekening',
                'status'          => 'aktif',
            ]);
            
            TransDeposito::create([
                'deposito_id'   => $depositoJT1->id,
                'jenis'         => 'setor_awal',
                'nominal'       => 5000000,
                'keterangan'    => 'Setoran Awal Deposito',
                'tgl_transaksi' => $tglMulai2,
            ]);

            // Hitung bunga final
            $bungaKotor2 = 5000000 * 0.0375 * ($tenor1->tenor_hari / 365);
            $bungaNet2 = $bungaKotor2 - ($bungaKotor2 * 0.2);
            $nominalAkhir2 = 5000000 + $bungaNet2;

            PencairanDeposito::create([
                'deposito_id'     => $depositoJT1->id,
                'id_nasabah'      => $nasabah2->id,
                'jenis_pencairan' => 'saldo_tabungan',
                'metode_pencairan'=> 'saldo_tabungan',
                'nominal_akhir'   => $nominalAkhir2,
                'status'          => 'pending',
                'catatan'         => 'Test pengajuan cair ke tabungan',
            ]);

            // 5. Deposito Jatuh Tempo -> Nasabah Ajukan Pencairan TF (Pending)
            $tglMulai3 = now()->subDays($tenor3->tenor_hari);
            $pengajuanAktif3 = PengajuanDeposito::create([
                'id_nasabah'   => $nasabah3->id,
                'nominal'      => 15000000,
                'tenor_id'     => $tenor3->id,
                'metode_setor' => 'saldo_tabungan',
                'status'       => '2',
                'approved_by'  => $admin->id,
            ]);

            $depositoJT2 = DepositoH::create([
                'id_pengajuan'    => $pengajuanAktif3->id,
                'id_nasabah'      => $nasabah3->id,
                'nomor_deposito'  => 'DP' . $tglMulai3->format('ymd') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'nominal_awal'    => 15000000,
                'tenor_id'        => $tenor3->id,
                'bunga'           => 0.0450, // 4.5%
                'tgl_mulai'       => $tglMulai3,
                'tgl_jatuh_tempo' => now()->subDay(),
                'metode_pencairan'=> 'pencairan_ke_rekening',
                'status'          => 'aktif',
            ]);

            TransDeposito::create([
                'deposito_id'   => $depositoJT2->id,
                'jenis'         => 'setor_awal',
                'nominal'       => 15000000,
                'keterangan'    => 'Setoran Awal Deposito',
                'tgl_transaksi' => $tglMulai3,
            ]);

            $bungaKotor3 = 15000000 * 0.0450 * ($tenor3->tenor_hari / 365);
            $bungaNet3 = $bungaKotor3 - ($bungaKotor3 * 0.2);
            $nominalAkhir3 = 15000000 + $bungaNet3;

            PencairanDeposito::create([
                'deposito_id'     => $depositoJT2->id,
                'id_nasabah'      => $nasabah3->id,
                'jenis_pencairan' => 'rek_nasabah',
                'metode_pencairan'=> 'rek_nasabah',
                'nominal_akhir'   => $nominalAkhir3,
                'status'          => 'pending',
                'catatan'         => 'Test pengajuan cair TF',
            ]);

            DB::commit();
            $this->command->info('Deposito seeder ran successfully: 2 pengajuan pending, 1 deposito aktif, 2 pencairan pending (1 Tabungan, 1 TF).');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error running seeder: ' . $e->getMessage());
        }
    }
}
