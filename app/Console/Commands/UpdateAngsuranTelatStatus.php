<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\PinjamanH;
use Carbon\Carbon;

class UpdateAngsuranTelatStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:update-telat-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status angsuran yang telat pembayarannya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update status angsuran telat...');

        $now = Carbon::now();
        $updated = 0;

        $models = [TempoPinjamanB::class, TempoPinjamanM::class];

        foreach ($models as $modelClass) {
            $type = $modelClass === TempoPinjamanB::class ? 'Bulanan' : 'Mingguan';
            $angsurans = $modelClass::where('status_bayar', '!=', 'lunas')
                ->where('tgl_jatuh_tempo', '<', $now)
                ->get();

            foreach ($angsurans as $angsuran) {
                if ($angsuran->status_bayar !== 'telat') {
                    $angsuran->update(['status_bayar' => 'telat']);
                    $updated++;

                    // Hitung dan update denda
                    $denda = $angsuran->hitungDenda();
                    $angsuran->update(['denda' => $denda]);
                } else {
                    // Update denda untuk angsuran yang sudah telat
                    $denda = $angsuran->hitungDenda();
                    if ($angsuran->denda != $denda) {
                        $angsuran->update(['denda' => $denda]);
                        $updated++;
                    }
                }
            }
        }

        // Check pinjaman yang sudah lunas (semua angsuran sudah lunas)
        $pinjamanBelumLunas = PinjamanH::where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->with(['tempoBulanan', 'tempoMingguan'])
            ->get();

        foreach ($pinjamanBelumLunas as $pinjaman) {
            if ($pinjaman->checkAndUpdateLunasStatus()) {
                $this->info("Pinjaman #{$pinjaman->id} telah lunas");
            }
        }

        $this->info("Selesai! Total {$updated} angsuran di-update.");

        return 0;
    }

}
