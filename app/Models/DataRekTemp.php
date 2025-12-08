<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataRekTemp extends Model
{
    use HasFactory;

    protected $table = 'tbl_data_rek_temp';

    protected $fillable = [
        'nasabah_id',
        'no_rekening',
        'nama_pemilik_rekening',
        'jenis_atm',
    ];

    /**
     * Get the nasabah temp that owns the data rek temp.
     */
    public function nasabahTemp(): BelongsTo
    {
        return $this->belongsTo(NasabahTemp::class, 'nasabah_id');
    }
}
