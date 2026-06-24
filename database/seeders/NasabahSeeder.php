<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Pekerjaan;
use App\Models\DataKtp;
use App\Models\DataRek;
use App\Models\Darurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel terkait nasabah untuk menghindari duplikasi data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tbl_darurat')->truncate();
        DB::table('tbl_data_rek')->truncate();
        DB::table('tbl_data_ktp')->truncate();
        DB::table('tbl_pekerjaan')->truncate();
        DB::table('tbl_nasabah')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get nasabah users
        $nasabahUsers = User::where('role', 'nasabah')->get();

        foreach ($nasabahUsers as $user) {
            // Cek jika ini adalah nasabah lama
            if ($user->email === 'budi.santoso@email.com') {
                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'no_kk' => '3201010101010001',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-15',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                    'foto_ktp' => 'ktp_budi.jpg',
                    'foto_kk' => 'kk_budi.jpg',
                ]);
                Pekerjaan::create([
                    'nasabah_id' => $nasabah->id,
                    'pekerjaan' => 'Karyawan Swasta',
                    'penghasilan' => 5000000.00,
                    'nama_perusahaan' => 'PT Maju Jaya',
                ]);
                DataKtp::create([
                    'nasabah_id' => $nasabah->id,
                    'nik' => '3201011501900001',
                    'nama_lengkap' => 'Budi Santoso',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-15',
                    'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                    'jenis_kelamin' => 'Laki-laki',
                    'file_ktp' => 'ktp_budi.jpg',
                ]);
                DataRek::create([
                    'nasabah_id' => $nasabah->id,
                    'no_rekening' => '1234567890123456',
                    'nama_pemilik_rekening' => 'Budi Santoso',
                    'nama_bank' => 'BCA',
                ]);
                Darurat::create([
                    'id_nasabah' => $nasabah->id,
                    'nama_lengkap' => 'Siti Budi',
                    'hubungan_peminjam' => 'Istri',
                    'no_telepon' => '081234567896',
                    'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                    'pekerjaan' => 'Ibu Rumah Tangga',
                    'email' => 'siti.budi@email.com',
                    'no_ktp' => '3201011501900002',
                    'foto_ktp' => 'ktp_darurat_budi.jpg',
                ]);
            } elseif ($user->email === 'siti.nurhaliza@email.com') {
                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'no_kk' => '3201010101010002',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1992-05-20',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                    'foto_ktp' => 'ktp_siti.jpg',
                    'foto_kk' => 'kk_siti.jpg',
                ]);
                Pekerjaan::create([
                    'nasabah_id' => $nasabah->id,
                    'pekerjaan' => 'Guru',
                    'penghasilan' => 4000000.00,
                    'nama_perusahaan' => 'SD Negeri 01',
                ]);
                DataKtp::create([
                    'nasabah_id' => $nasabah->id,
                    'nik' => '3201012005920002',
                    'nama_lengkap' => 'Siti Nurhaliza',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1992-05-20',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                    'jenis_kelamin' => 'Perempuan',
                    'file_ktp' => 'ktp_siti.jpg',
                ]);
                DataRek::create([
                    'nasabah_id' => $nasabah->id,
                    'no_rekening' => '2345678901234567',
                    'nama_pemilik_rekening' => 'Siti Nurhaliza',
                    'nama_bank' => 'Mandiri',
                ]);
                Darurat::create([
                    'id_nasabah' => $nasabah->id,
                    'nama_lengkap' => 'Ahmad Siti',
                    'hubungan_peminjam' => 'Suami',
                    'no_telepon' => '081234567897',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                    'pekerjaan' => 'Karyawan',
                    'email' => 'ahmad.siti@email.com',
                    'no_ktp' => '3201012005920003',
                    'foto_ktp' => 'ktp_darurat_siti.jpg',
                ]);
            } elseif ($user->email === 'ahmad.fauzi@email.com') {
                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'no_kk' => '3201010101010003',
                    'tempat_lahir' => 'Surabaya',
                    'tanggal_lahir' => '1988-11-10',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                    'foto_ktp' => 'ktp_ahmad.jpg',
                    'foto_kk' => 'kk_ahmad.jpg',
                ]);
                Pekerjaan::create([
                    'nasabah_id' => $nasabah->id,
                    'pekerjaan' => 'Wiraswasta',
                    'penghasilan' => 6000000.00,
                    'nama_perusahaan' => 'Toko Sembako Fauzi',
                ]);
                DataKtp::create([
                    'nasabah_id' => $nasabah->id,
                    'nik' => '3201011011880003',
                    'nama_lengkap' => 'Ahmad Fauzi',
                    'tempat_lahir' => 'Surabaya',
                    'tanggal_lahir' => '1988-11-10',
                    'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                    'jenis_kelamin' => 'Laki-laki',
                    'file_ktp' => 'ktp_ahmad.jpg',
                ]);
                DataRek::create([
                    'nasabah_id' => $nasabah->id,
                    'no_rekening' => '3456789012345678',
                    'nama_pemilik_rekening' => 'Ahmad Fauzi',
                    'nama_bank' => 'BNI',
                ]);
                Darurat::create([
                    'id_nasabah' => $nasabah->id,
                    'nama_lengkap' => 'Fauzi Ahmad',
                    'hubungan_peminjam' => 'Saudara',
                    'no_telepon' => '081234567898',
                    'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                    'pekerjaan' => 'Wiraswasta',
                    'email' => 'fauzi.ahmad@email.com',
                    'no_ktp' => '3201011011880004',
                    'foto_ktp' => 'ktp_darurat_ahmad.jpg',
                ]);
            } else {
                // Untuk Nasabah Baru (nsb1@o.com sampai nsb9@o.com)
                // Deteksi prefix email
                $prefix = explode('@', $user->email)[0]; // e.g. 'nsb1', 'nsb2', ...
                $num = (int) filter_var($prefix, FILTER_SANITIZE_NUMBER_INT);
                
                // Masing-masing bank & detail sesuai nomor
                // 1, 2, 3 -> terverifikasi akun & kontak daruratnya (Bank: BNI)
                // 4, 5, 6 -> unverified, bank: BCA
                // 7, 8, 9 -> unverified, bank: Mandiri
                $bank = 'BNI';
                $hasDarurat = false;

                if ($num >= 1 && $num <= 3) {
                    $hasDarurat = true;
                } elseif ($num >= 4 && $num <= 6) {
                    $bank = 'BCA';
                } elseif ($num >= 7 && $num <= 9) {
                    $bank = 'Mandiri';
                }

                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'no_kk' => '320101010101900' . $num,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1995-08-08',
                    'jenis_kelamin' => ($num % 2 === 0) ? 'P' : 'L',
                    'alamat' => 'Jl. Baru No. ' . $num,
                    'foto_ktp' => 'ktp_' . $prefix . '.jpg',
                    'foto_kk' => 'kk_' . $prefix . '.jpg',
                ]);

                Pekerjaan::create([
                    'nasabah_id' => $nasabah->id,
                    'pekerjaan' => 'Karyawan',
                    'penghasilan' => '4500000',
                    'nama_perusahaan' => 'PT Test New',
                ]);

                DataKtp::create([
                    'nasabah_id' => $nasabah->id,
                    'nik' => '320101150195900' . $num,
                    'nama_lengkap' => $user->nama,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1995-08-08',
                    'alamat' => 'Jl. Baru No. ' . $num,
                    'jenis_kelamin' => ($num % 2 === 0) ? 'Perempuan' : 'Laki-laki',
                    'file_ktp' => 'ktp_' . $prefix . '.jpg',
                ]);

                DataRek::create([
                    'nasabah_id' => $nasabah->id,
                    'no_rekening' => '987654321012300' . $num,
                    'nama_pemilik_rekening' => $user->nama,
                    'nama_bank' => $bank,
                ]);

                if ($hasDarurat) {
                    Darurat::create([
                        'id_nasabah' => $nasabah->id,
                        'nama_lengkap' => 'Darurat ' . $user->nama,
                        'hubungan_peminjam' => 'Orang Tua',
                        'no_telepon' => '08199988000' . $num,
                        'alamat' => 'Jl. Darurat ' . $num,
                        'pekerjaan' => 'Pensiunan',
                        'email' => 'darurat.' . $num . '@test.com',
                        'no_ktp' => '320101150195980' . $num,
                        'foto_ktp' => 'ktp_darurat_' . $prefix . '.jpg',
                    ]);
                }
            }
        }
    }
}

