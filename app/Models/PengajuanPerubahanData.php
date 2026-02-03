<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPerubahanData extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perubahan_data';

    protected $fillable = [
        'id_nasabah',
        'jenis_data',
        'data_lama',
        'data_baru',
        'status',
        'catatan_admin',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship: Pengajuan belongs to Nasabah
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    /**
     * Relationship: Pengajuan approved by Admin (User)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Only pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Only rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get label jenis data yang lebih friendly
     */
    public function getJenisDataLabelAttribute()
    {
        $labels = [
            'data_user' => 'Data Akun User',
            'data_pribadi' => 'Data Pribadi',
            'data_ktp' => 'Data KTP',
            'pekerjaan' => 'Data Pekerjaan',
            'rekening' => 'Data Rekening Bank',
            'kontak_darurat' => 'Kontak Darurat',
        ];

        return $labels[$this->jenis_data] ?? $this->jenis_data;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
