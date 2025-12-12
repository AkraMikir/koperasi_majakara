<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_gadai';

    protected $fillable = [
        'id_nasabah',
        'id_item_gadai',
        'nominal_diajukan',
        'metode',
        'foto_bukti_barang',
        'catatan',
        'status',
    ];

    protected $casts = [
        'nominal_diajukan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function itemGadai(): BelongsTo
    {
        return $this->belongsTo(ItemGadai::class, 'id_item_gadai');
    }

    public function gadai(): HasOne
    {
        return $this->hasOne(GadaiH::class, 'id_pengajuan');
    }
}



