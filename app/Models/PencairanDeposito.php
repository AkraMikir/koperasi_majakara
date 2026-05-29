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
        'jenis_pencairan',   // rek_nasabah | saldo_tabungan | petty_cash_operator
        'nominal_akhir',
        'metode_pencairan',  // (legacy, keep for compat)
        'foto_bukti_tf',
        'status',            // pending | diproses | selesai | ditolak
        'catatan',
        'approved_by',
        'is_cancel',
        'bank_pengirim',
        'biaya_transfer',
    ];

    protected $casts = [
        'nominal_akhir' => 'decimal:2',
        'is_cancel'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'biaya_transfer' => 'decimal:2',
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
    public function isPending(): bool      { return $this->status === 'pending'; }
    public function isDiproses(): bool     { return $this->status === 'diproses'; }
    public function isSelesai(): bool      { return $this->status === 'selesai'; }
    public function isTf(): bool           { return $this->jenis_pencairan === 'rek_nasabah'; }
    public function isTabungan(): bool     { return $this->jenis_pencairan === 'saldo_tabungan'; }
    public function isPettyCash(): bool    { return $this->jenis_pencairan === 'petty_cash_operator'; }
    public function isCancel(): bool       { return $this->is_cancel; }

    /** Apakah pencairan ini butuh dana fisik/transfer Owner? */
    public function needsFisikOwner(): bool
    {
        return in_array($this->jenis_pencairan, ['rek_nasabah', 'petty_cash_operator']);
    }
}
