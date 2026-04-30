<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\PengajuanPembayaranPinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StrukController extends Controller
{
    /**
     * Cetak struk transaksi tabungan (setoran atau penarikan).
     */
    public function transaksiTabungan(string $id)
    {
        $transaksi = TransTabungan::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'jnsTransaksi',
            'jnsVia',
            'pengajuanSetor.approvedBy',
            'pengajuanTarik'
        ])->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        $pdf = Pdf::loadView('struk.tabungan', compact('transaksi', 'hasLogo', 'logoPath'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 60mm width (thermal-ish), height auto
        $filename = 'Struk-Tabungan-' . $transaksi->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak struk transaksi tabungan langsung dari HTML (Thermal).
     */
    public function printTransaksiTabunganHtml(string $id)
    {
        $transaksi = TransTabungan::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'jnsTransaksi',
            'jnsVia',
            'pengajuanSetor.approvedBy',
            'pengajuanTarik',
            'adminPengelola'
        ])->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        return view('struk.tabungan-html', compact('transaksi', 'hasLogo', 'logoPath'));
    }

    /**
     * Cetak struk pembayaran angsuran pinjaman.
     */
    public function pembayaranPinjaman(string $id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pinjaman.pengajuan'
        ])->findOrFail($id);

        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::with('pinjaman')->find($pengajuan->tempo_id);
            } else {
                $angsuran = TempoPinjamanM::with('pinjaman')->find($pengajuan->tempo_id);
            }
        }

        $pdf = Pdf::loadView('struk.pembayaran-pinjaman', compact('pengajuan', 'angsuran'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pembayaran-' . $pengajuan->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak struk pencairan pinjaman.
     */
    public function pencairanPinjaman(string $id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pengajuan'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('struk.pencairan-pinjaman', compact('pinjaman'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pencairan-' . $pinjaman->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak struk per angsuran (bukti bayar angsuran ke-n).
     */
    public function angsuran(Request $request, string $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])->findOrFail($id);
        }

        $pdf = Pdf::loadView('struk.angsuran', compact('angsuran', 'jenis'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Angsuran-' . $id . '.pdf';
        return $pdf->download($filename);
    }
}
