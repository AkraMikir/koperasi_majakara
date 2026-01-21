<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PekerjaanTemp extends Model
{
    use HasFactory;

    protected $table = 'tbl_pekerjaan_temp';

    protected $fillable = [
        'nasabah_id',
        'pekerjaan',
        'penghasilan',
        'nama_perusahaan',
        'nama_bank',
    ];

    // Penghasilan sekarang menggunakan string (range), tidak perlu cast

    /**
     * Get the nasabah temp that owns the pekerjaan temp.
     */
    public function nasabahTemp(): BelongsTo
    {
        return $this->belongsTo(NasabahTemp::class, 'nasabah_id');
    }
}
