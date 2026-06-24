<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyaratKetentuanLayananGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_syarat_ketentuan_layanan_gadai';

    protected $fillable = [
        'konten',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
