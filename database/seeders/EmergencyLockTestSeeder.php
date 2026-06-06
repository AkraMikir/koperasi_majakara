<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Pekerjaan;
use App\Models\DataKtp;
use App\Models\DataRek;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmergencyLockTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user already exists to prevent duplication
        $user = User::where('email', 'test.lock@email.com')->first();
        if ($user) {
            if ($user->nasabah) {
                // Delete existing darurat if any to make sure it is empty for test
                if ($user->nasabah->darurat) {
                    $user->nasabah->darurat->delete();
                }
                return;
            }
            $user->delete();
        }

        // Create testing nasabah user
        $user = User::create([
            'nama' => 'Nasabah Tanpa Kontak Darurat',
            'email' => 'test.lock@email.com',
            'pin' => '123456', // Laravel hashes it automatically via casts
            'password' => Hash::make('password123'),
            'nomor_hp' => '089999999999',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);

        // Create nasabah profile
        $nasabah = Nasabah::create([
            'user_id' => $user->id,
            'no_kk' => '3201010101019999',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Uji Coba Lock No. 99, Jakarta Pusat',
            'foto_ktp' => 'ktp_test.jpg',
            'foto_kk' => 'kk_test.jpg',
        ]);

        // Create pekerjaan
        Pekerjaan::create([
            'nasabah_id' => $nasabah->id,
            'pekerjaan' => 'Karyawan Swasta',
            'penghasilan' => 5000000.00,
            'nama_perusahaan' => 'PT Uji Coba',
        ]);

        // Create data KTP
        DataKtp::create([
            'nasabah_id' => $nasabah->id,
            'nik' => '3201011505959999',
            'nama_lengkap' => 'Nasabah Tanpa Kontak Darurat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-05-15',
            'alamat' => 'Jl. Uji Coba Lock No. 99, Jakarta Pusat',
            'jenis_kelamin' => 'Laki-laki',
            'file_ktp' => 'ktp_test.jpg',
        ]);

        // Create data rekening
        DataRek::create([
            'nasabah_id' => $nasabah->id,
            'no_rekening' => '9999999999999999',
            'nama_pemilik_rekening' => 'Nasabah Tanpa Kontak Darurat',
            'nama_bank' => 'BCA',
        ]);

        // Note: We explicitly do NOT create the Darurat record here.
    }
}
