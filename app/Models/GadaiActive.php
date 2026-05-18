<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiActive extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_active';

    protected $fillable = [
        'nasabah_id',
        'kategori_id',
        'item_id',
        'lokasi_id',
        'slot_kode',
        'slot_table',
        'nominal_deal',
        'biaya_jasa',
        'denda_aktif',
        'biaya_inap',
        'tgl_mulai',
        'tgl_jatuh_tempo',
        'tgl_tenggang',
        'jumlah_perpanjangan',
        'status',
        'admin_id'
    ];

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_jatuh_tempo' => 'datetime',
        'tgl_tenggang' => 'datetime',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    public function kategori()
    {
        return $this->belongsTo(GadaiMasterKategori::class, 'kategori_id');
    }

    public function item()
    {
        return $this->belongsTo(GadaiMasterItem::class, 'item_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function history()
    {
        return $this->hasMany(GadaiHistory::class, 'gadai_active_id');
    }

    public function files()
    {
        return $this->hasMany(GadaiFile::class, 'gadai_active_id');
    }

    public function paymentLogs()
    {
        return $this->hasMany(GadaiPaymentLog::class, 'gadai_active_id');
    }
}
