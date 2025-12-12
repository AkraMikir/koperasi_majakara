<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransDeposito extends Model
{
    use HasFactory;

    protected $table = 'trans_deposito';

    protected $fillable = [
        'deposito_id',
        'jenis',
        'nominal',
        'keterangan',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(DepositoH::class, 'deposito_id');
    }
}



