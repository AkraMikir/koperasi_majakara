<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JnsBank extends Model
{
    protected $table = 'jns_bank';

    protected $fillable = [
        'pemilik',
        'nama',
        'no_rek',
        'bank',
    ];
}
