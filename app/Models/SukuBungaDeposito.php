<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SukuBungaDeposito extends Model
{
    use HasFactory;

    protected $table = 'suku_bunga_deposito';

    protected $fillable = [
        'tenor_id',
        'min_nominal',
        'max_nominal',
        'bunga',
        'status',
    ];

    protected $casts = [
        'min_nominal' => 'decimal:2',
        'max_nominal' => 'decimal:2',
        'bunga' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tenor(): BelongsTo
    {
        return $this->belongsTo(JnsTenorDeposito::class, 'tenor_id');
    }
}



