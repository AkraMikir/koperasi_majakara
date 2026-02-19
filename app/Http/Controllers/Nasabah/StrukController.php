<?php

namespace App\Http\Controllers\Nasabah;

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
    private function getIdAnggota()
    {
        $user = auth()->user();
        if (!$user || !$user->nasabah) {
            abort(403, 'User tidak memiliki data nasabah');
        }
        return $user->nasabah->id;
    }

    /**
     * Cetak struk transaksi tabungan (setoran atau penarikan) - hanya transaksi milik nasabah.
     */
    public function transaksiTabungan(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $transaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'jnsTransaksi',
                'jnsVia',
                'pengajuanSetor.approvedBy',
                'pengajuanTarik'
            ])
            ->findOrFail($id);

        $logoPath = public_path('images/logo-koperasi-majakara.png');
        $hasLogo = is_file($logoPath);

        $pdf = Pdf::loadView('struk.tabungan', compact('transaksi', 'hasLogo', 'logoPath'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Tabungan-' . $transaksi->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak struk pembayaran angsuran - hanya pengajuan milik nasabah.
     */
    public function pembayaranPinjaman(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $pengajuan = PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'pinjaman.pengajuan'
            ])
            ->findOrFail($id);

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
     * Cetak struk pencairan pinjaman - hanya pinjaman milik nasabah.
     */
    public function pencairanPinjaman(string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $pinjaman = PinjamanH::where('id_anggota', $idAnggota)
            ->with([
                'nasabah.user',
                'nasabah.dataKtp',
                'pengajuan'
            ])
            ->findOrFail($id);

        $pdf = Pdf::loadView('struk.pencairan-pinjaman', compact('pinjaman'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Pencairan-' . $pinjaman->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak struk per angsuran - hanya angsuran dari pinjaman milik nasabah.
     */
    public function angsuran(Request $request, string $id)
    {
        $idAnggota = $this->getIdAnggota();
        $jenis = $request->get('jenis', 'bulanan');
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])
                ->whereHas('pinjaman', fn ($q) => $q->where('id_anggota', $idAnggota))
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'pinjaman.pengajuan'])
                ->whereHas('pinjaman', fn ($q) => $q->where('id_anggota', $idAnggota))
                ->findOrFail($id);
        }

        $pdf = Pdf::loadView('struk.angsuran', compact('angsuran', 'jenis'));
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');
        $filename = 'Struk-Angsuran-' . $id . '.pdf';
        return $pdf->download($filename);
    }
}
