<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StrukNo extends Model
{
    protected $table = 'tbl_struk_no';

    protected $fillable = [
        'pinjaman_id',
        'gadai_id',
        'no_global',
        'no_harian_all',
        'no_harian_jenis',
        'no_struk',
        'jenis',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(PinjamanH::class, 'pinjaman_id');
    }

    public function gadai()
    {
        return $this->belongsTo(GadaiActive::class, 'gadai_id');
    }

    /**
     * Generate atau ambil no struk yang sudah ada (idempotent).
     *
     * Format: {no_global:4d}{no_harian_all:4d}{no_harian_jenis:4d}
     * Contoh: 011100220010
     *
     * @param string $jenis  'pinjaman' atau 'gadai'
     * @param string|int $refId  id pinjaman (string) atau id gadai (int)
     * @return string
     */
    public static function getOrCreate(string $jenis, $refId): string
    {
        $field = $jenis === 'pinjaman' ? 'pinjaman_id' : 'gadai_id';

        // Cek apakah sudah ada
        $existing = self::where($field, $refId)->first();
        if ($existing) {
            return $existing->no_struk;
        }

        // Hitung counters dalam satu transaksi agar thread-safe
        return DB::transaction(function () use ($jenis, $field, $refId) {
            $today = now()->toDateString();

            // no_global: total semua struk B5 yang pernah dicetak
            $noGlobal = self::count() + 1;

            // no_harian_all: semua struk B5 hari ini
            $noHarianAll = self::where('tanggal', $today)->count() + 1;

            // no_harian_jenis: struk jenis ini (pinjaman/gadai) hari ini
            $noHarianJenis = self::where('tanggal', $today)
                ->where('jenis', $jenis)
                ->count() + 1;

            // Format: 4 digit masing-masing, zero-padded
            $noStruk = str_pad($noGlobal, 4, '0', STR_PAD_LEFT)
                . str_pad($noHarianAll, 4, '0', STR_PAD_LEFT)
                . str_pad($noHarianJenis, 4, '0', STR_PAD_LEFT);

            self::create([
                'pinjaman_id'      => $jenis === 'pinjaman' ? $refId : null,
                'gadai_id'         => $jenis === 'gadai' ? $refId : null,
                'no_global'        => $noGlobal,
                'no_harian_all'    => $noHarianAll,
                'no_harian_jenis'  => $noHarianJenis,
                'no_struk'         => $noStruk,
                'jenis'            => $jenis,
                'tanggal'          => $today,
            ]);

            return $noStruk;
        });
    }

    /**
     * Format no_struk untuk ditampilkan: pisahkan tiap 4 digit dengan spasi
     * Contoh: '011100220010' → 'NO. 0111 0022 0010'
     */
    public static function formatDisplay(string $noStruk): string
    {
        return 'NO. ' . wordwrap($noStruk, 4, ' ', true);
    }
}
