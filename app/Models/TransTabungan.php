<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransTabungan extends Model
{
    use HasFactory;

    protected $table = 'trans_tabungan';

    protected $fillable = [
        'id_pengajuan_setor',
        'id_pengajuan_tarik',
        'id_anggota',
        'nominal',
        'keterangan',
        'jenis',
        'via',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pengajuanSetor(): BelongsTo
    {
        return $this->belongsTo(PengajuanTabungan::class, 'id_pengajuan_setor');
    }

    public function pengajuanTarik(): BelongsTo
    {
        return $this->belongsTo(PengajuanPenarikanTabungan::class, 'id_pengajuan_tarik');
    }
}



