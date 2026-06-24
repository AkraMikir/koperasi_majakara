<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBungaPinjaman extends Model
{
    use HasFactory;

    protected $table = 'master_bunga_pinjaman';

    protected $fillable = [
        'durasi_min',
        'durasi_max',
        'durasi_pilihan',
        'bunga_persen',
        'status_aktif',
        'keterangan',
    ];

    protected $casts = [
        'durasi_min' => 'integer',
        'durasi_max' => 'integer',
        'durasi_pilihan' => 'integer',
        'bunga_persen' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get bunga berdasarkan durasi pinjaman
     */
    public static function getBungaByDurasi($durasi)
    {
        return self::where('status_aktif', true)
            ->where('durasi_min', '<=', $durasi)
            ->where('durasi_max', '>=', $durasi)
            ->first();
    }

    /**
     * Get daftar opsi durasi pinjaman yang valid (dinamis dari database)
     */
    public static function getOpsiDurasi()
    {
        $masterBunga = self::where('status_aktif', true)->orderBy('durasi_min')->get();
        
        // Periksa apakah ada record yang mengisi durasi_pilihan
        $hasPilihan = $masterBunga->contains(function ($value) {
            return $value->durasi_pilihan !== null && $value->durasi_pilihan !== '';
        });

        $options = collect();
        foreach ($masterBunga as $b) {
            if ($hasPilihan) {
                // Hanya ambil yang mendefinisikan durasi_pilihan secara eksplisit
                if ($b->durasi_pilihan !== null && $b->durasi_pilihan !== '') {
                    $options->push($b->durasi_pilihan);
                }
            } else {
                // Fallback ke durasi_max (atau durasi_min jika nilainya sama) jika semua data null
                if ($b->durasi_min === $b->durasi_max) {
                    $options->push($b->durasi_min);
                } else {
                    $options->push($b->durasi_max);
                }
            }
        }
        return $options->unique()->sort()->values();
    }
}
