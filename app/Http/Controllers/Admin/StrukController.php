<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\GadaiActive;
use App\Models\SettingsStruk;
use App\Models\DepositoH;
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

    /**
     * Download Struk Gadai Active (Admin)
     */
    public function gadaiActive(string $id)
    {
        $gadai = GadaiActive::with(['nasabah.user', 'nasabah.dataKtp', 'kategori', 'item', 'lokasi'])
            ->findOrFail($id);
        
        $settings = SettingsStruk::getSettings();
        
        // Hitung total biaya
        $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + $gadai->denda_aktif;
        if ($gadai->extra_pinjaman_nominal) {
            $totalTagihan += $gadai->extra_pinjaman_nominal;
        }
        
        $data = [
            'settings' => $settings,
            'gadai' => $gadai,
            'total_tagihan' => $totalTagihan,
            'no_struk' => SettingsStruk::generateNoStruk('GD'),
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
        ];
        
        $pdf = Pdf::loadView('struk.gadai', $data);
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // Thermal size
        return $pdf->download('Struk-Gadai-' . $gadai->slot_kode . '.pdf');
    }

    /**
     * Download Struk Deposito Active (Admin)
     */
    public function depositoActive(string $id)
    {
        $deposito = DepositoH::with(['nasabah.user', 'nasabah.dataKtp', 'tenor'])
            ->findOrFail($id);
        
        $settings = SettingsStruk::getSettings();
        
        // Estimasi bunga & nominal akhir
        $tenorBulan = $deposito->tenor->tenor_bulan ?? 1;
        $estimasiBunga = ($deposito->nominal_awal * ($deposito->bunga) * $tenorBulan) / 12;
        $nominalAkhir = $deposito->nominal_awal + $estimasiBunga;
        
        $data = [
            'settings' => $settings,
            'deposito' => $deposito,
            'estimasi_bunga' => $estimasiBunga,
            'nominal_akhir' => $nominalAkhir,
            'no_struk' => SettingsStruk::generateNoStruk('DEP'),
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
        ];
        
        $pdf = Pdf::loadView('struk.deposito', $data);
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // Thermal size
        return $pdf->download('Struk-Deposito-' . $deposito->nomor_deposito . '.pdf');
    }
}
