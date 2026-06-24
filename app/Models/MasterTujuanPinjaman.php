<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTujuanPinjaman extends Model
{
    protected $table = 'master_tujuan_pinjaman';

    protected $fillable = [
        'tujuan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
