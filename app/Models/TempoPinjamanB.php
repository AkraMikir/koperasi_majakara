<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempoPinjamanB extends Model
{
    use HasFactory;

    protected $table = 'tempo_pinjaman_b';

    protected $fillable = [
        'pinjaman_id',
        'anggota_id',
        'no_urut',
        'tgl_jatuh_tempo',
        'jumlah_tagihan',
        'jumlah_terbayar',
        'status_bayar',
    ];

    protected $casts = [
        'tgl_jatuh_tempo' => 'datetime',
        'jumlah_tagihan' => 'decimal:2',
        'jumlah_terbayar' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PinjamanH::class, 'pinjaman_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'anggota_id');
    }
}



