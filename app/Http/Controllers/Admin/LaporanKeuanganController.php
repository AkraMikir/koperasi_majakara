<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\PengajuanPinjaman;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\JnsTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanKeuanganController extends Controller
{
    /**
     * Halaman indeks laporan (daftar semua laporan).
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Laporan Rekapitulasi Harian/Bulanan.
     */
    public function rekapitulasi(Request $request)
    {
        $tipe = $request->get('tipe', 'bulanan'); // harian | bulanan
        $tgl = $request->get('tgl', now()->format('Y-m-d'));
        $bulan = $request->get('bulan', now()->format('Y-m'));

        if ($tipe === 'harian') {
            $dari = $sampai = $tgl;
        } else {
            $dari = $bulan . '-01';
            $sampai = now()->parse($bulan . '-01')->endOfMonth()->format('Y-m-d');
        }

        // Tabungan: total setoran & penarikan dari trans_tabungan
        $setoranTabungan = TransTabungan::whereBetween(DB::raw('DATE(tgl_transaksi)'), [$dari, $sampai])
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'STR'))
            ->sum('nominal');
        $penarikanTabungan = TransTabungan::whereBetween(DB::raw('DATE(tgl_transaksi)'), [$dari, $sampai])
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'PNR'))
            ->sum('nominal');

        // Pinjaman: pencairan = pinjaman yang tgl_pinjam dalam periode
        $pencairanPinjaman = PinjamanH::whereBetween(DB::raw('DATE(tgl_pinjam)'), [$dari, $sampai])->sum('jumlah_pinjam');

        // Angsuran masuk: dari tempo yang sudah bayar (tgl_bayar dalam periode)
        $angsuranBulanan = TempoPinjamanB::whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->sum('jumlah_terbayar');
        $angsuranMingguan = TempoPinjamanM::whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->sum('jumlah_terbayar');
        $totalAngsuranMasuk = $angsuranBulanan + $angsuranMingguan;

        // Outstanding: pinjaman belum lunas
        $outstanding = PinjamanH::where('lunas', 'belum')->get()->sum(function ($p) {
            return $p->jumlah_pinjam - $p->tempoBulanan()->sum('jumlah_terbayar') - $p->tempoMingguan()->sum('jumlah_terbayar');
        });

        $data = [
            'tipe' => $tipe,
            'dari' => $dari,
            'sampai' => $sampai,
            'setoran_tabungan' => $setoranTabungan,
            'penarikan_tabungan' => $penarikanTabungan,
            'net_tabungan' => $setoranTabungan - $penarikanTabungan,
            'pencairan_pinjaman' => $pencairanPinjaman,
            'angsuran_masuk' => $totalAngsuranMasuk,
            'outstanding' => $outstanding,
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.rekapitulasi', $data, 'Laporan-Rekapitulasi-' . ($tipe === 'harian' ? $tgl : $bulan) . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelRekapitulasi($data);
        }

        return view('admin.laporan.rekapitulasi', $data);
    }

    /**
     * Laporan Tabungan (mutasi transaksi).
     */
    public function tabungan(Request $request)
    {
        $dari = $request->get('tgl_dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('tgl_sampai', now()->format('Y-m-d'));

        $transaksi = TransTabungan::with(['nasabah.user', 'jnsTransaksi'])
            ->whereBetween(DB::raw('DATE(tgl_transaksi)'), [$dari, $sampai])
            ->orderBy('tgl_transaksi')
            ->get();

        $totalSetor = $transaksi->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'STR')->sum('nominal');
        $totalTarik = $transaksi->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'PNR')->sum('nominal');

        $data = [
            'tgl_dari' => $dari,
            'tgl_sampai' => $sampai,
            'transaksi' => $transaksi,
            'total_setor' => $totalSetor,
            'total_tarik' => $totalTarik,
            'net' => $totalSetor - $totalTarik,
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.tabungan', $data, 'Laporan-Tabungan-' . $dari . '-sd-' . $sampai . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelTabungan($data);
        }

        return view('admin.laporan.tabungan', $data);
    }

    /**
     * Laporan Saldo Tabungan per nasabah (tanggal cutoff).
     */
    public function saldoTabungan(Request $request)
    {
        $tglCutoff = $request->get('tgl_cutoff', now()->format('Y-m-d'));

        $transaksi = TransTabungan::with(['nasabah.user', 'jnsTransaksi'])
            ->where(DB::raw('DATE(tgl_transaksi)'), '<=', $tglCutoff)
            ->orderBy('id_anggota')
            ->orderBy('tgl_transaksi')
            ->get();

        $perNasabah = $transaksi->groupBy('id_anggota')->map(function ($items) {
            $setor = $items->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'STR')->sum('nominal');
            $tarik = $items->filter(fn($t) => $t->jnsTransaksi && $t->jnsTransaksi->kode === 'PNR')->sum('nominal');
            return (object)[
                'nasabah' => $items->first()->nasabah,
                'saldo' => $setor - $tarik,
                'total_setor' => $setor,
                'total_tarik' => $tarik,
            ];
        })->filter(fn($r) => $r->saldo != 0 || $r->total_setor > 0);

        $data = [
            'tgl_cutoff' => $tglCutoff,
            'per_nasabah' => $perNasabah->values(),
            'total_saldo' => $perNasabah->sum('saldo'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.saldo-tabungan', $data, 'Laporan-Saldo-Tabungan-' . $tglCutoff . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelSaldoTabungan($data);
        }

        return view('admin.laporan.saldo-tabungan', $data);
    }

    /**
     * Laporan Pinjaman Aktif (Outstanding).
     */
    public function pinjamanAktif(Request $request)
    {
        $pinjaman = PinjamanH::with(['nasabah.user', 'tempoBulanan', 'tempoMingguan'])
            ->where('lunas', 'belum')
            ->orderBy('tgl_pinjam')
            ->get()
            ->map(function ($p) {
                $terbayarB = $p->tempoBulanan->sum('jumlah_terbayar');
                $terbayarM = $p->tempoMingguan->sum('jumlah_terbayar');
                $totalTerbayar = $terbayarB + $terbayarM;
                $sisaPokok = $p->jumlah_pinjam - $totalTerbayar;
                $totalTempo = $p->tempoBulanan->count() + $p->tempoMingguan->count();
                $sudahBayar = $p->tempoBulanan->where('status_bayar', '!=', 'belum')->count() + $p->tempoMingguan->where('status_bayar', '!=', 'belum')->count();
                return (object)[
                    'pinjaman' => $p,
                    'total_terbayar' => $totalTerbayar,
                    'sisa_pokok' => $sisaPokok,
                    'sisa_angsuran' => $totalTempo - $sudahBayar,
                ];
            });

        $data = [
            'rows' => $pinjaman,
            'total_outstanding' => $pinjaman->sum('sisa_pokok'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.pinjaman-aktif', $data, 'Laporan-Pinjaman-Aktif.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelPinjamanAktif($data);
        }

        return view('admin.laporan.pinjaman-aktif', $data);
    }

    /**
     * Laporan Angsuran Pinjaman (realisasi pembayaran per periode).
     */
    public function angsuranPinjaman(Request $request)
    {
        $dari = $request->get('tgl_dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('tgl_sampai', now()->format('Y-m-d'));

        $tempoB = TempoPinjamanB::with(['pinjaman.nasabah.user'])
            ->whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->orderBy('tgl_bayar')
            ->get();
        $tempoM = TempoPinjamanM::with(['pinjaman.nasabah.user'])
            ->whereNotNull('tgl_bayar')
            ->whereBetween(DB::raw('DATE(tgl_bayar)'), [$dari, $sampai])
            ->orderBy('tgl_bayar')
            ->get();

        $rows = $tempoB->map(fn($t) => (object)[
            'tempo' => $t,
            'tgl_bayar' => $t->tgl_bayar,
            'pinjaman' => $t->pinjaman,
            'pokok' => $t->jumlah_terbayar,
            'denda' => $t->denda ?? 0,
            'total' => $t->jumlah_terbayar + ($t->denda ?? 0),
        ])->concat($tempoM->map(fn($t) => (object)[
            'tempo' => $t,
            'tgl_bayar' => $t->tgl_bayar,
            'pinjaman' => $t->pinjaman,
            'pokok' => $t->jumlah_terbayar,
            'denda' => $t->denda ?? 0,
            'total' => $t->jumlah_terbayar + ($t->denda ?? 0),
        ]))->sortBy('tgl_bayar')->values();

        $data = [
            'tgl_dari' => $dari,
            'tgl_sampai' => $sampai,
            'rows' => $rows,
            'total_pokok' => $rows->sum('pokok'),
            'total_denda' => $rows->sum('denda'),
            'total_jumlah' => $rows->sum('total'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.angsuran-pinjaman', $data, 'Laporan-Angsuran-' . $dari . '-sd-' . $sampai . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelAngsuran($data);
        }

        return view('admin.laporan.angsuran-pinjaman', $data);
    }

    /**
     * Laporan Jatuh Tempo (angsuran jatuh tempo dalam periode).
     */
    public function jatuhTempo(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $dari = $bulan . '-01';
        $sampai = now()->parse($bulan . '-01')->endOfMonth()->format('Y-m-d');

        $tempoB = TempoPinjamanB::with(['pinjaman.nasabah.user'])
            ->whereBetween('tgl_jatuh_tempo', [$dari, $sampai])
            ->orderBy('tgl_jatuh_tempo')
            ->get();
        $tempoM = TempoPinjamanM::with(['pinjaman.nasabah.user'])
            ->whereBetween('tgl_jatuh_tempo', [$dari, $sampai])
            ->orderBy('tgl_jatuh_tempo')
            ->get();

        $rows = $tempoB->map(fn($t) => (object)[
            'tempo' => $t,
            'pinjaman' => $t->pinjaman,
            'tagihan' => $t->jumlah_tagihan,
            'terbayar' => $t->jumlah_terbayar ?? 0,
            'sisa' => $t->jumlah_tagihan - ($t->jumlah_terbayar ?? 0),
            'status_bayar' => $t->status_bayar,
        ])->concat($tempoM->map(fn($t) => (object)[
            'tempo' => $t,
            'pinjaman' => $t->pinjaman,
            'tagihan' => $t->jumlah_tagihan,
            'terbayar' => $t->jumlah_terbayar ?? 0,
            'sisa' => $t->jumlah_tagihan - ($t->jumlah_terbayar ?? 0),
            'status_bayar' => $t->status_bayar,
        ]))->sortBy('tempo.tgl_jatuh_tempo')->values();

        $data = [
            'bulan' => $bulan,
            'dari' => $dari,
            'sampai' => $sampai,
            'rows' => $rows,
            'total_tagihan' => $rows->sum('tagihan'),
            'total_terbayar' => $rows->sum('terbayar'),
            'total_sisa' => $rows->sum('sisa'),
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.jatuh-tempo', $data, 'Laporan-Jatuh-Tempo-' . $bulan . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelJatuhTempo($data);
        }

        return view('admin.laporan.jatuh-tempo', $data);
    }

    /**
     * Laporan Pengajuan (setor/tarik/pinjaman/pembayaran) per status.
     */
    public function pengajuan(Request $request)
    {
        $dari = $request->get('tgl_dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('tgl_sampai', now()->format('Y-m-d'));
        $status = $request->get('status'); // 1=pending, 2=disetujui, 3=ditolak

        $setor = PengajuanTabungan::query()
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status))
            ->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->with('nasabah.user')
            ->get();
        $tarik = PengajuanPenarikanTabungan::query()
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status))
            ->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->with('nasabah.user')
            ->get();
        $pinjaman = PengajuanPinjaman::query()
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status))
            ->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->with('nasabah.user')
            ->get();
        $pembayaran = PengajuanPembayaranPinjaman::query()
            ->when($status !== null && $status !== '', fn($q) => $q->where('status', $status))
            ->whereBetween(DB::raw('DATE(created_at)'), [$dari, $sampai])
            ->with('nasabah.user')
            ->get();

        $summary = [
            'setor' => ['count' => $setor->count(), 'nominal' => $setor->sum('nominal')],
            'tarik' => ['count' => $tarik->count(), 'nominal' => $tarik->sum('nominal')],
            'pinjaman' => ['count' => $pinjaman->count(), 'nominal' => $pinjaman->sum('nominal')],
            'pembayaran' => ['count' => $pembayaran->count(), 'nominal' => $pembayaran->sum('nominal')],
        ];

        $data = [
            'tgl_dari' => $dari,
            'tgl_sampai' => $sampai,
            'status' => $status,
            'setor' => $setor,
            'tarik' => $tarik,
            'pinjaman' => $pinjaman,
            'pembayaran' => $pembayaran,
            'summary' => $summary,
        ];

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf('admin.laporan.export.pengajuan', $data, 'Laporan-Pengajuan-' . $dari . '-sd-' . $sampai . '.pdf');
        }
        if ($request->get('export') === 'excel') {
            return $this->exportExcelPengajuan($data);
        }

        return view('admin.laporan.pengajuan', $data);
    }

    // ---- Export helpers ----

    protected function exportPdf(string $view, array $data, string $filename)
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }

    protected function exportExcelRekapitulasi(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi');
        $sheet->setCellValue('A1', 'Laporan Rekapitulasi ' . ($data['tipe'] === 'harian' ? 'Harian' : 'Bulanan'));
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A2', 'Periode: ' . $data['dari'] . ' s/d ' . $data['sampai']);
        $sheet->setCellValue('A4', 'Tabungan');
        $sheet->setCellValue('B4', 'Setoran');
        $sheet->setCellValue('C4', number_format($data['setoran_tabungan'], 0, ',', '.'));
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('B5', 'Penarikan');
        $sheet->setCellValue('C5', number_format($data['penarikan_tabungan'], 0, ',', '.'));
        $sheet->setCellValue('B6', 'Net Tabungan');
        $sheet->setCellValue('C6', number_format($data['net_tabungan'], 0, ',', '.'));
        $sheet->setCellValue('A8', 'Pinjaman');
        $sheet->setCellValue('B8', 'Pencairan');
        $sheet->setCellValue('C8', number_format($data['pencairan_pinjaman'], 0, ',', '.'));
        $sheet->setCellValue('B9', 'Angsuran Masuk');
        $sheet->setCellValue('C9', number_format($data['angsuran_masuk'], 0, ',', '.'));
        $sheet->setCellValue('B10', 'Outstanding');
        $sheet->setCellValue('C10', number_format($data['outstanding'], 0, ',', '.'));
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Rekapitulasi.xlsx');
    }

    protected function exportExcelTabungan(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mutasi Tabungan');
        $sheet->setCellValue('A1', 'Laporan Tabungan');
        $sheet->setCellValue('A2', 'Periode: ' . $data['tgl_dari'] . ' s/d ' . $data['tgl_sampai']);
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'Nasabah');
        $sheet->setCellValue('D4', 'Jenis');
        $sheet->setCellValue('E4', 'Nominal');
        $row = 5;
        foreach ($data['transaksi'] as $i => $t) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $t->tgl_transaksi?->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $t->nasabah?->user?->nama ?? '-');
            $sheet->setCellValue('D' . $row, $t->jnsTransaksi?->nama ?? $t->jenis ?? '-');
            $sheet->setCellValue('E' . $row, $t->nominal);
            $row++;
        }
        $sheet->setCellValue('D' . $row, 'Total Setor');
        $sheet->setCellValue('E' . $row, $data['total_setor']);
        $row++;
        $sheet->setCellValue('D' . $row, 'Total Tarik');
        $sheet->setCellValue('E' . $row, $data['total_tarik']);
        $row++;
        $sheet->setCellValue('D' . $row, 'Net');
        $sheet->setCellValue('E' . $row, $data['net']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Tabungan.xlsx');
    }

    protected function exportExcelSaldoTabungan(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Saldo Tabungan');
        $sheet->setCellValue('A1', 'Laporan Saldo Tabungan per Nasabah');
        $sheet->setCellValue('A2', 'Tanggal cutoff: ' . $data['tgl_cutoff']);
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nasabah');
        $sheet->setCellValue('C4', 'Total Setor');
        $sheet->setCellValue('D4', 'Total Tarik');
        $sheet->setCellValue('E4', 'Saldo');
        $row = 5;
        foreach ($data['per_nasabah'] as $i => $r) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $r->nasabah?->user?->nama ?? '-');
            $sheet->setCellValue('C' . $row, $r->total_setor);
            $sheet->setCellValue('D' . $row, $r->total_tarik);
            $sheet->setCellValue('E' . $row, $r->saldo);
            $row++;
        }
        $sheet->setCellValue('B' . $row, 'TOTAL SALDO');
        $sheet->setCellValue('E' . $row, $data['total_saldo']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Saldo-Tabungan.xlsx');
    }

    protected function exportExcelPinjamanAktif(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pinjaman Aktif');
        $sheet->setCellValue('A1', 'Laporan Pinjaman Aktif (Outstanding)');
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'ID Pinjaman');
        $sheet->setCellValue('C3', 'Nasabah');
        $sheet->setCellValue('D3', 'Tgl Pinjam');
        $sheet->setCellValue('E3', 'Nominal');
        $sheet->setCellValue('F3', 'Terbayar');
        $sheet->setCellValue('G3', 'Sisa Pokok');
        $sheet->setCellValue('H3', 'Sisa Angsuran');
        $row = 4;
        foreach ($data['rows'] as $i => $r) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $r->pinjaman->id);
            $sheet->setCellValue('C' . $row, $r->pinjaman->nasabah?->user?->nama ?? '-');
            $sheet->setCellValue('D' . $row, $r->pinjaman->tgl_pinjam?->format('d/m/Y'));
            $sheet->setCellValue('E' . $row, $r->pinjaman->jumlah_pinjam);
            $sheet->setCellValue('F' . $row, $r->total_terbayar);
            $sheet->setCellValue('G' . $row, $r->sisa_pokok);
            $sheet->setCellValue('H' . $row, $r->sisa_angsuran);
            $row++;
        }
        $sheet->setCellValue('F' . $row, 'TOTAL OUTSTANDING');
        $sheet->setCellValue('G' . $row, $data['total_outstanding']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Pinjaman-Aktif.xlsx');
    }

    protected function exportExcelAngsuran(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Angsuran');
        $sheet->setCellValue('A1', 'Laporan Angsuran Pinjaman');
        $sheet->setCellValue('A2', 'Periode: ' . $data['tgl_dari'] . ' s/d ' . $data['tgl_sampai']);
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tgl Bayar');
        $sheet->setCellValue('C4', 'Pinjaman');
        $sheet->setCellValue('D4', 'Nasabah');
        $sheet->setCellValue('E4', 'Pokok');
        $sheet->setCellValue('F4', 'Denda');
        $sheet->setCellValue('G4', 'Total');
        $row = 5;
        foreach ($data['rows'] as $i => $r) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $r->tgl_bayar?->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $r->pinjaman?->id ?? '-');
            $sheet->setCellValue('D' . $row, $r->pinjaman?->nasabah?->user?->nama ?? '-');
            $sheet->setCellValue('E' . $row, $r->pokok);
            $sheet->setCellValue('F' . $row, $r->denda);
            $sheet->setCellValue('G' . $row, $r->total);
            $row++;
        }
        $sheet->setCellValue('D' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $data['total_pokok']);
        $sheet->setCellValue('F' . $row, $data['total_denda']);
        $sheet->setCellValue('G' . $row, $data['total_jumlah']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Angsuran.xlsx');
    }

    protected function exportExcelJatuhTempo(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jatuh Tempo');
        $sheet->setCellValue('A1', 'Laporan Jatuh Tempo');
        $sheet->setCellValue('A2', 'Bulan: ' . $data['bulan']);
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tgl Jatuh Tempo');
        $sheet->setCellValue('C4', 'Pinjaman');
        $sheet->setCellValue('D4', 'Nasabah');
        $sheet->setCellValue('E4', 'Tagihan');
        $sheet->setCellValue('F4', 'Terbayar');
        $sheet->setCellValue('G4', 'Sisa');
        $sheet->setCellValue('H4', 'Status');
        $row = 5;
        foreach ($data['rows'] as $i => $r) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $r->tempo->tgl_jatuh_tempo?->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $r->pinjaman?->id ?? '-');
            $sheet->setCellValue('D' . $row, $r->pinjaman?->nasabah?->user?->nama ?? '-');
            $sheet->setCellValue('E' . $row, $r->tagihan);
            $sheet->setCellValue('F' . $row, $r->terbayar);
            $sheet->setCellValue('G' . $row, $r->sisa);
            $sheet->setCellValue('H' . $row, $r->status_bayar ?? '-');
            $row++;
        }
        $sheet->setCellValue('D' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $data['total_tagihan']);
        $sheet->setCellValue('F' . $row, $data['total_terbayar']);
        $sheet->setCellValue('G' . $row, $data['total_sisa']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Jatuh-Tempo.xlsx');
    }

    protected function exportExcelPengajuan(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengajuan');
        $sheet->setCellValue('A1', 'Laporan Pengajuan');
        $sheet->setCellValue('A2', 'Periode: ' . $data['tgl_dari'] . ' s/d ' . $data['tgl_sampai']);
        $sheet->setCellValue('A4', 'Jenis');
        $sheet->setCellValue('B4', 'Jumlah');
        $sheet->setCellValue('C4', 'Total Nominal');
        $sheet->setCellValue('A5', 'Setor Tabungan');
        $sheet->setCellValue('B5', $data['summary']['setor']['count']);
        $sheet->setCellValue('C5', $data['summary']['setor']['nominal']);
        $sheet->setCellValue('A6', 'Tarik Tabungan');
        $sheet->setCellValue('B6', $data['summary']['tarik']['count']);
        $sheet->setCellValue('C6', $data['summary']['tarik']['nominal']);
        $sheet->setCellValue('A7', 'Pinjaman');
        $sheet->setCellValue('B7', $data['summary']['pinjaman']['count']);
        $sheet->setCellValue('C7', $data['summary']['pinjaman']['nominal']);
        $sheet->setCellValue('A8', 'Pembayaran Pinjaman');
        $sheet->setCellValue('B8', $data['summary']['pembayaran']['count']);
        $sheet->setCellValue('C8', $data['summary']['pembayaran']['nominal']);
        return $this->downloadSpreadsheet($spreadsheet, 'Laporan-Pengajuan.xlsx');
    }

    protected function downloadSpreadsheet(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        return $response;
    }
}
