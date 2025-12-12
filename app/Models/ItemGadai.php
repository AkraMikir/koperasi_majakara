<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_item_gadai';

    protected $fillable = [
        'id_nasabah',
        'id_master_barang',
        'tgl_buat',
        'head_1',
        'head_2',
        'nominal_real',
        'bunga_low',
        'nominal_low',
        'bunga_high',
        'nominal_high',
        'file_pic',
    ];

    protected $casts = [
        'tgl_buat' => 'datetime',
        'nominal_real' => 'decimal:2',
        'bunga_low' => 'decimal:4',
        'nominal_low' => 'decimal:2',
        'bunga_high' => 'decimal:4',
        'nominal_high' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function masterBarang(): BelongsTo
    {
        return $this->belongsTo(MBarangGadai::class, 'id_master_barang');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanGadai::class, 'id_item_gadai');
    }

    public function gadai(): HasMany
    {
        return $this->hasMany(GadaiH::class, 'id_item_gadai');
    }
}



