<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashLog extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_logs';

    protected $fillable = [
        'user_id',
        'aksi',
        'ref_id',
        'ref_table',
        'nominal',
        'detail',
        'ip_address',
    ];

    protected $casts = [
        'nominal'    => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Create a log entry.
     */
    public static function catat(
        int $userId,
        string $aksi,
        float $nominal = null,
        array $detail = [],
        string $refId = null,
        string $refTable = null
    ): self {
        return static::create([
            'user_id'    => $userId,
            'aksi'       => $aksi,
            'ref_id'     => $refId,
            'ref_table'  => $refTable,
            'nominal'    => $nominal,
            'detail'     => json_encode($detail, JSON_UNESCAPED_UNICODE),
            'ip_address' => request()->ip(),
        ]);
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
