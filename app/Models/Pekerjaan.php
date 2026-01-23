<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pekerjaan';

    protected $fillable = [
        'nasabah_id',
        'pekerjaan',
        'penghasilan',
        'nama_perusahaan',
    ];

    // Penghasilan sekarang menggunakan string (range), tidak perlu cast

    /**
     * Get the nasabah that owns the pekerjaan.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}


