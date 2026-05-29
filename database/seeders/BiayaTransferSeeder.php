<?php

namespace Database\Seeders;

use App\Models\BiayaTransfer;
use Illuminate\Database\Seeder;

class BiayaTransferSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // BCA → BCA (tidak ada biaya)
            [
                'bank_pengirim' => 'BCA',
                'bank_penerima' => 'BCA',
                'biaya_admin' => 0,
                'min_saldo_non_bca' => 0,
                'keterangan' => 'Transfer dalam bank yang sama',
                'is_active' => 1,
            ],
            
            // BCA → Mandiri
            [
                'bank_pengirim' => 'BCA',
                'bank_penerima' => 'Mandiri',
                'biaya_admin' => 5000,
                'min_saldo_non_bca' => 20000,
                'keterangan' => 'Transfer ke Mandiri',
                'is_active' => 1,
            ],
            
            // BCA → BNI
            [
                'bank_pengirim' => 'BCA',
                'bank_penerima' => 'BNI',
                'biaya_admin' => 4000,
                'min_saldo_non_bca' => 20000,
                'keterangan' => 'Transfer ke BNI',
                'is_active' => 1,
            ],
            
            // BCA → BRI
            [
                'bank_pengirim' => 'BCA',
                'bank_penerima' => 'BRI',
                'biaya_admin' => 4000,
                'min_saldo_non_bca' => 20000,
                'keterangan' => 'Transfer ke BRI',
                'is_active' => 1,
            ],
        ];

        foreach ($data as $item) {
            BiayaTransfer::firstOrCreate(
                [
                    'bank_pengirim' => $item['bank_pengirim'], 
                    'bank_penerima' => $item['bank_penerima']
                ],
                $item
            );
        }
    }
}
