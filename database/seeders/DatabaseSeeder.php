<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterDataSeeder::class,
            GadaiSeeder::class,
            GadaiBaruSeeder::class,
            UserSeeder::class,
            NasabahSeeder::class,
            JnsLokasiPerusahaanSeeder::class,
            PinjamanSeeder::class,
        ]);
    }
}