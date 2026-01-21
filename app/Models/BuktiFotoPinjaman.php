<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiFotoPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto_pinjaman';

    protected $fillable = [
        'id_pinjaman',
        'id_pengajuan',
        'file_photo',
        'jenis',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PinjamanH::class, 'id_pinjaman');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }
}
