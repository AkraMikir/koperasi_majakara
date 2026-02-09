<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiFoto extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'owner_id',
        'owner_fitur',
        'owner_trans',
        'file_path',
        'keterangan',
    ];
}
