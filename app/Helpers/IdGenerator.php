<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IdGenerator
{
    /**
     * Generate ID Complex
     * Format: DDMMYYYY + SEQ(4) + FITUR + VIA + TRANS
     * Contoh: 300120260001TTSTR
     * 
     * PENTING: Setiap kombinasi FITUR+VIA+TRANS memiliki sequence sendiri
     * Contoh: 140220260001PTFPNJ dan 140220260001PTFPMB (sama-sama sequence 0001)
     * 
     * @param string $tableName Nama tabel untuk cek sequence terakhir
     * @param string $kodeFitur Kode fitur dari Master (T, P, D, G)
     * @param string $kodeVia Kode via dari Master (TF, CS, TN) - Optional
     * @param string $kodeTrans Kode transaksi dari Master (STR, PNR, dll) - Optional
     * @param string|\DateTime $date Tanggal transaksi (default now)
     * @return string
     */
    public static function generate($tableName, $kodeFitur, $kodeVia = '', $kodeTrans = '', $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $dateStr = $date->format('dmY'); // 30012026
        
        // Suffix untuk kombinasi fitur+via+transaksi
        $suffix = $kodeFitur . $kodeVia . $kodeTrans;
        
        // Cari max ID hari ini di tabel yang bersangkutan dengan suffix yang SAMA
        // Format pattern: DDMMYYYY + #### + SUFFIX
        // Contoh: 14022026 + 0001 + PTFPNJ
        $pattern = $dateStr . '____' . $suffix; // 4 underscore untuk 4 digit sequence
        
        $lastRecord = DB::table($tableName)
            ->where('id', 'like', $pattern)
            ->orderByRaw('LENGTH(id) DESC') // Antisipasi panjang beda
            ->orderBy('id', 'DESC')
            ->first();
            
        $nextSeq = 1;
        
        if ($lastRecord) {
            // Ambil 4 digit sequence (karakter ke-9 sampai ke-12, 0-indexed jadi 8-11)
            // Format ID: DDMMYYYY + SEQSEQSEQSEQ + SUFFIX
            // Contoh: 14022026 + 0001 + PTFPNJ
            $lastId = $lastRecord->id;
            $lastSeq = substr($lastId, 8, 4);
            if (is_numeric($lastSeq)) {
                $nextSeq = intval($lastSeq) + 1;
            }
        }
        
        $seqStr = str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        
        // Construct final ID
        // Format: DDMMYYYY + SEQ(4) + FITUR + VIA + TRANS
        // Contoh: 14022026 + 0001 + P + TF + PNJ = 140220260001PTFPNJ
        return $dateStr . $seqStr . $suffix;
    }
}
