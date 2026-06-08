<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiMasterInapKendaraan extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_master_inap_kendaraan';

    protected $fillable = [
        'golongan',
        'jenis_kendaraan',
        'nominal_inap',
        'keterangan'
    ];

    public function getContohItemAttribute()
    {
        return GadaiMasterItem::where('nominal_inap', $this->nominal_inap)
            ->pluck('head_1')
            ->join(', ') ?: '-';
    }
}
