<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NasabahTemp extends Model
{
    use HasFactory;

    protected $table = 'tbl_nasabah_temp';

    protected $fillable = [
        'user_id',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'alamat_domisili',
        'foto_ktp',
        'foto_kk',
        'foto_selfie',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user temp that owns the nasabah temp.
     */
    public function userTemp(): BelongsTo
    {
        return $this->belongsTo(UserTemp::class, 'user_id');
    }

    /**
     * Get the user that owns the nasabah temp (via user_temp).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the pekerjaan temp for the nasabah temp.
     */
    public function pekerjaanTemp(): HasOne
    {
        return $this->hasOne(PekerjaanTemp::class, 'nasabah_id');
    }

    /**
     * Get the data rek temp for the nasabah temp.
     */
    public function dataRekTemp(): HasOne
    {
        return $this->hasOne(DataRekTemp::class, 'nasabah_id');
    }

    /**
     * Get the data ktp temp for the nasabah temp.
     */
    public function dataKtpTemp(): HasOne
    {
        return $this->hasOne(DataKtpTemp::class, 'nasabah_id');
    }

    /**
     * Get the darurat temp for the nasabah temp.
     */
    public function daruratTemp(): HasOne
    {
        return $this->hasOne(DaruratTemp::class, 'id_nasabah');
    }
}
