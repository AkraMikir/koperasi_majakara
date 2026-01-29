<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BiayaTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // BCA to others
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BCA', 'biaya_admin' => 0, 'keterangan' => 'Sesama BCA gratis', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BNI', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke BNI', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'Mandiri', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke Mandiri', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'BRI', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke BRI', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'CIMB Niaga', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke CIMB Niaga', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'Permata', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke Permata', 'is_active' => true],
            ['bank_pengirim' => 'BCA', 'bank_penerima' => 'Bank Lainnya', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BCA ke bank lain', 'is_active' => true],
            
            // BNI to others
            ['bank_pengirim' => 'BNI', 'bank_penerima' => 'BNI', 'biaya_admin' => 0, 'keterangan' => 'Sesama BNI gratis', 'is_active' => true],
            ['bank_pengirim' => 'BNI', 'bank_penerima' => 'BCA', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BNI ke BCA', 'is_active' => true],
            ['bank_pengirim' => 'BNI', 'bank_penerima' => 'Mandiri', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BNI ke Mandiri', 'is_active' => true],
            ['bank_pengirim' => 'BNI', 'bank_penerima' => 'BRI', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BNI ke BRI', 'is_active' => true],
            ['bank_pengirim' => 'BNI', 'bank_penerima' => 'Bank Lainnya', 'biaya_admin' => 6500, 'keterangan' => 'Transfer BNI ke bank lain', 'is_active' => true],
            
            // Mandiri to others
            ['bank_pengirim' => 'Mandiri', 'bank_penerima' => 'Mandiri', 'biaya_admin' => 0, 'keterangan' => 'Sesama Mandiri gratis', 'is_active' => true],
            ['bank_pengirim' => 'Mandiri', 'bank_penerima' => 'BCA', 'biaya_admin' => 6500, 'keterangan' => 'Transfer Mandiri ke BCA', 'is_active' => true],
            ['bank_pengirim' => 'Mandiri', 'bank_penerima' => 'BNI', 'biaya_admin' => 6500, 'keterangan' => 'Transfer Mandiri ke BNI', 'is_active' => true],
            ['bank_pengirim' => 'Mandiri', 'bank_penerima' => 'BRI', 'biaya_admin' => 6500, 'keterangan' => 'Transfer Mandiri ke BRI', 'is_active' => true],
            ['bank_pengirim' => 'Mandiri', 'bank_penerima' => 'Bank Lainnya', 'biaya_admin' => 6500, 'keterangan' => 'Transfer Mandiri ke bank lain', 'is_active' => true],
        ];

        foreach ($data as $item) {
            \App\Models\BiayaTransfer::create($item);
        }
    }
}
