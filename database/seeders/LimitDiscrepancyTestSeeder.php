<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\LimitPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LimitDiscrepancyTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean up existing test data
        $existingUser = User::where('email', 'test.discrepancy@email.com')->first();
        if ($existingUser) {
            $nasabah = Nasabah::where('user_id', $existingUser->id)->first();
            if ($nasabah) {
                TempoPinjamanB::whereIn('pinjaman_id', function ($query) use ($nasabah) {
                    $query->select('id')->from('tbl_pinjaman_h')->where('id_anggota', $nasabah->id);
                })->delete();
                PinjamanH::where('id_anggota', $nasabah->id)->delete();
                LimitPinjaman::where('id_nasabah', $nasabah->id)->delete();
                $nasabah->delete();
            }
            $existingUser->delete();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create User (with required 'foto' attribute)
        $user = User::create([
            'nama' => 'Test Limit Discrepancy',
            'email' => 'test.discrepancy@email.com',
            'pin' => '112233',
            'password' => Hash::make('password123'),
            'nomor_hp' => '089988887777',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);

        // 2. Create Nasabah
        $nasabah = Nasabah::create([
            'user_id' => $user->id,
            'no_kk' => '3201010101019999',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-05-20',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Uji Coba Discrepancy No. 99',
        ]);

        // 3. Create Loan (which is already Lunas, passing non-nullable attributes)
        $loanId = 'P-TEST-DISC';
        $loan = PinjamanH::create([
            'id' => $loanId,
            'id_anggota' => $nasabah->id,
            'jumlah_pinjam' => 1500000.00,
            'lama_pinjam' => 1,
            'jenis' => 'bulanan',
            'bunga' => 0.00,
            'bunga_rp' => 0.00,
            'ags_bulan' => 1500000.00,
            'lunas' => 'lunas',
            'tgl_pinjam' => now()->subMonth(),
        ]);

        // 4. Create paid installment
        TempoPinjamanB::create([
            'id' => 'T-TEST-DISC',
            'pinjaman_id' => $loan->id,
            'no_urut' => 1,
            'tgl_jatuh_tempo' => now()->subDays(5),
            'jumlah_tagihan' => 1500000.00,
            'jumlah_terbayar' => 1500000.00,
            'denda' => 0.00,
            'tgl_bayar' => now()->subDays(5),
            'status_bayar' => 'lunas',
        ]);

        // 5. Create Limit (Discrepancy: nominal_terpakai should be 0, but set to 1,500,000)
        LimitPinjaman::create([
            'id_nasabah' => $nasabah->id,
            'limit_nominal' => 2000000.00,
            'nominal_terpakai' => 1500000.00, // Discrepancy!
        ]);

        $this->command->info("LimitDiscrepancyTestSeeder: Nasabah 'Test Limit Discrepancy' created with a paid loan but with used limit remaining at 1,500,000.00.");
    }
}
