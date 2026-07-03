<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\UserTemp;

class CleanupRegistrasiTemp extends Command
{
    protected $signature = 'registrasi:cleanup-temp {--minutes=20 : Hapus data temp yang tidak aktif lebih dari N menit}';

    protected $description = 'Hapus data registrasi temp yang sudah tidak aktif (default: 20 menit)';

    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $cutoff = now()->subMinutes($minutes);

        $expiredRecords = UserTemp::where('updated_at', '<', $cutoff)->get();

        if ($expiredRecords->isEmpty()) {
            $this->info('Tidak ada data temp yang expired.');
            return 0;
        }

        $deletedCount = 0;

        foreach ($expiredRecords as $userTemp) {
            // Hapus file foto profil
            if ($userTemp->foto && $userTemp->foto !== 'default-profile.jpg') {
                if (Storage::disk('public')->exists($userTemp->foto)) {
                    Storage::disk('public')->delete($userTemp->foto);
                }
            }

            // Hapus direktori temp user
            $tempUserDir = "registrasi/temp/users_{$userTemp->id}";
            if (Storage::disk('public')->exists($tempUserDir)) {
                Storage::disk('public')->deleteDirectory($tempUserDir);
            }

            // Delete record — cascade ke tbl_nasabah_temp dan semua turunannya
            $userTemp->delete();
            $deletedCount++;
        }

        // Cleanup direktori temp/data_diri jika kosong
        $dataDiriDir = 'registrasi/temp/data_diri';
        if (Storage::disk('public')->exists($dataDiriDir)) {
            $files = Storage::disk('public')->allFiles($dataDiriDir);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($dataDiriDir);
            }
        }

        $this->info("Cleanup selesai. {$deletedCount} record temp dihapus (expired > {$minutes} menit).");
        return 0;
    }
}
