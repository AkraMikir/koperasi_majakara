<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_tabungan';
    public $incrementing = false; // Karena pakai ID custom string
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_anggota',
        'nominal',
        'keterangan',
        'keterangan_admin',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        // ID generated manual via Controller/Service using IdGenerator
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    // Relationship ke Universal BuktiFoto
    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }

    // REMOVED: janjiTemu relation - janji temu sekarang independent

    public function transTabungan(): HasMany
    {
        return $this->hasMany(TransTabungan::class, 'id_pengajuan_setor');
    }
}



