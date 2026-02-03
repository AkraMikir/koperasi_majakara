<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiFoto extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto';

    protected $fillable = [
        'owner_id',
        'owner_fitur',
        'owner_trans',
        'file_path',
        'keterangan',
    ];
}
