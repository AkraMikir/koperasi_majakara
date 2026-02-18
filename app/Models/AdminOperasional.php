<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminOperasional extends Model
{
    use HasFactory;

    protected $table = 'admin_operasional';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Get the user that owns the admin operasional.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


