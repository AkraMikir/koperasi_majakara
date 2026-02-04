<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemuTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_janji_temu_tabungan';

    protected $fillable = [
        'id_pengajuan',
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'datetime',
        'waktu_janji_temu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanTabungan::class, 'id_pengajuan');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function transTabungan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransTabungan::class, 'id_janji_temu_tabungan');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }
}



