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
        'alamat_domisili',
        'kode_pos',
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

    // Tabungan relationships
    public function pengajuanTabungan(): HasMany
    {
        return $this->hasMany(PengajuanTabungan::class, 'id_anggota');
    }

    public function pengajuanPenarikanTabungan(): HasMany
    {
        return $this->hasMany(PengajuanPenarikanTabungan::class, 'id_anggota');
    }

    public function transTabungan(): HasMany
    {
        return $this->hasMany(TransTabungan::class, 'id_anggota');
    }

    // Pinjaman relationships
    public function pengajuanPinjaman(): HasMany
    {
        return $this->hasMany(PengajuanPinjaman::class, 'id_anggota');
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(PinjamanH::class, 'id_anggota');
    }

    public function tempoPinjamanBulanan(): HasMany
    {
        return $this->hasMany(TempoPinjamanB::class, 'anggota_id');
    }

    public function tempoPinjamanMingguan(): HasMany
    {
        return $this->hasMany(TempoPinjamanM::class, 'anggota_id');
    }

    // Deposito relationships
    public function pengajuanDeposito(): HasMany
    {
        return $this->hasMany(PengajuanDeposito::class, 'id_nasabah');
    }

    public function deposito(): HasMany
    {
        return $this->hasMany(DepositoH::class, 'id_nasabah');
    }

    public function pencairanDeposito(): HasMany
    {
        return $this->hasMany(PencairanDeposito::class, 'id_nasabah');
    }

    // Gadai relationships
    public function itemGadai(): HasMany
    {
        return $this->hasMany(ItemGadai::class, 'id_nasabah');
    }

    public function pengajuanGadai(): HasMany
    {
        return $this->hasMany(PengajuanGadai::class, 'id_nasabah');
    }

    public function gadai(): HasMany
    {
        return $this->hasMany(GadaiH::class, 'id_nasabah');
    }

    public function tempoGadai(): HasMany
    {
        return $this->hasMany(TempoGadai::class, 'nasabah_id');
    }

    public function transGadai(): HasMany
    {
        return $this->hasMany(TransGadai::class, 'nasabah_id');
    }

    public function limitPinjaman(): HasOne
    {
        return $this->hasOne(LimitPinjaman::class, 'id_nasabah');
    }

    public function logsLimitPinjaman(): HasMany
    {
        return $this->hasMany(LogLimitPinjaman::class, 'id_nasabah');
    }
}


