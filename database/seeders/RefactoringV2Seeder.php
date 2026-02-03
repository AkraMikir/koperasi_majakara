<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RefactoringV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('database/refactoring_v2_final.sql');
        $sql = File::get($path);
        
        // Split by semicolon to execute statement by statement if possible, 
        // but DB::unprepared is usually better for raw dumps.
        DB::unprepared($sql);
        
        $this->command->info('Database refactoring V2 executed successfully!');
    }
}
