<?php

namespace App\Traits;

use Carbon\Carbon;

trait CalculatesDenda
{
    /**
     * Hitung sisa tagihan (Jumlah Tagihan - Jumlah Terbayar).
     */
    public function getSisaTagihanAttribute()
    {
        return max(0, $this->jumlah_tagihan - ($this->jumlah_terbayar ?? 0));
    }

    /**
     * Hitung jumlah hari telat (H+1 setelah jatuh tempo).
     */
    public function hitungHariTelat()
    {
        $now = Carbon::now()->startOfDay();
        $tanggalJatuhTempo = Carbon::parse($this->tgl_jatuh_tempo)->startOfDay();

        // Jika hari ini masih pada tanggal jatuh tempo atau sebelumnya, belum telat
        if ($now->lessThanOrEqualTo($tanggalJatuhTempo)) {
            return 0;
        }

        // Jika jatuh tempo tgl 1 dan sekarang tgl 2, maka diffInDays = 1 (telat 1 hari)
        return (int) $tanggalJatuhTempo->diffInDays($now);
    }

    /**
     * Hitung denda untuk angsuran yang telat.
     * 
     * Aturan:
     * - Denda 0.3% per hari dari POKOK ANGSURAN per bulan
     * - Denda mulai dihitung 1 hari SETELAH tanggal jatuh tempo (H+1)
     * - Denda BERHENTI jika sudah ada pembayaran (walaupun Rp 1)
     */
    public function hitungDenda()
    {
        // Jika sudah lunas, return denda yang tersimpan di DB
        if ($this->status_bayar === 'lunas') {
            return $this->denda ?? 0;
        }

        // Jika sudah ada pembayaran (walaupun sebagian), denda BERHENTI
        if ($this->jumlah_terbayar > 0) {
            return $this->denda ?? 0;
        }

        $pinjaman = $this->pinjaman;
        if (!$pinjaman) {
            return 0;
        }

        // Hitung hari telat menggunakan method terpusat
        $hariTelat = $this->hitungHariTelat();
        
        if ($hariTelat <= 0) {
            return 0;
        }

        // Get denda persen dari pinjaman (Default 0.3% per hari)
        $dendaPersen = $pinjaman->denda_persen ?? 0.30;
        
        // Pokok per bulan = jumlah_pinjam / lama_pinjam
        $pokokPerBulan = $pinjaman->jumlah_pinjam / $pinjaman->lama_pinjam;
        
        // Denda = POKOK per bulan × (denda_persen / 100) × hari_telat
        $denda = $pokokPerBulan * ($dendaPersen / 100) * $hariTelat;

        return round($denda, 2);
    }

    /**
     * Catat pembayaran dan update status serta denda secara otomatis.
     * Centralized logic to prevent duplication in controllers.
     */
    public function applyPayment($nominal)
    {
        // 1. Hitung denda berjalan saat ini
        $dendaBerjalan = $this->hitungDenda();
        // Total tagihan keseluruhan (Pokok Angsuran + Denda)
        $totalTagihanDanDenda = $this->jumlah_tagihan + $dendaBerjalan;

        // 2. Hitung jumlah terbayar baru
        $nominal = (float) $nominal;
        $jumlahTerbayarBaru = ($this->jumlah_terbayar ?? 0) + $nominal;

        $statusBayar = 'belum';
        $tglBayar = now();
        $dendaBaru = $dendaBerjalan;

        // 3. Cek apakah lunas (melebihi atau sama dengan tagihan + denda)
        if ($jumlahTerbayarBaru >= $totalTagihanDanDenda) {
            $statusBayar = 'lunas';
            $jumlahTerbayarBaru = $totalTagihanDanDenda;
            // Jika lunas, denda yang tersimpan adalah denda yang dibayar
            // Namun beberapa bagian kode lama mereset ke 0, kita ikuti agar aman 
            // tapi sebenarnya lebih baik menyimpan nilainya. 
            // Kita gunakan $dendaBerjalan agar tercatat.
            $dendaBaru = $dendaBerjalan; 
        } else {
            // Jika belum lunas, cek apakah telat
            $statusBayar = $this->hitungHariTelat() > 0 ? 'telat' : 'belum';
            // Denda "terkunci" jika ada pembayaran masuk (sesuai aturan denda berhenti jika sudah ada bayar)
            if ($jumlahTerbayarBaru > 0) {
                $dendaBaru = $dendaBerjalan;
            }
        }

        // 4. Update data
        $this->update([
            'jumlah_terbayar' => $jumlahTerbayarBaru,
            'denda' => $dendaBaru,
            'status_bayar' => $statusBayar,
            'tgl_bayar' => $tglBayar,
        ]);

        // 5. Check pelunasan pinjaman induk
        if ($this->pinjaman) {
            $this->pinjaman->checkAndUpdateLunasStatus();
        }

        return $this;
    }
}
