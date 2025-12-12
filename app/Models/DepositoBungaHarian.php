<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositoBungaHarian extends Model
{
    use HasFactory;

    protected $table = 'deposito_bunga_harian';

    protected $fillable = [
        'deposito_id',
        'tanggal',
        'bunga_harian',
        'saldo_akhir',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'bunga_harian' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(DepositoH::class, 'deposito_id');
    }
}



