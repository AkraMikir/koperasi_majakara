<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use App\Models\DepositoH;
use App\Models\DepositoPersiapanCair;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateDepositoPeringatan extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'deposito:generate-peringatan
                            {--days=7 : Jumlah hari sebelum jatuh tempo untuk memulai peringatan}
                            {--dry-run : Tampilkan data tanpa menyimpan}';

    /**
     * The console command description.
     */
    protected $description = 'Generate peringatan jatuh tempo deposito X hari ke depan dan siapkan data pencairan';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days    = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');
        $today   = now()->toDateString();
        $batas   = now()->addDays($days)->toDateString();

        $this->info(" Mencari deposito aktif dengan jatuh tempo antara {$today} dan {$batas}...");

        // Ambil deposito aktif yang jatuh tempo dalam X hari ke depan
        // dan belum ada entri di deposito_persiapan_cair dengan status non-selesai
        $depositos = DepositoH::with(['nasabah', 'tenor'])
            ->where('status', 'aktif')
            ->where('tgl_jatuh_tempo', '<=', $batas)
            ->whereDoesntHave('persiapanCair', fn($q) =>
                $q->whereIn('status', ['tentatif', 'diproses'])
            )
            ->get();

        if ($depositos->isEmpty()) {
            $this->info('Tidak ada deposito yang perlu di-generate peringatan.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$depositos->count()} deposito.\n");

        $generated = 0;
        $errors    = 0;

        foreach ($depositos as $deposito) {
            try {
                // Kalkulasi bunga sesuai standar perbankan harian
                $pokok       = (float) $deposito->nominal_awal;
                $bungaTahunan = (float) $deposito->bunga;
                $tenorHari    = $deposito->tenor ? (int) $deposito->tenor->tenor_hari : 30;

                // Tentukan pembagi (365/366) berdasarkan tahun jatuh tempo
                $tahunJatuhTempo = $deposito->tgl_jatuh_tempo->year;
                $isLeap = ($tahunJatuhTempo % 4 === 0 && $tahunJatuhTempo % 100 !== 0) || ($tahunJatuhTempo % 400 === 0);
                $pembagi = $isLeap ? 366 : 365;

                // Bunga kotor = Pokok × (bunga/tahun) × (tenor_hari / pembagi)
                $bungaKotor  = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
                $pajak       = $bungaKotor * 0.20;    // Pajak 20%
                $bungaBersih = $bungaKotor - $pajak;
                $totalDibayar = $pokok + $bungaBersih;

                $tglJatuhTempo = $deposito->tgl_jatuh_tempo->toDateString();

                if ($isDryRun) {
                    $this->line(sprintf(
                        "  [DRY RUN] Deposito %s | Nasabah: %s | Pokok: Rp%s | Total: Rp%s | JT: %s",
                        $deposito->nomor_deposito,
                        $deposito->nasabah->user->nama ?? '-',
                        number_format($pokok, 0, ',', '.'),
                        number_format($totalDibayar, 0, ',', '.'),
                        $tglJatuhTempo
                    ));
                    $generated++;
                    continue;
                }

                DB::beginTransaction();

                // Insert ke deposito_persiapan_cair
                DepositoPersiapanCair::create([
                    'deposito_id'     => $deposito->id,
                    'nasabah_id'      => $deposito->id_nasabah,
                    'pokok'           => $pokok,
                    'bunga_kotor'     => round($bungaKotor, 2),
                    'pajak'           => round($pajak, 2),
                    'bunga_bersih'    => round($bungaBersih, 2),
                    'total_dibayar'   => round($totalDibayar, 2),
                    'metode_cair'     => 'saldo_tabungan', // default aman
                    'status'          => 'tentatif',
                    'tgl_peringatan'  => $today,
                    'tgl_target_cair' => $tglJatuhTempo,
                ]);

                // Update status_peringatan di DepositoH
                $deposito->update([
                    'status_peringatan' => 'need_prepare',
                    'tgl_peringatan'    => $today,
                ]);

                // 5. Buat Notifikasi untuk Owner
                AdminNotification::notify(
                    'deposito_warning',
                    '🛡️ Persiapan Dana Deposito',
                    "Deposito {$deposito->nomor_deposito} nasabah {$deposito->nasabah->user->nama} akan jatuh tempo pada {$tglJatuhTempo}. Segera siapkan dana sebesar Rp " . number_format($totalDibayar, 0, ',', '.'),
                    route('admin.deposito.peringatan.index'),
                    (string) $deposito->id,
                    'deposito_h'
                );

                DB::commit();

                $this->line(sprintf(
                    "  ✅ Deposito %s | Jatuh Tempo: %s | Total: Rp%s",
                    $deposito->nomor_deposito,
                    $tglJatuhTempo,
                    number_format($totalDibayar, 0, ',', '.')
                ));

                $generated++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errors++;
                $this->error("  ❌ Error pada deposito #{$deposito->id}: " . $e->getMessage());
                Log::error('GenerateDepositoPeringatan error', [
                    'deposito_id' => $deposito->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("📊 Selesai. Generated: {$generated} | Error: {$errors}");

        if ($isDryRun) {
            $this->warn('⚠️  Mode dry-run aktif. Tidak ada data yang disimpan.');
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
