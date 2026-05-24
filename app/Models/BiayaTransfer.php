<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaTransfer extends Model
{
    use HasFactory;

    protected $table = 'biaya_transfer';

    protected $fillable = [
        'bank_pengirim',
        'bank_penerima',
        'biaya_admin',
        'min_saldo_non_bca',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'biaya_admin'       => 'decimal:2',
        'min_saldo_non_bca' => 'decimal:2',
        'is_active'         => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
