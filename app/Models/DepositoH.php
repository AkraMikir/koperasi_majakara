<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DepositoH extends Model
{
    use HasFactory;

    protected $table = 'tbl_deposito_h';

    protected $fillable = [
        'id_pengajuan',
        'id_nasabah',
        'paket_id',
        'nomor_deposito',
        'nominal_awal',
        'tenor_id',
        'bunga',
        'tgl_mulai',
        'tgl_jatuh_tempo',
        'metode_pencairan',
        'status',
        'status_peringatan',
        'tgl_peringatan',
    ];

    protected $casts = [
        'nominal_awal' => 'decimal:2',
        'bunga' => 'decimal:4',
        'tgl_mulai' => 'datetime',
        'tgl_jatuh_tempo' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanDeposito::class, 'id_pengajuan');
    }

    public function tenor(): BelongsTo
    {
        return $this->belongsTo(JnsTenorDeposito::class, 'tenor_id');
    }

    public function bungaHarian(): HasMany
    {
        return $this->hasMany(DepositoBungaHarian::class, 'deposito_id');
    }

    public function pencairan(): HasOne
    {
        return $this->hasOne(PencairanDeposito::class, 'deposito_id');
    }

    public function transDeposito(): HasMany
    {
        return $this->hasMany(TransDeposito::class, 'deposito_id');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemuDeposito::class, 'deposito_id');
    }

    public function persiapanCair(): HasMany
    {
        return $this->hasMany(DepositoPersiapanCair::class, 'deposito_id');
    }

    /**
     * Apakah deposito ini perlu mendapat warning berdasarkan threshold hari.
     */
    public function needsWarning(int $days = 7): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }
        return $this->tgl_jatuh_tempo !== null
            && $this->tgl_jatuh_tempo->lte(now()->addDays($days));
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketDeposito::class, 'paket_id');
    }
}



