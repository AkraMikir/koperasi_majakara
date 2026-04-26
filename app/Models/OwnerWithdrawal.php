<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'owner_withdrawals';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'nominal_cash',
        'nominal_tf',
        'sumber',
        'keterangan',
        'bukti_foto',
    ];

    protected $casts = [
        'nominal_cash' => 'decimal:2',
        'nominal_tf'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
