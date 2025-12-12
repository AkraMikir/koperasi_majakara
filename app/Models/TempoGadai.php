<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempoGadai extends Model
{
    use HasFactory;

    protected $table = 'tempo_gadai';

    protected $fillable = [
        'gadai_id',
        'nasabah_id',
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

    public function gadai(): BelongsTo
    {
        return $this->belongsTo(GadaiH::class, 'gadai_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}



