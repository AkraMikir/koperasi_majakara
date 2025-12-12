<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MBarangGadai extends Model
{
    use HasFactory;

    protected $table = 'm_barang_gadai';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function itemGadai(): HasMany
    {
        return $this->hasMany(ItemGadai::class, 'id_master_barang');
    }
}



