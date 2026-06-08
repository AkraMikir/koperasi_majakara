<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiMasterKategori extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_master_kategori';

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'rate_jasa',
        'rate_denda',
        'rate_inap_persen',
        'max_extend_default',
        'masa_gadai_hari',
        'masa_tenggang_hari',
        'countdown_ambil_hari'
    ];

    public function items()
    {
        return $this->hasMany(GadaiMasterItem::class, 'kategori_id');
    }
}
