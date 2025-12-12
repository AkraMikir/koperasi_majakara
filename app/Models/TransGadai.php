<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransGadai extends Model
{
    use HasFactory;

    protected $table = 'trans_gadai';

    protected $fillable = [
        'gadai_id',
        'nasabah_id',
        'jenis',
        'nominal',
        'keterangan',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gadai(): BelongsTo
    {
        return $this->belongsTo(GadaiH::class, 'gadai_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}



