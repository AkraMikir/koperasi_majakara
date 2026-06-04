<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDataBankRegis extends Model
{
    protected $table = 'master_data_bank_regis';

    protected $fillable = [
        'nama_bank',
        'kode_bank',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
