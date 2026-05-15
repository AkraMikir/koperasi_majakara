<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GadaiActive;
use App\Models\GadaiHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GadaiCheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gadai:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek status jatuh tempo dan tenggang pada Gadai Aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan status Gadai...');

        $now = Carbon::now();

        // 1. Cek Active -> Grace Period
        $activeGadais = GadaiActive::with(['kategori', 'item'])
            ->where('status', 'active')
            ->whereDate('tgl_jatuh_tempo', '<', $now->toDateString())
            ->get();

        foreach ($activeGadais as $gadai) {
            DB::beginTransaction();
            try {
                // Hitung denda
                $denda = ($gadai->nominal_deal * $gadai->kategori->rate_denda) / 100;
                
                // Hitung inap
                $inap = 0;
                if ($gadai->item->nominal_inap > 0) {
                    // Jika ada nominal inap khusus di item (biasanya Kendaraan)
                    $inap = $gadai->item->nominal_inap;
                } else {
                    // Gunakan persentase dari kategori (biasanya Emas/Elektronik)
                    $inap = ($gadai->nominal_deal * $gadai->kategori->rate_inap_persen) / 100;
                }

                $gadai->update([
                    'status' => 'grace_period',
                    'denda_aktif' => $denda,
                    'biaya_inap' => $inap
                ]);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'extend', // Using extend status loosely for system update or we can just use create
                    'catatan' => 'Sistem otomatis mengubah status menjadi Masa Tenggang. Denda & Inap diterapkan.'
                ]);

                // TODO: Here we can trigger WhatsApp notification via WhatsAppService
                
                DB::commit();
                $this->info("Gadai ID {$gadai->id} masuk masa tenggang.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal update Gadai ID {$gadai->id}: " . $e->getMessage());
            }
        }

        // 2. Cek Grace Period -> Expired Final (Hangus)
        $graceGadais = GadaiActive::where('status', 'grace_period')
            ->whereDate('tgl_tenggang', '<', $now->toDateString())
            ->get();

        foreach ($graceGadais as $gadai) {
            DB::beginTransaction();
            try {
                $gadai->update(['status' => 'expired_final']);

                GadaiHistory::create([
                    'gadai_active_id' => $gadai->id,
                    'aksi' => 'expired',
                    'catatan' => 'Masa tenggang telah habis. Status diubah menjadi Hangus (Siap Dilelang).'
                ]);
                
                // TODO: Trigger WA notification Hangus
                
                DB::commit();
                $this->info("Gadai ID {$gadai->id} hangus (expired_final).");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal update Gadai ID {$gadai->id}: " . $e->getMessage());
            }
        }

        $this->info('Pengecekan selesai.');
    }
}
