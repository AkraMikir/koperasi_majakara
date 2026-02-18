<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: hanya log dari nasabah
     */
    public function scopeForNasabah($query)
    {
        return $query->where('user_role', 'nasabah');
    }

    /**
     * Scope: hanya log dari admin (operasional dan utama)
     */
    public function scopeForAdmin($query)
    {
        return $query->whereIn('user_role', ['admin_operasional', 'admin_utama']);
    }

    /**
     * Scope: hanya log dari admin operasional
     */
    public function scopeForAdminOperasional($query)
    {
        return $query->where('user_role', 'admin_operasional');
    }

    /**
     * Scope: filter berdasarkan modul
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: filter berdasarkan tanggal range
     */
    public function scopeInDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }

    /**
     * Helper: warna badge berdasarkan action
     */
    public function getActionColorAttribute(): string
    {
        return match(true) {
            str_starts_with($this->action, 'approve_') || str_starts_with($this->action, 'create_') || str_starts_with($this->action, 'submit_') => 'green',
            str_starts_with($this->action, 'reject_') || str_starts_with($this->action, 'delete_') || str_starts_with($this->action, 'hapus_') => 'red',
            str_starts_with($this->action, 'edit_') || str_starts_with($this->action, 'update_') || str_starts_with($this->action, 'toggle_') => 'yellow',
            str_starts_with($this->action, 'cairkan_') || str_starts_with($this->action, 'konfirmasi_') || str_starts_with($this->action, 'proses_') => 'blue',
            default => 'gray',
        };
    }

    /**
     * Helper: warna badge berdasarkan modul
     */
    public function getModuleColorAttribute(): string
    {
        return match($this->module) {
            'tabungan' => 'brown',
            'pinjaman' => 'gold',
            'nasabah' => 'green',
            'master_data' => 'gray',
            'akun' => 'purple',
            default => 'gray',
        };
    }
}
