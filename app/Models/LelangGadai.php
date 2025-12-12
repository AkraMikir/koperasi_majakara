<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LelangGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_lelang_gadai';

    protected $fillable = [
        'gadai_id',
        'id_item_gadai',
        'harga_laku',
        'selisih_ke_nasabah',
        'catatan',
        'status',
    ];

    protected $casts = [
        'harga_laku' => 'decimal:2',
        'selisih_ke_nasabah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gadai(): BelongsTo
    {
        return $this->belongsTo(GadaiH::class, 'gadai_id');
    }

    public function itemGadai(): BelongsTo
    {
        return $this->belongsTo(ItemGadai::class, 'id_item_gadai');
    }
}



