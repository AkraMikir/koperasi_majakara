<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDendaDeposito extends Model
{
    use HasFactory;

    protected $table = 'master_denda_deposito';

    protected $fillable = [
        'denda_persen',
        'status_aktif',
        'keterangan',
    ];

    protected $casts = [
        'denda_persen' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get denda aktif
     */
    public static function getDendaAktif()
    {
        return self::where('status_aktif', true)->first();
    }
}
