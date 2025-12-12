<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiSpesial extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_spesial';

    protected $fillable = [
        'nama',
        'tmpl_250_ribu',
        'tmpl_500_ribu',
        'tmpl_1_juta',
        'tmpl_2_juta',
        'tmpl_3_juta',
        'tmpl_4_juta',
        'tmpl_lebih_dari_5_juta',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}



