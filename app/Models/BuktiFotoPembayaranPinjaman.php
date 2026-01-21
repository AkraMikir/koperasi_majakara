<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiFotoPembayaranPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto_pembayaran_pinjaman';

    protected $fillable = [
        'id_pengajuan',
        'file_photo',
        'jenis',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPembayaranPinjaman::class, 'id_pengajuan');
    }
}
