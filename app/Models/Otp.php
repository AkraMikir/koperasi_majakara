<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'tbl_otp';
    
    // Disable updated_at since table only has created_at
    public $timestamps = false;
    
    // Set created_at manually
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expired_at',
        'is_verified',
        'type',
        'channel',
        'phone_number',
        'session_id',
        'created_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];
    
    // Set dates for date handling
    protected $dates = [
        'expired_at',
        'created_at',
    ];

    /**
     * Get the user that owns the otp.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


