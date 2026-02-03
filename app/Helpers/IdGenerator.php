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
     * @param string $tableName Nama tabel untuk cek sequence terakhir
     * @param string $kodeFitur Kode fitur dari Master (T, P, D, G)
     * @param string $kodeVia Kode via dari Master (T, C) - Optional
     * @param string $kodeTrans Kode transaksi dari Master (STR, PNR, dll) - Optional
     * @param string|\DateTime $date Tanggal transaksi (default now)
     * @return string
     */
    public static function generate($tableName, $kodeFitur, $kodeVia = '', $kodeTrans = '', $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $dateStr = $date->format('dmY'); // 30012026
        
        // Pola prefix tanggal untuk pencarian sequence
        $prefix = $dateStr;
        
        // Cari max ID hari ini di tabel yang bersangkutan
        // Asumsi ID selalu diawali tanggal
        $lastRecord = DB::table($tableName)
            ->where('id', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(id) DESC') // Antisipasi panjang beda
            ->orderBy('id', 'DESC')
            ->first();
            
        $nextSeq = 1;
        
        if ($lastRecord) {
            // Ambil 12 karakter pertama (8 tanggal + 4 seq)
            // 300120260001
            $lastId = $lastRecord->id;
            $lastSeq = substr($lastId, 8, 4);
            if (is_numeric($lastSeq)) {
                $nextSeq = intval($lastSeq) + 1;
            }
        }
        
        $seqStr = str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        
        // Construct final ID
        // 300120260001 + T + T + STR
        return $prefix . $seqStr . $kodeFitur . $kodeVia . $kodeTrans;
    }
}
