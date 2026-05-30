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

        // Admin Utama
        $adminUtama = User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@koperasi.com',
            'pin' => '123456',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567890',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_utama',
            'email_verified_at' => now(),
        ]);

        // Admin Operasional
        $adminOperasional1 = User::create([
            'nama' => 'Admin Operasional 1',
            'email' => 'admin.operasional1@koperasi.com',
            'pin' => '567890',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567891',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_operasional',
            'email_verified_at' => now(),
        ]);

        $adminOperasional2 = User::create([
            'nama' => 'Admin Operasional 2',
            'email' => 'admin.operasional2@koperasi.com',
            'pin' => '901234',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567892',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_operasional',
            'email_verified_at' => now(),
        ]);

        // Nasabah
        $nasabah1 = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@email.com',
            'pin' => '111111',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567893',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);

        $nasabah2 = User::create([
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@email.com',
            'pin' => '222222',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567894',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);

        $nasabah3 = User::create([
            'nama' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@email.com',
            'pin' => '333333',
            'password' => Hash::make('password123'),
            'nomor_hp' => '081234567895',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);

        // Create admin records
        \App\Models\AdminUtama::create([
            'user_id' => $adminUtama->id,
        ]);

        \App\Models\AdminOperasional::create([
            'user_id' => $adminOperasional1->id,
        ]);

        \App\Models\AdminOperasional::create([
            'user_id' => $adminOperasional2->id,
        ]);
    }
}