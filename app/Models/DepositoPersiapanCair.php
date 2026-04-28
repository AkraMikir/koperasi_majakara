<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositoPersiapanCair extends Model
{
    use HasFactory;

    protected $table = 'deposito_persiapan_cair';

    protected $fillable = [
        'deposito_id',
        'nasabah_id',
        'pokok',
        'bunga_kotor',
        'pajak',
        'bunga_bersih',
        'total_dibayar',
        'metode_cair',
        'status',
        'tgl_peringatan',
        'tgl_target_cair',
        'pencairan_id',
        'catatan',
    ];

    protected $casts = [
        'pokok'          => 'decimal:2',
        'bunga_kotor'    => 'decimal:2',
        'pajak'          => 'decimal:2',
        'bunga_bersih'   => 'decimal:2',
        'total_dibayar'  => 'decimal:2',
        'tgl_peringatan' => 'date',
        'tgl_target_cair'=> 'date',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    /* ── Relationships ── */

    public function deposito(): BelongsTo
    {
        return $this->belongsTo(DepositoH::class, 'deposito_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function pencairan(): BelongsTo
    {
        return $this->belongsTo(PencairanDeposito::class, 'pencairan_id');
    }

    /* ── Helpers ── */

    public function isTentatif(): bool
    {
        return $this->status === 'tentatif';
    }

    public function isDiproses(): bool
    {
        return $this->status === 'diproses';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    /** Perlu dana fisik Owner (transfer atau tunai ke Admin) */
    public function needsFisik(): bool
    {
        return in_array($this->metode_cair, ['rek_nasabah', 'petty_cash_operator']);
    }

    /** Pencairan via Petty Cash Admin Operasional */
    public function needsPettyCash(): bool
    {
        return $this->metode_cair === 'petty_cash_operator';
    }

    /** Pencairan via transfer bank ke rekening nasabah */
    public function needsTransfer(): bool
    {
        return $this->metode_cair === 'rek_nasabah';
    }

    /** Pencairan ke saldo tabungan (tidak perlu dana fisik) */
    public function isTabungan(): bool
    {
        return $this->metode_cair === 'saldo_tabungan';
    }

    /* ── Scopes ── */

    public function scopeTentatif($query)
    {
        return $query->where('status', 'tentatif');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeNeedsFisik($query)
    {
        return $query->whereIn('metode_cair', ['rek_nasabah', 'petty_cash_operator']);
    }

    public function scopeByTargetDate($query, string $date)
    {
        return $query->where('tgl_target_cair', $date);
    }
}
