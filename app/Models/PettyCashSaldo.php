<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashSaldo extends Model 
{
    use HasFactory;

    protected $table = 'petty_cash_saldo';

    protected $fillable = [
        'user_id',
        'role',
        'tipe',
        'mutasi',
        'saldo_akhir',
        'ref_id',
        'ref_table',
        'keterangan',
    ];

    protected $casts = [
        'mutasi'      => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Get current saldo for a user (last record).
     */
    public static function getSaldo(int $userId, string $role = 'admin', string $tipe = null): float
    {
        $query = static::where('user_id', $userId)
            ->where('role', $role);
            
        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $last = $query->latest('id')->first();

        return $last ? (float) $last->saldo_akhir : 0.0;
    }

    public static function getSaldoCash(int $userId): float { return static::getSaldo($userId, 'admin', 'cash'); }
    public static function getSaldoTransfer(int $userId): float { return static::getSaldo($userId, 'admin', 'transfer'); }
    public static function getTotalAdmin(int $userId): float { 
        return static::getSaldoCash($userId) + static::getSaldoTransfer($userId); 
    }

    /**
     * Create a mutation record and return new saldo_akhir.
     */
    public static function buatMutasi(
        int $userId,
        string $role,
        float $mutasi,
        string $keterangan,
        string $refId = null,
        string $refTable = null,
        string $tipe = 'cash'
    ): self {
        $saldoSekarang = static::getSaldo($userId, $role, $tipe);
        $saldoBaru     = $saldoSekarang + $mutasi;

        return static::create([
            'user_id'    => $userId,
            'role'       => $role,
            'tipe'       => $tipe,
            'mutasi'     => $mutasi,
            'saldo_akhir'=> $saldoBaru,
            'ref_id'     => $refId,
            'ref_table'  => $refTable,
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Helper to update or create saldo (compatible with previous implementation)
     */
    public static function updateSaldo($userId, $tipe, $mutasi, $refId, $keterangan = '', $refTable = null)
    {
        $last = self::where('user_id', $userId)
            ->where('role', 'admin')
            ->where('tipe', $tipe)
            ->latest('id')->first();
            
        $saldoAkhir = ($last ? (float)$last->saldo_akhir : 0) + $mutasi;
        
        return self::create([
            'user_id' => $userId,
            'role' => 'admin',
            'tipe' => $tipe,
            'mutasi' => $mutasi,
            'saldo_akhir' => $saldoAkhir,
            'ref_table' => $refTable ?: (request()->route('id') ? 'tbl_janji_temu_tabungan' : 'petty_cash_transaksi_nasabah'),
            'ref_id' => $refId,
            'keterangan' => $keterangan ?: 'Auto ' . ($mutasi > 0 ? 'Setoran' : 'Penarikan')
        ]);
    }

    // Keep original updateOrCreateSaldo for compatibility
    public static function updateOrCreateSaldo($userId, $role, $mutasi, $refId, $keterangan = '', $refTable = null, $tipe = 'cash')
    {
        return static::buatMutasi($userId, $role, $mutasi, $keterangan ?: 'Auto', $refId, $refTable, $tipe);
    }

    /**
     * Validate physical cash balance.
     */
    public static function validatePenarikanCash($adminId, $nominal): bool
    {
        return static::getSaldoCash($adminId) >= $nominal;
    }

    /**
     * Validate transfer balance.
     */
    public static function validatePenarikanTransfer($adminId, $nominal): bool
    {
        return static::getSaldoTransfer($adminId) >= $nominal;
    }

    /**
     * Generic validation based on type.
     */
    public static function validatePenarikan($adminId, $nominal, $tipe = 'cash'): bool
    {
        return static::getSaldo($adminId, 'admin', $tipe) >= $nominal;
    }

    /**
     * Get total saldo (cash + tf) for the owner.
     */
    public static function getSaldoOwnerTotal(int $ownerId): float
    {
        return static::getSaldo($ownerId, 'owner', 'cash') + 
               static::getSaldo($ownerId, 'owner', 'transfer');
    }

    /**
     * Validate if owner has enough balance for both cash and tf.
     */
    public static function validateKirimOwner(int $ownerId, float $cash, float $tf): bool
    {
        $saldoCash = static::getSaldo($ownerId, 'owner', 'cash');
        $saldoTf   = static::getSaldo($ownerId, 'owner', 'transfer');
        
        return $saldoCash >= $cash && $saldoTf >= $tf;
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
