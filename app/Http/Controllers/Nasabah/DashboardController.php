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
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->get();
        $totalPinjaman = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;
        $sisaPinjaman = $pinjamanAktif->sum('saldo_lebih') ?? 0;

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

        // Get Recent Transactions
        $transaksiTerbaru = TransTabungan::where('id_anggota', $idAnggota)
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();

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
        ];
        
        return view('nasabah.dashboard', [
            'user' => auth()->user(),
            'dummyNasabah' => $dummyNasabah,
            'stats' => $stats,
            'transaksiTerbaru' => $transaksiTerbaru,
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

        return view('nasabah.profile', compact('nasabah', 'saldoTabungan'));
    }

    /**
     * Show unified pengajuan pending page.
     */
    public function pengajuanPending(Request $request)
    {
        $idAnggota = $this->getIdAnggota();
        
        $allPengajuan = [];
        
        // Get Pengajuan Tabungan (Setoran)
        $tabunganSetor = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '1') // Pending
            ->with(['buktiFoto', 'janjiTemu'])
            ->latest()
            ->get();
        
        foreach ($tabunganSetor as $t) {
            // Calculate nominal
            $nominal = 0;
            if ($t->buktiFoto && $t->buktiFoto->count() > 0) {
                $nominal = $t->buktiFoto->sum('nominal');
            } elseif ($t->janjiTemu) {
                $nominal = $t->janjiTemu->nominal ?? 0;
            }
            
            $allPengajuan[] = [
                'id' => $t->id,
                'type' => 'tabungan_setor',
                'jenis' => 'Setoran Tabungan',
                'nominal' => $nominal,
                'tanggal' => $t->created_at,
                'status' => $this->getStatusText($t->status),
                'detail_url' => route('nasabah.tabungan.detail-pengajuan-setor', $t->id),
                'metode' => $t->foto_bukti_tf == 'transfer' ? 'Transfer' : ($t->foto_bukti_tf == 'tunai' ? 'Janji Temu' : 'N/A'),
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
        
        // Get Pengajuan Deposito
        $deposito = PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1') // Pending
            ->whereDoesntHave('deposito') // Belum disetujui
            ->with(['tenor', 'jenisDeposito'])
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
                'tenor' => $d->tenor ? $d->tenor->nama : null,
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
        
        // Sort by tanggal (newest first)
        usort($allPengajuan, function($a, $b) {
            return $b['tanggal']->timestamp - $a['tanggal']->timestamp;
        });
        
        // Filter by type if requested
        $filterType = $request->get('type');
        if ($filterType) {
            $allPengajuan = array_filter($allPengajuan, function($item) use ($filterType) {
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
     * Get angsuran terdekat (7 hari ke depan)
     */
    private function getAngsuranTerdekat($idAnggota)
    {
        $now = Carbon::now();
        $nextWeek = Carbon::now()->addDays(7);
        
        // Get angsuran bulanan
        $angsuranB = TempoPinjamanB::where('anggota_id', $idAnggota)
            ->where('status_bayar', 'belum')
            ->whereBetween('tgl_jatuh_tempo', [$now, $nextWeek])
            ->orderBy('tgl_jatuh_tempo')
            ->with('pinjaman')
            ->first();
        
        // Get angsuran mingguan
        $angsuranM = TempoPinjamanM::where('anggota_id', $idAnggota)
            ->where('status_bayar', 'belum')
            ->whereBetween('tgl_jatuh_tempo', [$now, $nextWeek])
            ->orderBy('tgl_jatuh_tempo')
            ->with('pinjaman')
            ->first();
        
        // Return yang paling dekat
        if ($angsuranB && $angsuranM) {
            return $angsuranB->tgl_jatuh_tempo < $angsuranM->tgl_jatuh_tempo ? $angsuranB : $angsuranM;
        }
        
        return $angsuranB ?? $angsuranM;
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
    private function getTransaksiBulanIni($idAnggota)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $setoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->whereBetween('tgl_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('nominal') ?? 0;
        
        $penarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
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
        $angsuranTelatB = TempoPinjamanB::where('anggota_id', $idAnggota)
            ->where('status_bayar', 'belum')
            ->where('tgl_jatuh_tempo', '<', $now)
            ->count();
        
        $angsuranTelatM = TempoPinjamanM::where('anggota_id', $idAnggota)
            ->where('status_bayar', 'belum')
            ->where('tgl_jatuh_tempo', '<', $now)
            ->count();
        
        $totalAngsuranTelat = $angsuranTelatB + $angsuranTelatM;
        
        if ($totalAngsuranTelat > 0) {
            $notifikasi[] = [
                'type' => 'warning',
                'message' => "Anda memiliki {$totalAngsuranTelat} angsuran yang telat",
                'link' => route('nasabah.pinjaman.angsuran'),
            ];
        }
        
        // Cek deposito jatuh tempo (3 hari ke depan)
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
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;

        // Tambahkan setoran dari pengajuan yang sudah approved tapi belum ada transaksi
        $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->with('buktiFoto', 'janjiTemu')
            ->get();

        foreach ($pengajuanApproved as $pengajuan) {
            $nominal = 0;
            if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
                $nominal = $pengajuan->buktiFoto->sum('nominal');
            } elseif ($pengajuan->janjiTemu) {
                $nominal = $pengajuan->janjiTemu->nominal ?? 0;
            }
            $totalSetoran += $nominal;
        }

        return max(0, $totalSetoran - $totalPenarikan);
    }
}
