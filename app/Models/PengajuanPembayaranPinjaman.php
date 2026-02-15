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
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_anggota',
        'pinjaman_id',
        'tempo_id',
        'jenis_tempo',
        'nominal',
        'metode_pembayaran',
        'rekening_tujuan',
        'keterangan',
        'keterangan_admin',
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

    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }
}
