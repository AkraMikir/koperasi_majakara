<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NasabahNotification extends Model
{
    protected $table = 'nasabah_notifications';

    protected $fillable = [
        'id_anggota',
        'type',
        'title',
        'message',
        'link',
        'related_id',
        'related_type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    public function scopeForAnggota($query, $idAnggota)
    {
        return $query->where('id_anggota', $idAnggota);
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public static function notify(
        int $idAnggota,
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?string $relatedId = null,
        ?string $relatedType = null
    ): self {
        return self::create([
            'id_anggota' => $idAnggota,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }
}
