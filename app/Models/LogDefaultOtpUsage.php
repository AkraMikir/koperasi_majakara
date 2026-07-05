<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogDefaultOtpUsage extends Model
{
    use HasFactory;

    protected $table = 'log_default_otp_usage';

    // Disable updated_at as it only has created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'phone_number',
        'session_id',
        'type',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that used this default OTP.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
