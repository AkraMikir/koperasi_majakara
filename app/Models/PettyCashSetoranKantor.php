<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PettyCashSetoranKantor extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_setoran_kantor';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'admin_id',
        'owner_id',
        'total_setor',
        'nominal_cash',
        'nominal_tf',
        'data_potongan',
        'jumlah_nasabah',
        'foto_setoran',
        'sudah_setor_fisik',
        'status',
        'keterangan_admin',
        'keterangan_owner',
        'tgl_setoran',
        'tgl_approval',
    ];

    protected $casts = [
        'total_setor'       => 'decimal:2',
        'nominal_cash'      => 'decimal:2',
        'nominal_tf'        => 'decimal:2',
        'data_potongan'     => 'array',      // JSON auto-cast
        'sudah_setor_fisik' => 'boolean',
        'tgl_setoran'       => 'datetime',
        'tgl_approval'      => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved_owner');
    }

    // Relationships
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function transaksiNasabah(): HasMany
    {
        return $this->hasMany(PettyCashTransaksiNasabah::class, 'setoran_kantor_id');
    }
}
