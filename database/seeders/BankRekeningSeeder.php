<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JnsBank;

class BankRekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Existing (Bang Parhan) - Diupdate detailnya
        JnsBank::updateOrCreate(
            ['no_rek' => '6331062879'],
            [
                'pemilik' => 'Farhan Saadilah',
                'nama' => 'Farhan Saadilah',
                'bank' => 'BCA',
                'cabang' => 'Pekayon',
                'kode_bank' => '014',
                'status' => 'aktif',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg'
            ]
        );

        // // 2. Data Baru (Developer ganteng Dessar)
        // JnsBank::updateOrCreate(
        //     ['no_rek' => '7391937197'],
        //     [
        //         'pemilik' => 'Developer ganteng Dessar',
        //         'nama' => 'Dessar',
        //         'bank' => 'BCA',
        //         'cabang' => 'Pekayon',
        //         'kode_bank' => '014',
        //         'status' => 'aktif',
        //         'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg'
        //     ]
        // );
    }
}
