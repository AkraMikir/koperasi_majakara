<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FixCollationViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('database/fix_collation_view.sql');
        $sql = File::get($path);
        
        // Execute raw sql
        DB::unprepared($sql);
        
        $this->command->info('Collation fixed and View created!');
    }
}
