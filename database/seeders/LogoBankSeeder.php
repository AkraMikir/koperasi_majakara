<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogoBank;

class LogoBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logos = [
            [
                'nama_bank' => 'BCA',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg'
            ],
            [
                'nama_bank' => 'Mandiri',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg'
            ],
            [
                'nama_bank' => 'BNI',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg'
            ],
            [
                'nama_bank' => 'BRI',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_Logo.svg'
            ],
            [
                'nama_bank' => 'BSI',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a0/Bank_Syariah_Indonesia_2021.svg'
            ],
            [
                'nama_bank' => 'BTN',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Logo_Bank_Tabungan_Negara.svg/1200px-Logo_Bank_Tabungan_Negara.svg.png'
            ],
            [
                'nama_bank' => 'CIMB Niaga',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_CIMB_Niaga.svg/1200px-Logo_CIMB_Niaga.svg.png'
            ],
            [
                'nama_bank' => 'Permata',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/a/a3/PermataBank_logo.svg'
            ],
            [
                'nama_bank' => 'Danamon',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/c/c2/Bank_Danamon_logo.svg'
            ],
            [
                'nama_bank' => 'Mega',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/a/af/Bank_Mega_logo.svg'
            ],
            [
                'nama_bank' => 'Muamalat',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/f/f6/Bank_Muamalat_logo.svg'
            ],
            [
                'nama_bank' => 'Panin',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/9/90/Logo_Bank_Panin.svg'
            ],
            [
                'nama_bank' => 'Maybank',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/id/7/75/Logo_Maybank.svg'
            ],
            [
                'nama_bank' => 'SeaBank',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/SeaBank_Logo.svg/1200px-SeaBank_Logo.svg.png'
            ],
            [
                'nama_bank' => 'Bank Jago',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Bank_Jago_logo.svg/1200px-Bank_Jago_logo.svg.png'
            ]
        ];

        foreach ($logos as $logo) {
            LogoBank::updateOrCreate(
                ['nama_bank' => $logo['nama_bank']],
                ['logo_url' => $logo['logo_url']]
            );
        }
    }
}
