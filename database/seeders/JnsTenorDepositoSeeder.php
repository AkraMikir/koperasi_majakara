<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JnsTenorDepositoSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan suku bunga dulu agar tidak duplikat
        DB::table('suku_bunga_deposito')->delete();

        $tenors = [
            ['tenor_bulan' => 1,  'tenor_hari' => 30,  'bunga' => 0.0375, 'label' => 'Deposito 1 Bulan'],
            ['tenor_bulan' => 3,  'tenor_hari' => 90,  'bunga' => 0.0450, 'label' => 'Deposito 3 Bulan'],
            ['tenor_bulan' => 6,  'tenor_hari' => 180, 'bunga' => 0.0525, 'label' => 'Deposito 6 Bulan'],
            ['tenor_bulan' => 12, 'tenor_hari' => 365, 'bunga' => 0.0600, 'label' => 'Deposito 12 Bulan'],
        ];

        foreach ($tenors as $tenor) {
            // Upsert jns_tenor_deposito
            $tenorRecord = DB::table('jns_tenor_deposito')
                ->where('tenor_bulan', $tenor['tenor_bulan'])
                ->first();

            if (!$tenorRecord) {
                $tenorId = DB::table('jns_tenor_deposito')->insertGetId([
                    'tenor_hari'  => $tenor['tenor_hari'],
                    'tenor_bulan' => $tenor['tenor_bulan'],
                    'aktif'       => 'y',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            } else {
                $tenorId = $tenorRecord->id;
                // Update tenor_hari jika belum sesuai
                DB::table('jns_tenor_deposito')->where('id', $tenorId)->update([
                    'tenor_hari'  => $tenor['tenor_hari'],
                    'aktif'       => 'y',
                    'updated_at'  => now(),
                ]);
            }

            // Insert suku_bunga_deposito dengan 1 tier (berlaku untuk semua nominal ≥ 1juta)
            DB::table('suku_bunga_deposito')->insert([
                'tenor_id'    => $tenorId,
                'min_nominal' => 1000000, // Min 1 Juta
                'max_nominal' => null,    // Tanpa batas max
                'bunga'       => $tenor['bunga'],
                'status'      => 'aktif',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->command->info('✅ Tenor Deposito & Suku Bunga berhasil di-seed:');
        $this->command->info('   1 bln → 3.75% | 3 bln → 4.50% | 6 bln → 5.25% | 12 bln → 6.00%');
    }
}
