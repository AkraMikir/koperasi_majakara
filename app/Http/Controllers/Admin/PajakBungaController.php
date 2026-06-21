<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PajakBungaPayment;
use App\Models\PinjamanH;
use App\Models\GadaiActive;
use App\Models\DepositoH;
use App\Models\DepositoPersiapanCair;
use App\Models\Setting;
use App\Models\GadaiPaymentLog;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PajakBungaController extends Controller
{
    /**
     * Hitung realisasi pinjaman untuk bulan & tahun tertentu
     */
    private function hitungRealisasiPinjaman(int $bulan, int $tahun): float
    {
        $angsuranBulanan = TempoPinjamanB::with('pinjaman')
            ->whereHas('pinjaman.pengajuan', fn($q) => $q->whereIn('status', ['3', '4']))
            ->where('status_bayar', 'lunas')
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->get();

        $total = 0;
        foreach ($angsuranBulanan as $angsuran) {
            $p = $angsuran->pinjaman;
            if ($p && $p->lama_pinjam > 0) {
                $total += $p->bunga_rp / $p->lama_pinjam;
            }
        }

        $angsuranMingguan = TempoPinjamanM::with('pinjaman')
            ->whereHas('pinjaman.pengajuan', fn($q) => $q->whereIn('status', ['3', '4']))
            ->where('status_bayar', 'lunas')
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->get();

        foreach ($angsuranMingguan as $angsuran) {
            $p = $angsuran->pinjaman;
            if (!$p || !$p->lama_pinjam || $p->lama_pinjam <= 0) continue;
            $minggu = $p->lama_pinjam * 4;
            if ($minggu > 0 && $p->bunga_rp) {
                $total += $p->bunga_rp / $minggu;
            }
        }

        return $total;
    }

    /**
     * Hitung realisasi gadai untuk bulan & tahun tertentu
     */
    private function hitungRealisasiGadai(int $bulan, int $tahun): float
    {
        $payments = GadaiPaymentLog::with('gadaiActive')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        $total = 0;
        foreach ($payments as $payment) {
            if ($payment->jenis_pembayaran === 'tebus') {
                $gadai = $payment->gadaiActive;
                $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
                $total += max(0, $bunga);
            } else {
                $total += $payment->nominal;
            }
        }

        return $total;
    }

    /**
     * Helper to compute realisasi/proyeksi kotor and pajak of deposito
     */
    private function hitungRealisasiDeposito(int $bulan, int $tahun): array
    {
        $depositoAktifList = DepositoH::with(['persiapanCair', 'tenor'])->where('status', 'aktif')->get();
        $totalKotor = 0;
        $totalPajak = 0;

        foreach ($depositoAktifList as $depo) {
            $persiapan = $depo->persiapanCair->last();

            if ($persiapan) {
                $bungaKotor = (float) $persiapan->bunga_kotor;
                $pajak      = (float) $persiapan->pajak;
            } else {
                // In-memory fallback — identik dengan BungaController::deposito()
                $pokok        = (float) $depo->nominal_awal;
                $bungaTahunan = (float) $depo->bunga;

                $tenorHari = 30;
                if ($depo->tenor) {
                    $tenorHari = (int) $depo->tenor->tenor_hari;
                } elseif ($depo->tgl_mulai && $depo->tgl_jatuh_tempo) {
                    $tenorHari = (int) $depo->tgl_mulai->diffInDays($depo->tgl_jatuh_tempo);
                }

                $tahunJT = $depo->tgl_jatuh_tempo ? $depo->tgl_jatuh_tempo->year : Carbon::now()->year;
                $isLeap  = ($tahunJT % 4 === 0 && $tahunJT % 100 !== 0) || ($tahunJT % 400 === 0);
                $pembagi = $isLeap ? 366 : 365;

                if ($pokok > 0 && $bungaTahunan > 0 && $tenorHari > 0) {
                    $bungaKotor = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
                    $taxRate    = (float) (Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20);
                    $pajak      = $bungaKotor * $taxRate;
                } else {
                    $bungaKotor = 0;
                    $pajak      = 0;
                }
            }
            $totalKotor += $bungaKotor;
            $totalPajak += $pajak;
        }

        return [
            'kotor' => $totalKotor,
            'pajak' => $totalPajak
        ];
    }

    /**
     * Hitung total proyeksi pajak deposito aktif — dual-path sama dengan BungaController.
     * Menggunakan persiapanCair jika ada, fallback in-memory jika belum diproses.
     */
    private function hitungPajakDeposito(int $bulan, int $tahun): float
    {
        $res = $this->hitungRealisasiDeposito($bulan, $tahun);
        return $res['pajak'];
    }

    /**
     * Index — daftar semua record + KPI summary
     */
    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $jenis = $request->get('jenis', '');

        $query = PajakBungaPayment::with('dibuatOleh')
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->orderByDesc('created_at');

        if ($jenis) {
            $query->where('jenis_pajak', $jenis);
        }

        $records = $query->get();

        // ── Realisasi & Kewajiban PPh Aktual Bulan Ini (dari data DB, bukan dari catatan) ──
        $realisasiPinjaman = $this->hitungRealisasiPinjaman(Carbon::now()->month, Carbon::now()->year);
        $realisasiGadai    = $this->hitungRealisasiGadai(Carbon::now()->month, Carbon::now()->year);
        $pajakDeposito     = $this->hitungPajakDeposito(Carbon::now()->month, Carbon::now()->year);

        // Kewajiban PPh yang harus dibayar berdasarkan data aktual
        $kewajibanPphPinjaman = $realisasiPinjaman * 0.15;
        $kewajibanPphGadai    = $realisasiGadai    * 0.15;
        $kewajibanPphDeposito = $pajakDeposito;               // sudah 20% dari hitungPajakDeposito
        $totalKewajiban       = $kewajibanPphPinjaman + $kewajibanPphGadai + $kewajibanPphDeposito;

        // ── Dari catatan yang sudah diinput admin ──
        $recordsBulanIni  = PajakBungaPayment::where('periode_bulan', Carbon::now()->month)
            ->where('periode_tahun', Carbon::now()->year)->get();

        $sudahDicatatBulanIni = $recordsBulanIni->sum('jumlah_pajak');
        $sudahBayar   = PajakBungaPayment::where('status', 'sudah_bayar')->sum('jumlah_pajak');
        $belumBayar   = PajakBungaPayment::where('status', 'belum_bayar')->sum('jumlah_pajak');
        $jumlahRecord = PajakBungaPayment::count();

        // Selisih: kewajiban bulan ini vs yang sudah dicatat
        $sisaBelumDicatat = max(0, $totalKewajiban - $sudahDicatatBulanIni);

        $data = compact(
            'records', 'bulan', 'tahun', 'jenis',
            // Kewajiban terhitung (dari data aktual)
            'kewajibanPphPinjaman', 'kewajibanPphGadai', 'kewajibanPphDeposito', 'totalKewajiban',
            'realisasiPinjaman', 'realisasiGadai', 'pajakDeposito',
            // Dari catatan DB
            'sudahDicatatBulanIni', 'sudahBayar', 'belumBayar', 'jumlahRecord', 'sisaBelumDicatat'
        );

        return view('admin.bunga.pajak.index', $data);
    }

    /**
     * Create form
     */
    public function create()
    {
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;
        return view('admin.bunga.pajak.create', compact('bulan', 'tahun'));
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pajak'    => 'required|in:pph_pinjaman,pph_gadai,pph_deposito',
            'periode_bulan'  => 'required|integer|between:1,12',
            'periode_tahun'  => 'required|integer|min:2020|max:2099',
            'jumlah_kotor'   => 'required|numeric|min:0',
            'tarif_persen'   => 'required|numeric|min:0|max:100',
            'jumlah_pajak'   => 'required|numeric|min:0',
            'jumlah_bersih'  => 'required|numeric',
            'tanggal_bayar'  => 'nullable|date',
            'keterangan'     => 'nullable|string|max:1000',
            'status'         => 'required|in:belum_bayar,sudah_bayar',
            'bukti_bayar'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->except(['_token', 'bukti_bayar']);
        $data['dibuat_oleh'] = Auth::id();

        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('pajak_bukti', 'public');
            $data['bukti_bayar'] = $path;
        }

        PajakBungaPayment::create($data);

        return redirect()->route('admin.bunga.pajak.index')
            ->with('success', 'Catatan pembayaran pajak berhasil disimpan.');
    }

    /**
     * Edit form
     */
    public function edit(int $id)
    {
        $record = PajakBungaPayment::findOrFail($id);
        return view('admin.bunga.pajak.edit', compact('record'));
    }

    /**
     * Update
     */
    public function update(Request $request, int $id)
    {
        $record = PajakBungaPayment::findOrFail($id);

        $request->validate([
            'jenis_pajak'    => 'required|in:pph_pinjaman,pph_gadai,pph_deposito',
            'periode_bulan'  => 'required|integer|between:1,12',
            'periode_tahun'  => 'required|integer|min:2020|max:2099',
            'jumlah_kotor'   => 'required|numeric|min:0',
            'tarif_persen'   => 'required|numeric|min:0|max:100',
            'jumlah_pajak'   => 'required|numeric|min:0',
            'jumlah_bersih'  => 'required|numeric',
            'tanggal_bayar'  => 'nullable|date',
            'keterangan'     => 'nullable|string|max:1000',
            'status'         => 'required|in:belum_bayar,sudah_bayar',
            'bukti_bayar'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->except(['_token', '_method', 'bukti_bayar']);

        if ($request->hasFile('bukti_bayar')) {
            // Hapus file lama jika ada
            if ($record->bukti_bayar) {
                Storage::disk('public')->delete($record->bukti_bayar);
            }
            $path = $request->file('bukti_bayar')->store('pajak_bukti', 'public');
            $data['bukti_bayar'] = $path;
        }

        $record->update($data);

        return redirect()->route('admin.bunga.pajak.index')
            ->with('success', 'Catatan pembayaran pajak berhasil diperbarui.');
    }

    /**
     * Destroy
     */
    public function destroy(int $id)
    {
        $record = PajakBungaPayment::findOrFail($id);

        if ($record->bukti_bayar) {
            Storage::disk('public')->delete($record->bukti_bayar);
        }

        $record->delete();

        return redirect()->route('admin.bunga.pajak.index')
            ->with('success', 'Catatan pembayaran pajak berhasil dihapus.');
    }

    /**
     * AJAX — hitung nilai pajak dari data aktual
     */
    public function hitung(Request $request)
    {
        $jenis = $request->get('jenis');
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);

        $jumlahKotor = 0;
        $tarifPersen = 0;

        switch ($jenis) {
            case 'pph_pinjaman':
                $jumlahKotor = $this->hitungRealisasiPinjaman($bulan, $tahun);
                $tarifPersen = 15;
                break;
            case 'pph_gadai':
                $jumlahKotor = $this->hitungRealisasiGadai($bulan, $tahun);
                $tarifPersen = 15;
                break;
            case 'pph_deposito':
                // Deposito: jumlah_kotor = total bunga kotor, menggunakan helper hitungRealisasiDeposito
                $resDepo = $this->hitungRealisasiDeposito($bulan, $tahun);
                $jumlahKotor = $resDepo['kotor'];
                $tarifPersen = 20;
                break;
        }

        $jumlahPajak  = $jumlahKotor * ($tarifPersen / 100);
        $jumlahBersih = $jumlahKotor - $jumlahPajak;

        return response()->json([
            'jumlah_kotor'  => round($jumlahKotor, 2),
            'tarif_persen'  => $tarifPersen,
            'jumlah_pajak'  => round($jumlahPajak, 2),
            'jumlah_bersih' => round($jumlahBersih, 2),
        ]);
    }
}
