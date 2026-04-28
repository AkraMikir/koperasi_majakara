<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDeposito extends Model
{
    protected $table = 'kategori_depositos';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
        'status',
    ];
}
