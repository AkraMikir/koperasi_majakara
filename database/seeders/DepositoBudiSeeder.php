<?php

namespace Database\Seeders;

use App\Helpers\IdGenerator;
use App\Models\DepositoH;
use App\Models\JnsTenorDeposito;
use App\Models\Nasabah;
use App\Models\PaketDeposito;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositoBudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari Nasabah Budi (Nasabah ID 1, User ID 4)
        $nasabah = Nasabah::find(1);
        if (!$nasabah) {
            $this->command->error('Nasabah Budi (ID 1) tidak ditemukan.');
            return;
        }

        // Ambil Paket Deposito & Tenor secara random
        $pakets = PaketDeposito::where('status', 'aktif')->get();
        $tenors = JnsTenorDeposito::where('aktif', 'y')->get();

        if ($pakets->isEmpty() || $tenors->isEmpty()) {
            $this->command->error('Master data paket atau tenor kosong.');
            return;
        }

        $this->command->info('Menyemai data deposito untuk Budi Santoso...');

        // 1. 5 Deposito mendekati jatuh tempo (today + 1 s/d today + 6)
        for ($i = 1; $i <= 5; $i++) {
            $paket = $pakets->random();
            $tenor = $tenors->where('tenor_bulan', $paket->tenor_bulan)->first() ?? $tenors->random();
            $nominal = rand(5, 50) * 1000000;
            $tglJatuhTempo = Carbon::now()->addDays($i);
            $tglMulai = $tglJatuhTempo->copy()->subMonths($paket->tenor_bulan);

            $this->createDeposito($nasabah, $paket, $tenor, $nominal, $tglMulai, $tglJatuhTempo);
        }

        // 2. 3 Deposito sudah tenor / lewat jatuh tempo (today - 10 s/d today - 1)
        for ($i = 1; $i <= 3; $i++) {
            $paket = $pakets->random();
            $tenor = $tenors->where('tenor_bulan', $paket->tenor_bulan)->first() ?? $tenors->random();
            $nominal = rand(10, 100) * 1000000;
            $tglJatuhTempo = Carbon::now()->subDays($i * 3); // -3, -6, -9 hari
            $tglMulai = $tglJatuhTempo->copy()->subMonths($paket->tenor_bulan);

            $this->createDeposito($nasabah, $paket, $tenor, $nominal, $tglMulai, $tglJatuhTempo);
        }

        $this->command->info('Selesai menyemai 8 data deposito Budi.');
    }

    private function createDeposito($nasabah, $paket, $tenor, $nominal, $tglMulai, $tglJatuhTempo)
    {
        // char(16) limit
        $nomor = 'DEP' . rand(1000, 9999) . date('is'); 
        $nomor = str_pad($nomor, 16, '0');
        
        DepositoH::create([
            'id_nasabah'      => $nasabah->id,
            'paket_id'        => $paket->id,
            'nomor_deposito'  => $nomor,
            'nominal_awal'    => $nominal,
            'tenor_id'        => $tenor->id,
            'bunga'           => $paket->suku_bunga / 100,
            'tgl_mulai'       => $tglMulai,
            'tgl_jatuh_tempo' => $tglJatuhTempo,
            'status'          => 'aktif',
            'metode_pencairan'=> 'pencairan_ke_rekening', // disesuaikan dengan enum
        ]);
    }
}
