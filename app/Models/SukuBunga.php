<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SukuBunga extends Model
{
    use HasFactory;

    protected $table = 'suku_bunga';

    protected $fillable = [
        'jenis_bunga',
        'opsi_val',
    ];

    protected $casts = [
        'opsi_val' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}



