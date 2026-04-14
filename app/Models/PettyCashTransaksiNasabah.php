<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PettyCashTransaksiNasabah extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_transaksi_nasabah';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'admin_id',
        'nasabah_id',
        'id_jns_transaksi',
        'id_jns_via',
        'id_jns_fitur',
        'nominal',
        'bukti_tf',
        'status',
        'keterangan',
        'ref_table',
        'ref_id',
        'setoran_kantor_id',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal'         => 'decimal:2',
        'tgl_transaksi'   => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // Scopes
    public function scopeHariIni($query)
    {
        return $query->whereDate('tgl_transaksi', today());
    }

    public function scopeCashOnly($query)
    {
        return $query->whereHas('jnsVia', fn($q) => $q->where('kode', 'CS'));
    }

    public function scopeTfOnly($query)
    {
        return $query->whereHas('jnsVia', fn($q) => $q->whereIn('kode', ['TF', 'TN']));
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBelumDisetor($query)
    {
        return $query->whereNull('setoran_kantor_id');
    }

    // Relationships
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function jnsTransaksi(): BelongsTo
    {
        return $this->belongsTo(JnsTransaksi::class, 'id_jns_transaksi');
    }

    public function jnsVia(): BelongsTo
    {
        return $this->belongsTo(JnsVia::class, 'id_jns_via');
    }

    public function jnsFitur(): BelongsTo
    {
        return $this->belongsTo(JnsFitur::class, 'id_jns_fitur');
    }

    public function setoranKantor(): BelongsTo
    {
        return $this->belongsTo(PettyCashSetoranKantor::class, 'setoran_kantor_id');
    }

    public function transTabungan(): HasOne
    {
        return $this->hasOne(TransTabungan::class, 'petty_cash_ref', 'id');
    }

    public function pengajuanPembayaran(): BelongsTo
    {
        return $this->belongsTo(PengajuanPembayaranPinjaman::class, 'ref_id');
    }

    public function pengajuanTabungan(): BelongsTo
    {
        return $this->belongsTo(PengajuanTabungan::class, 'ref_id');
    }

    public function getJenisTransaksiAttribute()
    {
        return match($this->ref_table) {
            'tbl_pengajuan_tabungan' => 'Tabungan',
            'tbl_pengajuan_pembayaran_pinjaman' => 'Angsuran',
            'tbl_pinjaman_h' => 'Pencairan',
            default => 'Lainnya'
        };
    }
}
