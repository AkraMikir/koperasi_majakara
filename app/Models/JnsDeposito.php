<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JnsDeposito extends Model
{
    use HasFactory;

    protected $table = 'jns_deposito';

    protected $fillable = [
        'nama_jenis',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanDeposito::class, 'jenis_deposito');
    }
}



