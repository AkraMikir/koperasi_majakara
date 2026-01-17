<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserTemp extends Model
{
    use HasFactory;

    protected $table = 'users_temp';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'pin',
        'password',
        'nomor_hp',
        'foto',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the user temp.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the nasabah temp for the user temp.
     */
    public function nasabahTemp(): HasOne
    {
        return $this->hasOne(NasabahTemp::class, 'user_id');
    }
}
