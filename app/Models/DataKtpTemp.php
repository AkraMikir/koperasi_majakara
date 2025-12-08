<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataKtpTemp extends Model
{
    use HasFactory;

    protected $table = 'tbl_data_ktp_temp';

    protected $fillable = [
        'nasabah_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jenis_kelamin',
        'file_ktp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the nasabah temp that owns the data ktp temp.
     */
    public function nasabahTemp(): BelongsTo
    {
        return $this->belongsTo(NasabahTemp::class, 'nasabah_id');
    }
}
