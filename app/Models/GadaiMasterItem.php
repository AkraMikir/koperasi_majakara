<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiMasterItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_master_item';

    protected $fillable = [
        'kategori_id',
        'head_1',
        'head_2',
        'file_pic',
        'nominal_real',
        'bunga_low',
        'nominal_low',
        'bunga_high',
        'nominal_high',
        'nominal_inap',
        'is_active',
    ];

    public function kategori()
    {
        return $this->belongsTo(GadaiMasterKategori::class, 'kategori_id');
    }
}
