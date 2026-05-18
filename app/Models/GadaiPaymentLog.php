<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiPaymentLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_payment_log';

    protected $fillable = [
        'gadai_active_id',
        'jenis_pembayaran',
        'nominal',
        'metode',
        'petty_cash_ref'
    ];

    public function gadaiActive()
    {
        return $this->belongsTo(GadaiActive::class, 'gadai_active_id');
    }
}
