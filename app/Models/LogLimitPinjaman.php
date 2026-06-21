<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogLimitPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_log_limit_pinjaman';

    protected $fillable = [
        'id_nasabah',
        'id_user_admin',
        'limit_sebelum',
        'limit_sesudah',
        'keterangan',
    ];

    protected $casts = [
        'limit_sebelum' => 'decimal:2',
        'limit_sesudah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_admin');
    }
}
