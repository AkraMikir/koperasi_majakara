<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JnsTenorDeposito extends Model
{
    use HasFactory;

    protected $table = 'jns_tenor_deposito';

    protected $fillable = [
        'tenor_hari',
        'tenor_bulan',
        'aktif',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sukuBunga(): HasMany
    {
        return $this->hasMany(SukuBungaDeposito::class, 'tenor_id');
    }
}



