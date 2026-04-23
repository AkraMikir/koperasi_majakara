<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashOwnerTransaksi extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_owner_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'tipe',
        'nominal_cash',
        'nominal_tf',
        'keterangan',
        'bukti_foto_cash',
        'bukti_foto_tf',
        'ref_id',
        'ref_table',
    ];

    protected $casts = [
        'nominal_cash' => 'decimal:2',
        'nominal_tf'   => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
