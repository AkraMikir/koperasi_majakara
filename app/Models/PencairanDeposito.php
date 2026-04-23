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
        'jenis_pencairan',   // rek_nasabah | saldo_tabungan
        'nominal_akhir',
        'metode_pencairan',  // (legacy, keep for compat)
        'foto_bukti_tf',
        'status',            // pending | diproses | selesai | ditolak
        'catatan',
        'approved_by',
    ];

    protected $casts = [
        'nominal_akhir' => 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(DepositoH::class, 'deposito_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    /* ── helpers ── */
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isSelesai(): bool   { return $this->status === 'selesai'; }
    public function isTf(): bool        { return $this->jenis_pencairan === 'rek_nasabah'; }
    public function isTabungan(): bool  { return $this->jenis_pencairan === 'saldo_tabungan'; }
}
