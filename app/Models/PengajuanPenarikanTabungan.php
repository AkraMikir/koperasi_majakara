<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPenarikanTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_penarikan_tabungan';

    protected $fillable = [
        'id_anggota',
        'tgl_pengajuan',
        'nominal',
        'keterangan',
        'status',
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

    public function transTabungan(): HasMany
    {
        return $this->hasMany(TransTabungan::class, 'id_pengajuan_tarik');
    }
}



