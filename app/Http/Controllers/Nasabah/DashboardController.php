<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\DepositoH;
use App\Models\GadaiH;
use App\Models\Nasabah;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\PengajuanPinjaman;
use App\Models\PengajuanDeposito;
use App\Models\PengajuanGadai;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\PengajuanPerubahanData;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the nasabah dashboard.
     */
    public function index()
    {
        $idAnggota = $this->getIdAnggota();
        
        $dummyNasabah = (object) [
            'pekerjaanTemp' => (object) [
                'pekerjaan' => 'Karyawan Swasta',
                'penghasilan' => 5000000,
                'nama_perusahaan' => 'PT Contoh Indonesia',
            ],
            'dataRekTemp' => (object) [
                'no_rekening' => '1234567890123456',
                'nama_pemilik_rekening' => 'Ahmad Rizki',
                'jenis_atm' => 'BCA',
            ],
        ];

        // Calculate Tabungan Stats using same method as Admin
        $saldoTabungan = $this->getSaldoNasabah($idAnggota);

        // Get Pinjaman Aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->where('lunas', 'belum')
            ->get();
        $totalPinjaman = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;
        
        $sisaPinjaman = 0; // Logic sisa pinjaman V2 pending
        
        // Get Deposito Aktif
        $depositoAktif = DepositoH::where('id_nasabah', $idAnggota)
            ->where('status', 'aktif')
            ->get();
        $totalDeposito = $depositoAktif->sum('nominal_awal') ?? 0;
        $depositoTerdekat = $depositoAktif->sortBy('tgl_jatuh_tempo')->first();

        // Get Gadai Aktif
        $gadaiAktif = GadaiH::where('id_nasabah', $idAnggota)
            ->where('status', 'aktif')
            ->get();
        $totalGadai = $gadaiAktif->sum('jumlah_pinjaman') ?? 0;

        // Get Recent Transactions (Combined)
        $allTransaksi = [];

        // 1. Tabungan
        $transTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->with('jnsTransaksi')
            ->latest('tgl_transaksi')
            ->get();
            
        foreach($transTabungan as $t) {
            $isSetoran = optional($t->jnsTransaksi)->kode === 'STR';
            $allTransaksi[] = (object)[
                'id' => 'T-'.$t->id,
                'tgl_transaksi' => $t->tgl_transaksi,
                'nominal' => $t->nominal,
                'jenis' => 'Tabungan - ' . (optional($t->jnsTransaksi)->nama ?? 'Transaksi'),
                'is_inflow' => $isSetoran,
                'url' => route('nasabah.tabungan.detail-transaksi', $t->id),
                'icon_type' => 'tabungan'
            ];
        }

        // 2. Deposito
        try {
            $transDeposito = \App\Models\TransDeposito::whereHas('deposito', function($q) use ($idAnggota) {
                $q->where('id_nasabah', $idAnggota);
            })->latest('tgl_transaksi')->get();
            
            foreach($transDeposito as $t) {
                $isInflow = (strtolower($t->jenis) == 'setor_awal' || strtolower($t->jenis) == 'bunga');
                $allTransaksi[] = (object)[
                    'id' => 'D-'.$t->id,
                    'tgl_transaksi' => $t->tgl_transaksi,
                    'nominal' => $t->nominal,
                    'jenis' => 'Deposito - ' . (strtolower($t->jenis) == 'setor_awal' ? 'Setoran Awal' : ($strtolower($t->jenis) == 'pencairan' ? 'Pencairan' : ucfirst($t->jenis))),
                    'is_inflow' => $isInflow,
                    'url' => $t->deposito_id ? route('nasabah.deposito.detail', $t->deposito_id) : '#', 
                    'icon_type' => 'deposito'
                ];
            }
        } catch (\Exception $e) {}

        // 3. Gadai
        try {
            // Pencairan Gadai (Initial contract creation)
            $activeGadais = \App\Models\GadaiActive::where('nasabah_id', $idAnggota)->get();
            foreach ($activeGadais as $g) {
                $allTransaksi[] = (object)[
                    'id' => 'G-DISB-'.$g->id,
                    'tgl_transaksi' => $g->tgl_mulai,
                    'nominal' => $g->nominal_deal,
                    'jenis' => 'Gadai - Pencairan',
                    'is_inflow' => true,
                    'url' => route('nasabah.gadai_baru.aktif-detail', $g->id),
                    'icon_type' => 'gadai'
                ];
            }

            // Gadai Payments (Tebus / Perpanjangan)
            $gadaiPayments = \App\Models\GadaiPaymentLog::whereHas('gadaiActive', function($q) use ($idAnggota) {
                $q->where('nasabah_id', $idAnggota);
            })->get();
            foreach ($gadaiPayments as $p) {
                $allTransaksi[] = (object)[
                    'id' => 'G-PAY-'.$p->id,
                    'tgl_transaksi' => $p->created_at,
                    'nominal' => $p->nominal,
                    'jenis' => 'Gadai - ' . ($p->jenis_pembayaran === 'tebus' ? 'Pelunasan' : 'Perpanjangan'),
                    'is_inflow' => false,
                    'url' => route('nasabah.gadai_baru.aktif-detail', $p->gadai_active_id),
                    'icon_type' => 'gadai'
                ];
            }
        } catch (\Exception $e) {}

        // 4. Pinjaman
        try {
            // Pencairan Pinjaman (Initial Loan approval)
            $loans = \App\Models\PinjamanH::where('id_anggota', $idAnggota)->get();
            foreach ($loans as $l) {
                $allTransaksi[] = (object)[
                    'id' => 'P-DISB-'.$l->id,
                    'tgl_transaksi' => $l->tgl_pinjam,
                    'nominal' => $l->jumlah_pinjam,
                    'jenis' => 'Pinjaman - Pencairan',
                    'is_inflow' => true,
                    'url' => route('nasabah.pinjaman.detail-pinjaman', $l->id),
                    'icon_type' => 'pinjaman'
                ];
            }

            // Payment installments (PengajuanPembayaranPinjaman)
            $loanPayments = \App\Models\PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
                ->where('status', '3')
                ->get();
            foreach ($loanPayments as $p) {
                $allTransaksi[] = (object)[
                    'id' => 'P-PAY-'.$p->id,
                    'tgl_transaksi' => $p->tgl_pembayaran,
                    'nominal' => $p->nominal,
                    'jenis' => 'Pinjaman - Angsuran ' . ($p->jenis_tempo === 'bulanan' ? 'Bulanan' : 'Musiman'),
                    'is_inflow' => false,
                    'url' => route('nasabah.pinjaman.detail-pembayaran', $p->id),
                    'icon_type' => 'pinjaman'
                ];
            }
        } catch (\Exception $e) {}

        // Sort by tgl_transaksi descending
        usort($allTransaksi, function($a, $b) {
            $timeA = $a->tgl_transaksi ? Carbon::parse($a->tgl_transaksi)->timestamp : 0;
            $timeB = $b->tgl_transaksi ? Carbon::parse($b->tgl_transaksi)->timestamp : 0;
            return $timeB - $timeA;
        });
        
        // Take latest 5
        $transaksiTerbaru = array_slice($allTransaksi, 0, 5);

        // Calculate Total Assets
        $totalAssets = $saldoTabungan + $totalDeposito;

        // Get Angsuran Terdekat (7 hari ke depan)
        $angsuranTerdekat = $this->getAngsuranTerdekat($idAnggota);
        
        // Get Pengajuan Pending
        $pengajuanPending = $this->getPengajuanPending($idAnggota);
        
        // Get Transaksi Bulan Ini
        $transaksiBulanIni = $this->getTransaksiBulanIni($idAnggota);
        
        // Get Notifikasi Penting
        $notifikasiPenting = $this->getNotifikasiPenting($idAnggota, $pinjamanAktif);

        // Calculate Chart Data (Percentages)
        $totalAll = $saldoTabungan + $totalDeposito + $totalPinjaman + $totalGadai;
        $chartData = [
            'labels' => ['Tabungan', 'Deposito', 'Pinjaman', 'Gadai'],
            'data' => [
                $totalAll > 0 ? ($saldoTabungan / $totalAll) * 100 : 0,
                $totalAll > 0 ? ($totalDeposito / $totalAll) * 100 : 0,
                $totalAll > 0 ? ($totalPinjaman / $totalAll) * 100 : 0,
                $totalAll > 0 ? ($totalGadai / $totalAll) * 100 : 0,
            ],
            'raw' => [$saldoTabungan, $totalDeposito, $totalPinjaman, $totalGadai]
        ];

        // Stats for cards
        $stats = [
            'saldo_tabungan' => $saldoTabungan,
            'total_pinjaman' => $totalPinjaman,
            'sisa_pinjaman' => $sisaPinjaman,
            'total_deposito' => $totalDeposito,
            'total_gadai' => $totalGadai,
            'total_assets' => $totalAssets,
            'pinjaman_aktif_count' => $pinjamanAktif->count(),
            'deposito_aktif_count' => $depositoAktif->count(),
            'gadai_aktif_count' => $gadaiAktif->count(),
            'deposito_terdekat' => $depositoTerdekat,
            'angsuran_terdekat' => $angsuranTerdekat,
            'pengajuan_pending' => $pengajuanPending,
            'transaksi_bulan_ini' => $transaksiBulanIni,
            'notifikasi_penting' => $notifikasiPenting,
            'chart_data' => $chartData,
        ];
        
        // Check premium feature access
        $bankService = app(\App\Services\BankAccessService::class);
        $bankInfo = $bankService->checkPremiumAccess($idAnggota);

        return view('nasabah.dashboard', [
            'user' => auth()->user(),
            'dummyNasabah' => $dummyNasabah,
            'stats' => $stats,
            'transaksiTerbaru' => $transaksiTerbaru,
            'bankInfo' => $bankInfo,
        ]);
    }

    /**
     * Show the nasabah profile page.
     */
    public function profile()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Get nasabah data with all relationships
        $nasabah = Nasabah::with(['user', 'pekerjaan', 'dataKtp', 'dataRek', 'darurat', 'transTabungan'])
            ->findOrFail($idAnggota);

        // Calculate saldo tabungan using same method as Admin
        $saldoTabungan = $this->getSaldoNasabah($idAnggota);

        // Get pending profile update requests
        $pendingRequests = PengajuanPerubahanData::where('id_nasabah', $idAnggota)
            ->where('status', 'pending')
            ->get()
            ->keyBy('jenis_data'); // Key by jenis_data for easy access

        return view('nasabah.profile', compact('nasabah', 'saldoTabungan', 'pendingRequests'));
    }

    /**
     * Show unified transaction history for verified nasabah.
     */
    public function riwayatTransaksi(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        $allTransaksi = [];

        // Filter parameters
        $filterType = $request->input('type'); // tabungan, deposito, pinjaman, gadai
        $filterFlow = $request->input('flow'); // masuk (inflow), keluar (outflow)

        // 1. Tabungan
        if (!$filterType || $filterType === 'tabungan') {
            $transTabungan = TransTabungan::where('id_anggota', $idAnggota)
                ->with('jnsTransaksi')
                ->latest('tgl_transaksi')
                ->get();
                
            foreach($transTabungan as $t) {
                $isSetoran = optional($t->jnsTransaksi)->kode === 'STR';
                if ($filterFlow && (($filterFlow === 'masuk' && !$isSetoran) || ($filterFlow === 'keluar' && $isSetoran))) {
                    continue;
                }
                $allTransaksi[] = (object)[
                    'id' => 'T-'.$t->id,
                    'tgl_transaksi' => $t->tgl_transaksi,
                    'nominal' => $t->nominal,
                    'jenis' => 'Tabungan - ' . (optional($t->jnsTransaksi)->nama ?? 'Transaksi'),
                    'is_inflow' => $isSetoran,
                    'url' => route('nasabah.tabungan.detail-transaksi', $t->id),
                    'icon_type' => 'tabungan'
                ];
            }
        }

        // 2. Deposito
        if (!$filterType || $filterType === 'deposito') {
            try {
                $transDeposito = \App\Models\TransDeposito::whereHas('deposito', function($q) use ($idAnggota) {
                    $q->where('id_nasabah', $idAnggota);
                })->latest('tgl_transaksi')->get();
                
                foreach($transDeposito as $t) {
                    $isInflow = (strtolower($t->jenis) == 'setor_awal' || strtolower($t->jenis) == 'bunga');
                    if ($filterFlow && (($filterFlow === 'masuk' && !$isInflow) || ($filterFlow === 'keluar' && $isInflow))) {
                        continue;
                    }
                    $allTransaksi[] = (object)[
                        'id' => 'D-'.$t->id,
                        'tgl_transaksi' => $t->tgl_transaksi,
                        'nominal' => $t->nominal,
                        'jenis' => 'Deposito - ' . (strtolower($t->jenis) == 'setor_awal' ? 'Setoran Awal' : ($strtolower($t->jenis) == 'pencairan' ? 'Pencairan' : ucfirst($t->jenis))),
                        'is_inflow' => $isInflow,
                        'url' => $t->deposito_id ? route('nasabah.deposito.detail', $t->deposito_id) : '#', 
                        'icon_type' => 'deposito'
                    ];
                }
            } catch (\Exception $e) {}
        }

        // 3. Gadai
        if (!$filterType || $filterType === 'gadai') {
            try {
                // Pencairan Gadai (Initial contract creation)
                if (!$filterFlow || $filterFlow === 'masuk') {
                    $activeGadais = \App\Models\GadaiActive::where('nasabah_id', $idAnggota)->get();
                    foreach ($activeGadais as $g) {
                        $allTransaksi[] = (object)[
                            'id' => 'G-DISB-'.$g->id,
                            'tgl_transaksi' => $g->tgl_mulai,
                            'nominal' => $g->nominal_deal,
                            'jenis' => 'Gadai - Pencairan',
                            'is_inflow' => true,
                            'url' => route('nasabah.gadai_baru.aktif-detail', $g->id),
                            'icon_type' => 'gadai'
                        ];
                    }
                }

                // Gadai Payments (Tebus / Perpanjangan)
                if (!$filterFlow || $filterFlow === 'keluar') {
                    $gadaiPayments = \App\Models\GadaiPaymentLog::whereHas('gadaiActive', function($q) use ($idAnggota) {
                        $q->where('nasabah_id', $idAnggota);
                    })->get();
                    foreach ($gadaiPayments as $p) {
                        $allTransaksi[] = (object)[
                            'id' => 'G-PAY-'.$p->id,
                            'tgl_transaksi' => $p->created_at,
                            'nominal' => $p->nominal,
                            'jenis' => 'Gadai - ' . ($p->jenis_pembayaran === 'tebus' ? 'Pelunasan' : 'Perpanjangan'),
                            'is_inflow' => false,
                            'url' => route('nasabah.gadai_baru.aktif-detail', $p->gadai_active_id),
                            'icon_type' => 'gadai'
                        ];
                    }
                }
            } catch (\Exception $e) {}
        }

        // 4. Pinjaman
        if (!$filterType || $filterType === 'pinjaman') {
            try {
                // Pencairan Pinjaman (Initial Loan approval)
                if (!$filterFlow || $filterFlow === 'masuk') {
                    $loans = \App\Models\PinjamanH::where('id_anggota', $idAnggota)->get();
                    foreach ($loans as $l) {
                        $allTransaksi[] = (object)[
                            'id' => 'P-DISB-'.$l->id,
                            'tgl_transaksi' => $l->tgl_pinjam,
                            'nominal' => $l->jumlah_pinjam,
                            'jenis' => 'Pinjaman - Pencairan',
                            'is_inflow' => true,
                            'url' => route('nasabah.pinjaman.detail-pinjaman', $l->id),
                            'icon_type' => 'pinjaman'
                        ];
                    }
                }

                // Payment installments (PengajuanPembayaranPinjaman)
                if (!$filterFlow || $filterFlow === 'keluar') {
                    $loanPayments = \App\Models\PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
                        ->where('status', '3')
                        ->get();
                    foreach ($loanPayments as $p) {
                        $allTransaksi[] = (object)[
                            'id' => 'P-PAY-'.$p->id,
                            'tgl_transaksi' => $p->tgl_pembayaran,
                            'nominal' => $p->nominal,
                            'jenis' => 'Pinjaman - Angsuran ' . ($p->jenis_tempo === 'bulanan' ? 'Bulanan' : 'Musiman'),
                            'is_inflow' => false,
                            'url' => route('nasabah.pinjaman.detail-pembayaran', $p->id),
                            'icon_type' => 'pinjaman'
                        ];
                    }
                }
            } catch (\Exception $e) {}
        }

        // Sort by tgl_transaksi descending
        usort($allTransaksi, function($a, $b) {
            $timeA = $a->tgl_transaksi ? Carbon::parse($a->tgl_transaksi)->timestamp : 0;
            $timeB = $b->tgl_transaksi ? Carbon::parse($b->tgl_transaksi)->timestamp : 0;
            return $timeB - $timeA;
        });

        // Paginate manually
        $perPage = 10;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page') ?: 1;
        $allTransaksiCollection = collect($allTransaksi);
        $paginatedItems = $allTransaksiCollection->forPage($page, $perPage)->values()->all();
        $transaksi = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $allTransaksiCollection->count(),
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );

        return view('nasabah.riwayat-transaksi', compact('transaksi'));
    }

    /**
     * Show unified pengajuan pending page.
     */
    public function pengajuanPending(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        
        $allPengajuan = [];
        
        // Get Pengajuan Tabungan (Setoran) - hanya transfer; nominal dari pengajuan
        $tabunganSetor = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1') // Pending
            ->with(['buktiFoto'])
            ->latest()
            ->get();
        
        foreach ($tabunganSetor as $t) {
            $allPengajuan[] = [
                'id' => $t->id,
                'type' => 'tabungan_setor',
                'jenis' => 'Setoran Tabungan',
                'nominal' => $t->nominal ?? 0,
                'tanggal' => $t->created_at,
                'status' => $this->getStatusText($t->status),
                'detail_url' => route('nasabah.tabungan.detail-pengajuan-setor', $t->id),
                'metode' => 'Transfer',
            ];
        }
        
        // Get Pengajuan Penarikan Tabungan
        $tabunganTarik = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1') // Pending
            ->latest()
            ->get();
        
        foreach ($tabunganTarik as $t) {
            $allPengajuan[] = [
                'id' => $t->id,
                'type' => 'tabungan_tarik',
                'jenis' => 'Penarikan Tabungan',
                'nominal' => $t->nominal ?? 0,
                'tanggal' => $t->tgl_pengajuan ?? $t->created_at,
                'status' => $this->getStatusText($t->status),
                'detail_url' => route('nasabah.tabungan.detail-pengajuan-tarik', $t->id),
                'metode' => 'Penarikan',
            ];
        }
        
        // Get Pengajuan Pinjaman
        $pinjaman = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->where('status', '1') // ONLY pending
            ->whereDoesntHave('pinjaman') // Belum disetujui
            ->latest()
            ->get();
        
        foreach ($pinjaman as $p) {
            $allPengajuan[] = [
                'id' => $p->id,
                'type' => 'pinjaman',
                'jenis' => 'Pinjaman',
                'nominal' => $p->nominal ?? 0,
                'tanggal' => $p->tgl_pengajuan ?? $p->created_at,
                'status' => 'Menunggu Persetujuan',
                'detail_url' => route('nasabah.pinjaman.detail-pengajuan', $p->id),
                'metode' => $p->jenis ?? 'N/A',
                'durasi' => $p->durasi ?? null,
            ];
        }

        // Get Pengajuan Pembayaran Pinjaman (Angsuran)
        $pinjamanBayar = \App\Models\PengajuanPembayaranPinjaman::where('id_anggota', $idAnggota)
            ->where('status', '1') // Pending
            ->latest()
            ->get();
        
        foreach ($pinjamanBayar as $pb) {
            $allPengajuan[] = [
                'id' => $pb->id,
                'type' => 'pinjaman_bayar',
                'jenis' => 'Pembayaran Pinjaman',
                'nominal' => $pb->nominal ?? 0,
                'tanggal' => $pb->created_at,
                'status' => $this->getStatusText($pb->status),
                'detail_url' => route('nasabah.pinjaman.detail-pembayaran', $pb->id),
                'metode' => $pb->metode_pembayaran ?? 'N/A',
            ];
        }
        
        // Get Pengajuan Deposito
        $deposito = PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1') // Pending
            ->whereDoesntHave('deposito') // Belum disetujui
            ->with(['tenor', 'paket'])
            ->latest()
            ->get();
        
        foreach ($deposito as $d) {
            $allPengajuan[] = [
                'id' => $d->id,
                'type' => 'deposito',
                'jenis' => 'Deposito',
                'nominal' => $d->nominal ?? 0,
                'tanggal' => $d->created_at,
                'status' => $this->getStatusText($d->status),
                'detail_url' => '#', // TODO: Add route when deposito detail page is ready
                'metode' => $d->metode_setor ?? 'N/A',
                'tenor' => $d->tenor ? $d->tenor->tenor_bulan . ' Bulan' : ($d->paket ? $d->paket->tenor_bulan . ' Bulan' : null),
            ];
        }
        
        // Get Pengajuan Gadai
        $gadai = PengajuanGadai::where('id_nasabah', $idAnggota)
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', '1');
            })
            ->whereDoesntHave('gadai') // Belum disetujui
            ->with('itemGadai')
            ->latest()
            ->get();
        
        foreach ($gadai as $g) {
            $allPengajuan[] = [
                'id' => $g->id,
                'type' => 'gadai',
                'jenis' => 'Gadai',
                'nominal' => $g->nominal_diajukan ?? 0,
                'tanggal' => $g->created_at,
                'status' => $this->getStatusText($g->status),
                'detail_url' => '#', // TODO: Add route when gadai detail page is ready
                'metode' => $g->metode ?? 'N/A',
                'item' => $g->itemGadai ? $g->itemGadai->nama_item : 'N/A',
            ];
        }

        // Get Pengajuan Tebus / Perpanjangan Gadai (GadaiPengajuan)
        $gadaiRepay = \App\Models\GadaiPengajuan::whereHas('gadaiActive', function($q) use ($idAnggota) {
                $q->where('nasabah_id', $idAnggota);
            })
            ->where('status', 'pending')
            ->with(['gadaiActive.item'])
            ->latest()
            ->get();

        foreach ($gadaiRepay as $gr) {
            $allPengajuan[] = [
                'id' => $gr->id,
                'type' => 'gadai_repay',
                'jenis' => 'Gadai - ' . ($gr->jenis_pengajuan === 'lunas' ? 'Pelunasan' : 'Perpanjangan'),
                'nominal' => $gr->nominal ?? 0,
                'tanggal' => $gr->created_at,
                'status' => $this->getStatusText($gr->status),
                'detail_url' => route('nasabah.gadai_baru.status-pengajuan'),
                'metode' => $gr->metode ?? 'N/A',
                'item' => $gr->gadaiActive && $gr->gadaiActive->item ? $gr->gadaiActive->item->nama_item : 'N/A',
            ];
        }
        
        // Sort by tanggal (newest first)
        usort($allPengajuan, function($a, $b) {
            $timeA = $a['tanggal'] ? $a['tanggal']->timestamp : 0;
            $timeB = $b['tanggal'] ? $b['tanggal']->timestamp : 0;
            return $timeB - $timeA;
        });
        
        // Filter by type if requested
        $filterType = $request->get('type');
        if ($filterType) {
            $allPengajuan = array_filter($allPengajuan, function($item) use ($filterType) {
                if ($filterType === 'pinjaman') {
                    return $item['type'] === 'pinjaman' || $item['type'] === 'pinjaman_bayar';
                }
                if ($filterType === 'gadai') {
                    return $item['type'] === 'gadai' || $item['type'] === 'gadai_repay';
                }
                return $item['type'] == $filterType;
            });
        }
        
        // Filter by status if requested
        $filterStatus = $request->get('status');
        if ($filterStatus) {
            $allPengajuan = array_filter($allPengajuan, function($item) use ($filterStatus) {
                return strtolower($item['status']) == strtolower($filterStatus);
            });
        }
        
        return view('nasabah.pengajuan-pending', [
            'user' => auth()->user(),
            'pengajuan' => $allPengajuan,
            'filterType' => $filterType,
            'filterStatus' => $filterStatus,
        ]);
    }

    /**
     * Get status text from status code.
     */
    private function getStatusText($status)
    {
        $statusMap = [
            '1' => 'Menunggu Persetujuan',
            '2' => 'Disetujui',
            '3' => 'Ditolak',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
        
        return $statusMap[$status] ?? 'Tidak Diketahui';
    }

    /**
     * Get angsuran terdekat (Next absolute installment)
     */
    private function getAngsuranTerdekat($idAnggota)
    {
        $now = Carbon::now();
        
        // Get the very next unpaid installment
        $angsuran = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota)
                  ->where('lunas', 'belum');
            })
            ->where('status_bayar', 'belum')
            ->where('tgl_jatuh_tempo', '>=', $now->startOfDay())
            ->orderBy('tgl_jatuh_tempo')
            ->with('pinjaman')
            ->first();
        
        return $angsuran;
    }

    /**
     * Get jumlah pengajuan pending
     */
    private function getPengajuanPending($idAnggota)
    {
        $tabungan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1')
            ->count();
        
        $penarikan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1')
            ->count();
        
        $pinjaman = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->whereDoesntHave('pinjaman')
            ->count();
        
        $deposito = PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1')
            ->whereDoesntHave('deposito')
            ->count();
        
        $gadai = PengajuanGadai::where('id_nasabah', $idAnggota)
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', '1');
            })
            ->whereDoesntHave('gadai')
            ->count();
        
        return $tabungan + $penarikan + $pinjaman + $deposito + $gadai;
    }

    /**
     * Get statistik transaksi bulan ini
     */
    /**
     * Get statistik transaksi bulan ini
     */
    private function getTransaksiBulanIni($idAnggota)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $setoran = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'STR');
            })
            ->whereBetween('tgl_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('nominal') ?? 0;
        
        $penarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'PNR');
            })
            ->whereBetween('tgl_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('nominal') ?? 0;
        
        $jumlahTransaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->whereBetween('tgl_transaksi', [$startOfMonth, $endOfMonth])
            ->count();
        
        return [
            'setoran' => $setoran,
            'penarikan' => $penarikan,
            'jumlah' => $jumlahTransaksi,
            'saldo_bersih' => $setoran - $penarikan,
        ];
    }

    /**
     * Get notifikasi penting
     */
    private function getNotifikasiPenting($idAnggota, $pinjamanAktif)
    {
        $notifikasi = [];
        
        // Cek angsuran telat
        $now = Carbon::now();
        $angsuranTelatB = TempoPinjamanB::whereHas('pinjaman', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota);
            })
            ->where('status_bayar', 'belum')
            ->where('tgl_jatuh_tempo', '<', $now)
            ->count();
        
        // TempoPinjamanM belum ada di refactoring, skip logic merge
        $totalAngsuranTelat = $angsuranTelatB;
        
        if ($totalAngsuranTelat > 0) {
            $notifikasi[] = [
                'type' => 'warning',
                'message' => "Anda memiliki {$totalAngsuranTelat} angsuran yang telat",
                'link' => route('nasabah.pinjaman.angsuran'),
            ];
        }
        
        // Cek deposito jatuh tempo (3 hari ke depan)
        // Skip DepositoH if table dropped or use try catch
        try {
            $depositoJatuhTempo = DepositoH::where('id_nasabah', $idAnggota)
                ->where('status', 'aktif')
                ->whereBetween('tgl_jatuh_tempo', [$now, $now->copy()->addDays(3)])
                ->count();
            
            if ($depositoJatuhTempo > 0) {
                $notifikasi[] = [
                    'type' => 'info',
                    'message' => "{$depositoJatuhTempo} deposito akan jatuh tempo dalam 3 hari",
                    'link' => '#',
                ];
            }
        } catch (\Exception $e) {
            // Ignore if table doesn't exist yet
        }
        
        return $notifikasi;
    }

    /**
     * Get ID anggota from authenticated user.
     */
    private function getIdAnggota()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $nasabah = $user->nasabah;
        
        if (!$nasabah) {
            abort(403, 'User tidak memiliki data nasabah');
        }

        return $nasabah->id;
    }

    /**
     * Get saldo nasabah (same method as Admin controller).
     */
    private function getSaldoNasabah($idAnggota)
    {
        // Hitung dari trans_tabungan yang sudah ada
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'STR');
            })
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'PNR');
            })
            ->sum('nominal') ?? 0;

        return max(0, $totalSetoran - $totalPenarikan);
    }
}
