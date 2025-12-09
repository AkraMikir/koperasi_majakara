<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nasabah extends Model
{
    use HasFactory;

    protected $table = 'tbl_nasabah';

    protected $fillable = [
        'user_id',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto_ktp',
        'foto_kk',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the nasabah.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pekerjaan for the nasabah.
     */
    public function pekerjaan(): HasOne
    {
        return $this->hasOne(Pekerjaan::class, 'nasabah_id');
    }

    /**
     * Get the data rek for the nasabah.
     */
    public function dataRek(): HasOne
    {
        return $this->hasOne(DataRek::class, 'nasabah_id');
    }

    /**
     * Get the data ktp for the nasabah.
     */
    public function dataKtp(): HasOne
    {
        return $this->hasOne(DataKtp::class, 'nasabah_id');
    }

    /**
     * Get the darurat for the nasabah.
     */
    public function darurat(): HasOne
    {
        return $this->hasOne(Darurat::class, 'id_nasabah');
    }
}


