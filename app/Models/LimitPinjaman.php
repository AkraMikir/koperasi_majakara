<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LimitPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_limit_pinjaman';

    protected $fillable = [
        'id_nasabah',
        'limit_nominal',
        'nominal_terpakai',
    ];

    protected $casts = [
        'limit_nominal' => 'decimal:2',
        'nominal_terpakai' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }
}
