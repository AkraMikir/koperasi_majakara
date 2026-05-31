<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\MasterBungaPinjaman;
use App\Models\MasterDendaPinjaman;
use App\Models\JnsAngsuranBulan;
use App\Models\PaketDeposito;

class GuideController extends Controller
{
    /**
     * Tampilkan halaman panduan (Guide) untuk nasabah.
     */
    public function index()
    {
        return view('nasabah.guide');
    }

    public function tabunganSetoran()
    {
        return view('nasabah.guide.tabungan-setoran');
    }

    public function tabunganPenarikan()
    {
        return view('nasabah.guide.tabungan-penarikan');
    }

    public function pinjamanPengajuan()
    {
        $bungaPinjaman = MasterBungaPinjaman::where('status_aktif', true)->orderBy('durasi_min')->get();
        $dendaPinjaman = MasterDendaPinjaman::getDendaAktif();
        $durasiList = JnsAngsuranBulan::where('aktif', 'y')->orderBy('bulan')->get();
        if ($durasiList->isEmpty()) {
            $durasiList = collect(range(1, 24))->map(fn ($b) => (object)['bulan' => $b, 'ket' => (string) $b]);
        }
        return view('nasabah.guide.pinjaman-pengajuan', [
            'bungaPinjaman' => $bungaPinjaman,
            'dendaPinjaman' => $dendaPinjaman,
            'durasiList'    => $durasiList,
        ]);
    }

    public function pinjamanPembayaran()
    {
        return view('nasabah.guide.pinjaman-pembayaran');
    }

    public function depositoPengajuan()
    {
        $paketDeposito = PaketDeposito::where('status', 'aktif')
            ->with('kategori')
            ->orderBy('tenor_bulan')
            ->get();

        return view('nasabah.guide.deposito-pengajuan', [
            'paketDeposito' => $paketDeposito,
        ]);
    }

    public function gadaiPengajuan()
    {
        return view('nasabah.guide.gadai-pengajuan');
    }

    public function gadaiAktif()
    {
        return view('nasabah.guide.gadai-aktif');
    }
}
