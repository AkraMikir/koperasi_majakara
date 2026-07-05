<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDefaultOtp extends Model
{
    use HasFactory;

    protected $table = 'master_default_otp';

    protected $fillable = [
        'otp_code_hashed',
        'used',
    ];

    protected $casts = [
        'used' => 'integer',
    ];
}
