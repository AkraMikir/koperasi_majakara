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

        // Update angsuran bulanan
        $angsuranBulanan = TempoPinjamanB::where('status_bayar', '!=', 'lunas')
            ->where('tgl_jatuh_tempo', '<', $now)
            ->get();

        foreach ($angsuranBulanan as $angsuran) {
            if ($angsuran->status_bayar !== 'telat') {
                $angsuran->update(['status_bayar' => 'telat']);
                $updated++;
                
                // Hitung dan update denda
                $denda = $this->hitungDenda($angsuran);
                $angsuran->update(['denda' => $denda]);
            } else {
                // Update denda untuk angsuran yang sudah telat
                $denda = $this->hitungDenda($angsuran);
                if ($angsuran->denda != $denda) {
                    $angsuran->update(['denda' => $denda]);
                    $updated++;
                }
            }
        }

        // Update angsuran mingguan
        $angsuranMingguan = TempoPinjamanM::where('status_bayar', '!=', 'lunas')
            ->where('tgl_jatuh_tempo', '<', $now)
            ->get();

        foreach ($angsuranMingguan as $angsuran) {
            if ($angsuran->status_bayar !== 'telat') {
                $angsuran->update(['status_bayar' => 'telat']);
                $updated++;
                
                // Hitung dan update denda
                $denda = $this->hitungDenda($angsuran);
                $angsuran->update(['denda' => $denda]);
            } else {
                // Update denda untuk angsuran yang sudah telat
                $denda = $this->hitungDenda($angsuran);
                if ($angsuran->denda != $denda) {
                    $angsuran->update(['denda' => $denda]);
                    $updated++;
                }
            }
        }

        // Check pinjaman yang sudah lunas (semua angsuran sudah lunas)
        $pinjamanBelumLunas = PinjamanH::where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->with(['tempoBulanan', 'tempoMingguan'])
            ->get();

        foreach ($pinjamanBelumLunas as $pinjaman) {
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
                $this->info("Pinjaman #{$pinjaman->id} telah lunas");
            }
        }

        $this->info("Selesai! Total {$updated} angsuran di-update.");
        
        return 0;
    }

    /**
     * Hitung denda untuk angsuran yang telat.
     */
    private function hitungDenda($angsuran)
    {
        if ($angsuran->status_bayar === 'lunas') {
            return 0;
        }

        $hariTelat = Carbon::now()->diffInDays($angsuran->tgl_jatuh_tempo, false);
        
        if ($hariTelat <= 0) {
            return 0;
        }

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return 0;
        }

        $dendaPersen = $pinjaman->denda_persen ?? 0.02;
        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        $denda = $sisaTagihan * ($dendaPersen / 100) * $hariTelat;
        $dendaMax = $angsuran->jumlah_tagihan * 0.5;
        
        return round(min($denda, $dendaMax), 2);
    }
}
