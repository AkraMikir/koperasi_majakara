<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_tabungan';

    protected $fillable = [
        'id_anggota',
        'nominal',
        'foto_bukti_tf',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function buktiFoto(): HasMany
    {
        return $this->hasMany(BuktiFotoTabungan::class, 'id_pengajuan');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemuTabungan::class, 'id_pengajuan');
    }

    public function transTabungan(): HasMany
    {
        return $this->hasMany(TransTabungan::class, 'id_pengajuan_setor');
    }
}



