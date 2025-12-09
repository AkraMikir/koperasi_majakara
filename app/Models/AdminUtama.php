<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUtama extends Model
{
    use HasFactory;

    protected $table = 'admin_utama';

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the admin utama.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


