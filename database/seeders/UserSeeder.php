<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel user & admin untuk mencegah duplikasi saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admin_operasional')->truncate();
        DB::table('admin_utama')->truncate();
        DB::table('users')->truncate();
        DB::table('tbl_otp')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- AKUN LAMA ---
        // Admin Utama Lama
        $adminUtamaLama = User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@koperasi.com',
            'pin' => '123456',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567890',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_utama',
            'email_verified_at' => now(),
        ]);
        \App\Models\AdminUtama::create([
            'user_id' => $adminUtamaLama->id,
        ]);

        // Admin Operasional Lama
        $adminOperasional1Lama = User::create([
            'nama' => 'Admin Operasional 1',
            'email' => 'admin.operasional1@koperasi.com',
            'pin' => '567890',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567891',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_operasional',
            'email_verified_at' => now(),
        ]);
        \App\Models\AdminOperasional::create([
            'user_id' => $adminOperasional1Lama->id,
        ]);

        $adminOperasional2Lama = User::create([
            'nama' => 'Admin Operasional 2',
            'email' => 'admin.operasional2@koperasi.com',
            'pin' => '901234',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567892',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_operasional',
            'email_verified_at' => now(),
        ]);
        \App\Models\AdminOperasional::create([
            'user_id' => $adminOperasional2Lama->id,
        ]);

        // Nasabah Lama
        $nasabahLama = [
            ['nama' => 'Budi Santoso', 'email' => 'budi.santoso@email.com', 'pin' => '111111', 'password' => 'password123', 'nomor_hp' => '081234567893', 'verified' => now()],
            ['nama' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@email.com', 'pin' => '222222', 'password' => 'password123', 'nomor_hp' => '081234567894', 'verified' => now()],
            ['nama' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@email.com', 'pin' => '333333', 'password' => 'password123', 'nomor_hp' => '081234567895', 'verified' => now()],
        ];
        foreach ($nasabahLama as $nl) {
            User::create([
                'nama' => $nl['nama'],
                'email' => $nl['email'],
                'pin' => $nl['pin'],
                'password' => Hash::make($nl['password']),
                'nomor_hp' => $nl['nomor_hp'],
                'foto' => 'default-avatar.jpg',
                'role' => 'nasabah',
                'email_verified_at' => now(),
                'verified' => $nl['verified'],
            ]);
        }

        // --- AKUN BARU ---
        // 3 Owners Baru (admin_utama)
        $owners = [
            ['nama' => 'Owner 1', 'email' => 'owner1@o.com', 'password' => '1'],
            ['nama' => 'Owner 2', 'email' => 'owner2@o.com', 'password' => '1'],
            ['nama' => 'Owner 3', 'email' => 'owner3@o.com', 'password' => '1'],
        ];

        foreach ($owners as $ownerData) {
            $user = User::create([
                'nama' => $ownerData['nama'],
                'email' => $ownerData['email'],
                'pin' => '111111',
                'password' => Hash::make($ownerData['password']),
                'nomor_hp' => '081100000' . rand(100, 999),
                'foto' => 'default-avatar.jpg',
                'role' => 'admin_utama',
                'email_verified_at' => now(),
            ]);

            \App\Models\AdminUtama::create([
                'user_id' => $user->id,
            ]);
        }

        // 3 Admin Operasional Baru (admin_operasional)
        $ops = [
            ['nama' => 'Ops 1', 'email' => 'ops1@o.com', 'password' => '1'],
            ['nama' => 'Ops 2', 'email' => 'ops2@o.com', 'password' => '1'],
            ['nama' => 'Ops 3', 'email' => 'ops3@o.com', 'password' => '1'],
        ];

        foreach ($ops as $opData) {
            $user = User::create([
                'nama' => $opData['nama'],
                'email' => $opData['email'],
                'pin' => '111111',
                'password' => Hash::make($opData['password']),
                'nomor_hp' => '081200000' . rand(100, 999),
                'foto' => 'default-avatar.jpg',
                'role' => 'admin_operasional',
                'email_verified_at' => now(),
            ]);

            \App\Models\AdminOperasional::create([
                'user_id' => $user->id,
            ]);
        }

        // 9 Nasabah Baru (nasabah)
        $nasabahs = [
            ['nama' => 'Nsb 1', 'email' => 'nsb1@o.com', 'password' => '1', 'verified' => now()],
            ['nama' => 'Nsb 2', 'email' => 'nsb2@o.com', 'password' => '1', 'verified' => now()],
            ['nama' => 'Nsb 3', 'email' => 'nsb3@o.com', 'password' => '1', 'verified' => now()],
            ['nama' => 'Nsb 4', 'email' => 'nsb4@o.com', 'password' => '1', 'verified' => null],
            ['nama' => 'Nsb 5', 'email' => 'nsb5@o.com', 'password' => '1', 'verified' => null],
            ['nama' => 'Nsb 6', 'email' => 'nsb6@o.com', 'password' => '1', 'verified' => null],
            ['nama' => 'Nsb 7', 'email' => 'nsb7@o.com', 'password' => '1', 'verified' => null],
            ['nama' => 'Nsb 8', 'email' => 'nsb8@o.com', 'password' => '1', 'verified' => null],
            ['nama' => 'Nsb 9', 'email' => 'nsb9@o.com', 'password' => '1', 'verified' => null],
        ];

        foreach ($nasabahs as $nasabahData) {
            User::create([
                'nama' => $nasabahData['nama'],
                'email' => $nasabahData['email'],
                'pin' => '111111',
                'password' => Hash::make($nasabahData['password']),
                'nomor_hp' => '081300000' . rand(100, 999),
                'foto' => 'default-avatar.jpg',
                'role' => 'nasabah',
                'email_verified_at' => now(),
                'verified' => $nasabahData['verified'],
            ]);
        }
    }
}