<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PettyCashPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_penerimaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'owner_id',
        'admin_id',
        'sumber',
        'nominal_tf',
        'nominal_cash',
        'bukti_tf',
        'foto_cash',
        'status',
        'keterangan',
        'keterangan_admin',
        'tgl_penerimaan',
    ];

    protected $casts = [
        'nominal_tf'      => 'decimal:2',
        'nominal_cash'    => 'decimal:2',
        'nominal_total'   => 'decimal:2',
        'tgl_penerimaan'  => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Saldo mutations connected to this record
    public function saldoMutasi()
    {
        return PettyCashSaldo::where('ref_id', $this->id)
            ->where('ref_table', 'petty_cash_penerimaan');
    }
}
