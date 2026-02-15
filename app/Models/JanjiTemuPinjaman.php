<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JanjiTemuPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_janji_temu_pinjaman';

    protected $fillable = [
        'id',
        'id_pengajuan',
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',
        'status',
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
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function buktiFoto(): HasMany
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }
}
