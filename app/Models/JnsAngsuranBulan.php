<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsAngsuranBulan extends Model
{
    use HasFactory;

    protected $table = 'jns_angsuran_bulan';

    protected $fillable = [
        'bulan',
        'ket',
        'aktif',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}



