<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPembayaranPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_pembayaran_pinjaman';

    protected $fillable = [
        'id_anggota',
        'pinjaman_id',
        'tempo_id',
        'jenis_tempo',
        'nominal',
        'rekening_tujuan',
        'keterangan',
        'status',
        'tgl_pembayaran',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_pembayaran' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PinjamanH::class, 'pinjaman_id');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemuPembayaranPinjaman::class, 'id_pengajuan');
    }

    public function buktiFoto(): HasMany
    {
        return $this->hasMany(BuktiFotoPembayaranPinjaman::class, 'id_pengajuan');
    }
}
