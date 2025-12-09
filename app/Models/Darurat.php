<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Darurat extends Model
{
    use HasFactory;

    protected $table = 'tbl_darurat';

    protected $fillable = [
        'id_nasabah',
        'nama_lengkap',
        'hubungan_peminjam',
        'no_telepon',
        'alamat',
        'pekerjaan',
        'email',
        'no_ktp',
        'foto_ktp',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the nasabah that owns the darurat.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }
}


