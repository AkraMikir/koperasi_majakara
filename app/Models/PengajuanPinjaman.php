<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_pinjaman';

    protected $fillable = [
        'id_anggota',
        'tgl_pengajuan',
        'nominal',
        'jenis',
        'durasi',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pinjaman(): HasOne
    {
        return $this->hasOne(PinjamanH::class, 'id_pengajuan');
    }
}



