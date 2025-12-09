<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataRek extends Model
{
    use HasFactory;

    protected $table = 'tbl_data_rek';

    protected $fillable = [
        'nasabah_id',
        'no_rekening',
        'nama_pemilik_rekening',
        'nama_bank',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the nasabah that owns the data rek.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}

