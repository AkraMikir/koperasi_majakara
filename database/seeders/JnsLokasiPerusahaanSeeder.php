<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JnsLokasiPerusahaan;
use Illuminate\Support\Facades\DB;

class JnsLokasiPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing location or create new one
        DB::table('jns_lokasi_perusahaan')->updateOrInsert(
            ['id' => 1],
            [
                'nama_lokasi' => 'Neovala Head Office',
                'alamat_lengkap' => 'Pondok Pekayon Indah, CC No.42/16, RT.04/RW.018',
                'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!3m2!1sid!2sid!4v1770304337083!5m2!1sid!2sid!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f359.26803021923!4f-14.940653351282776!5f0.7820865974627469',
                'kota' => 'Bekasi',
                'provinsi' => 'Jawa Barat',
                'tipe_lokasi' => 'Kantor Pusat',
                'status_aktif' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->command->info('✅ Lokasi perusahaan berhasil di-update!');
    }
}
