<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class RefreshDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:refresh-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh cache untuk halaman Dashboard dan Sidebar Admin agar tetap ringan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai membersihkan cache dashboard dan sidebar admin...');
        
        // Dapatkan semua user admin yang relevan
        $admins = User::whereIn('role', ['admin_utama', 'admin_operasional'])->get();
        
        $count = 0;
        foreach ($admins as $user) {
            Cache::forget('sidebar_stats_' . $user->id);
            Cache::forget('dashboard_stats_' . $user->id);
            $count++;
        }
        
        $this->info('✅ Berhasil menyegarkan cache untuk ' . $count . ' admin.');
        return 0;
    }
}
