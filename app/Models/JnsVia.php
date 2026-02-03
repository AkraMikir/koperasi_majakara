<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsVia extends Model
{
    use HasFactory;

    protected $table = 'jns_via';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
