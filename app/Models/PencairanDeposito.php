<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanDeposito extends Model
{
    use HasFactory;

    protected $table = 'tbl_pencairan_deposito';

    protected $fillable = [
        'deposito_id',
        'id_nasabah',
        'nominal_akhir',
        'metode_pencairan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'nominal_akhir' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(DepositoH::class, 'deposito_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }
}



