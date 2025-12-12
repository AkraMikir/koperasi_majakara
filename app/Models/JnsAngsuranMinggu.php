<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsAngsuranMinggu extends Model
{
    use HasFactory;

    protected $table = 'jns_angsuran_minggu';

    protected $fillable = [
        'ket',
        'aktif',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}



