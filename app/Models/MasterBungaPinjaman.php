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
        'bunga_persen',
        'status_aktif',
        'keterangan',
    ];

    protected $casts = [
        'durasi_min' => 'integer',
        'durasi_max' => 'integer',
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
}
