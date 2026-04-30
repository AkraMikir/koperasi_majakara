<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanDeposito extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_deposito';

    protected $fillable = [
        'id_nasabah',
        'paket_id',
        'nominal',
        'tenor_id',
        'metode_setor',
        'foto_bukti_tf',
        'status',
        'catatan',
        'catatan_admin',
        'approved_by',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function tenor(): BelongsTo
    {
        return $this->belongsTo(JnsTenorDeposito::class, 'tenor_id');
    }

    public function deposito(): HasOne
    {
        return $this->hasOne(DepositoH::class, 'id_pengajuan');
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketDeposito::class, 'paket_id');
    }
}



