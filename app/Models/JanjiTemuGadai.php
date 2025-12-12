<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemuGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_janji_temu_gadai';

    protected $fillable = [
        'gadai_id',
        'lokasi_temu',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'catatan',
    ];

    protected $casts = [
        'tanggal_janji_temu' => 'datetime',
        'waktu_janji_temu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gadai(): BelongsTo
    {
        return $this->belongsTo(GadaiH::class, 'gadai_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }
}



