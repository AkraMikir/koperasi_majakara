<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'tbl_otp';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expired_at',
        'is_verified',
        'type',
        'channel',
        'phone_number',
        'session_id',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the otp.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


