<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogoBank extends Model
{
    protected $table = 'logo_bank';

    protected $fillable = [
        'nama_bank',
        'logo_url',
    ];
}
