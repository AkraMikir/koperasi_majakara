<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\Nasabah;
use App\Models\PengajuanPembayaranPinjaman;
use App\Models\JanjiTemuPembayaranPinjaman;
use App\Models\BuktiFotoPembayaranPinjaman;
use App\Models\JnsLokasiPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    /**
     * Display dashboard pinjaman admin.
     */
    public function index()
    {
        // Statistik pinjaman
        $stats = [
            'total_pengajuan_pending' => PengajuanPinjaman::whereDoesntHave('pinjaman')->count(),
            'total_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->whereIn('status', ['pencairan', 'telaksana'])->count(),
            'total_pinjaman_lunas' => PinjamanH::where('lunas', 'lunas')->count(),
            'total_pinjaman_hari_ini' => PinjamanH::whereDate('created_at', today())->count(),
            'total_nominal_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->whereIn('status', ['pencairan', 'telaksana'])->sum('jumlah_pinjam') ?? 0,
            'total_angsuran_telat' => $this->getTotalAngsuranTelat(),
            'total_pembayaran_pending' => PengajuanPembayaranPinjaman::where('status', '1')->count(),
        ];

        // Pengajuan terbaru (pending)
        $pengajuan_terbaru = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Pinjaman aktif terbaru
        $pinjaman_aktif_terbaru = PinjamanH::where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Angsuran jatuh tempo hari ini
        $angsuran_jatuh_tempo = $this->getAngsuranJatuhTempo();

        return view('admin.pinjaman.index', compact(
            'stats',
            'pengajuan_terbaru',
            'pinjaman_aktif_terbaru',
            'angsuran_jatuh_tempo'
        ));
    }

    /**
     * Display list of pengajuan pinjaman.
     */
    public function pengajuan(Request $request)
    {
        $query = PengajuanPinjaman::with('nasabah.user')
            ->latest();

        // Filter by status (pengajuan yang belum punya pinjaman = pending)
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } else {
                $query->whereHas('pinjaman');
            }
        } else {
            // Default show pending
            $query->whereDoesntHave('pinjaman');
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan pinjaman.
     */
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.pekerjaan', 'pinjaman'])
            ->findOrFail($id);

        return view('admin.pinjaman.detail-pengajuan', compact('pengajuan'));
    }

    /**
     * Approve pengajuan pinjaman.
     */
    public function approvePengajuan(Request $request, $id)
    {
        $request->validate([
            'bunga' => 'required|numeric|min:0|max:100',
            'bunga_rp' => 'required|numeric|min:0',
            'denda_persen' => 'required|numeric|min:0|max:100',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        // Create pinjaman
        $pinjaman = PinjamanH::create([
            'id_anggota' => $pengajuan->id_anggota,
            'id_pengajuan' => $pengajuan->id,
            'jumlah_pinjam' => $pengajuan->nominal,
            'lama_pinjam' => (int)$pengajuan->durasi,
            'jenis' => $pengajuan->jenis,
            'bunga' => $request->bunga / 100, // Convert to decimal
            'bunga_rp' => $request->bunga_rp,
            'denda_persen' => $request->denda_persen,
            'tgl_pinjam' => now(),
            'status' => 'pencairan',
            'lunas' => 'belum',
        ]);

        // Generate jadwal angsuran
        $this->generateJadwalAngsuran($pinjaman);

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman berhasil disetujui');
    }

    /**
     * Reject pengajuan pinjaman.
     */
    public function rejectPengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);
        // Update status pengajuan jika ada field status
        // Note: Sesuai struktur, pengajuan tidak punya status field, jadi kita skip atau update keterangan
        $pengajuan->update([
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman ditolak');
    }

    /**
     * Display list of active pinjaman.
     */
    public function pinjamanAktif(Request $request)
    {
        $query = PinjamanH::with('nasabah.user')
            ->where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-aktif', compact('pinjaman'));
    }

    /**
     * Display detail pinjaman.
     */
    public function detailPinjaman($id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'nasabah.pekerjaan',
            'pengajuan',
            'tempoBulanan',
            'tempoMingguan'
        ])->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        return view('admin.pinjaman.detail-pinjaman', compact('pinjaman', 'angsuran'));
    }

    /**
     * Display list of angsuran/tempo.
     */
    public function angsuran(Request $request)
    {
        $query = null;
        $jenis = $request->get('jenis', 'bulanan');

        if ($jenis === 'bulanan') {
            $query = TempoPinjamanB::with(['pinjaman.nasabah.user', 'nasabah.user'])
                ->latest('tgl_jatuh_tempo');
        } else {
            $query = TempoPinjamanM::with(['pinjaman.nasabah.user', 'nasabah.user'])
                ->latest('tgl_jatuh_tempo');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_bayar', $request->status);
        }

        // Filter by date
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $angsuran = $query->paginate(20);

        return view('admin.pinjaman.angsuran', compact('angsuran', 'jenis'));
    }

    /**
     * Generate jadwal angsuran untuk pinjaman.
     */
    private function generateJadwalAngsuran(PinjamanH $pinjaman)
    {
        $jumlahAngsuran = $pinjaman->lama_pinjam;
        $jumlahPinjam = $pinjaman->jumlah_pinjam;
        $bungaRp = $pinjaman->bunga_rp;
        $totalTagihan = $jumlahPinjam + $bungaRp;
        $jumlahPerAngsuran = $totalTagihan / $jumlahAngsuran;

        $tanggalMulai = $pinjaman->tgl_pinjam;

        for ($i = 1; $i <= $jumlahAngsuran; $i++) {
            $tanggalJatuhTempo = clone $tanggalMulai;
            
            if ($pinjaman->jenis === 'bulanan') {
                $tanggalJatuhTempo->addMonths($i);
            } else {
                $tanggalJatuhTempo->addWeeks($i);
            }

            $data = [
                'pinjaman_id' => $pinjaman->id,
                'anggota_id' => $pinjaman->id_anggota,
                'no_urut' => $i,
                'tgl_jatuh_tempo' => $tanggalJatuhTempo,
                'jumlah_tagihan' => $jumlahPerAngsuran,
                'jumlah_terbayar' => 0,
                'status_bayar' => 'belum',
            ];

            if ($pinjaman->jenis === 'bulanan') {
                TempoPinjamanB::create($data);
            } else {
                TempoPinjamanM::create($data);
            }
        }
    }

    /**
     * Get total angsuran telat.
     */
    private function getTotalAngsuranTelat()
    {
        $bulanan = TempoPinjamanB::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count();

        $mingguan = TempoPinjamanM::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count();

        return $bulanan + $mingguan;
    }

    /**
     * Get angsuran jatuh tempo hari ini.
     */
    private function getAngsuranJatuhTempo()
    {
        $bulanan = TempoPinjamanB::whereDate('tgl_jatuh_tempo', today())
            ->where('status_bayar', 'belum')
            ->with(['pinjaman.nasabah.user', 'nasabah.user'])
            ->get()
            ->map(function($item) {
                $item->jenis = 'bulanan';
                return $item;
            });

        $mingguan = TempoPinjamanM::whereDate('tgl_jatuh_tempo', today())
            ->where('status_bayar', 'belum')
            ->with(['pinjaman.nasabah.user', 'nasabah.user'])
            ->get()
            ->map(function($item) {
                $item->jenis = 'mingguan';
                return $item;
            });

        return $bulanan->merge($mingguan)->take(5);
    }

    /**
     * Display detail angsuran.
     */
    public function detailAngsuran(Request $request, $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        }

        return view('admin.pinjaman.detail-angsuran', compact('angsuran', 'jenis'));
    }

    /**
     * Hitung denda untuk angsuran yang telat.
     */
    private function hitungDenda($angsuran, $pinjaman)
    {
        // Jika sudah lunas, tidak ada denda
        if ($angsuran->status_bayar === 'lunas') {
            return 0;
        }

        // Hitung hari telat
        $hariTelat = now()->diffInDays($angsuran->tgl_jatuh_tempo, false);
        
        // Jika belum telat, tidak ada denda
        if ($hariTelat <= 0) {
            return 0;
        }

        // Hitung denda berdasarkan persentase dari pinjaman
        // denda_persen adalah persentase per hari (dalam format decimal, misal 0.02 = 2%)
        $dendaPersen = $pinjaman->denda_persen ?? 0.02; // Default 2% per hari jika tidak ada
        
        // Sisa tagihan yang belum dibayar
        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        
        // Hitung denda: sisa tagihan * (denda persen * hari telat)
        $denda = $sisaTagihan * ($dendaPersen / 100) * $hariTelat;
        
        // Batasi denda maksimal 50% dari jumlah tagihan
        $dendaMax = $angsuran->jumlah_tagihan * 0.5;
        $denda = min($denda, $dendaMax);

        return round($denda, 2);
    }

    /**
     * Update pembayaran angsuran.
     */
    public function updatePembayaranAngsuran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'jenis' => 'required|in:bulanan,mingguan',
        ]);

        if ($request->jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::findOrFail($id);
        }

        $pinjaman = $angsuran->pinjaman;
        if (!$pinjaman) {
            return redirect()->back()
                ->with('error', 'Pinjaman tidak ditemukan');
        }

        // Hitung denda sebelum pembayaran
        $denda = $this->hitungDenda($angsuran, $pinjaman);
        
        // Total yang harus dibayar = tagihan + denda
        $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;
        
        // Hitung jumlah terbayar setelah pembayaran baru
        $jumlahTerbayarSebelumnya = $angsuran->jumlah_terbayar ?? 0;
        $jumlahTerbayar = $jumlahTerbayarSebelumnya + $request->jumlah_bayar;
        
        // Tentukan status bayar
        $statusBayar = 'belum';
        $tglBayar = null;
        
        if ($jumlahTerbayar >= $totalTagihanPlusDenda) {
            // Jika sudah membayar lebih dari atau sama dengan tagihan + denda
            $statusBayar = 'lunas';
            $jumlahTerbayar = $totalTagihanPlusDenda; // Jangan lebih dari tagihan + denda
            $denda = 0; // Reset denda jika sudah lunas
            $tglBayar = now();
        } elseif ($jumlahTerbayar >= $angsuran->jumlah_tagihan) {
            // Jika sudah membayar tagihan pokok, tapi masih ada denda
            $statusBayar = $angsuran->tgl_jatuh_tempo < now() ? 'telat' : 'belum';
            $tglBayar = now();
        } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayar < $angsuran->jumlah_tagihan) {
            // Jika sudah telat dan belum lunas
            $statusBayar = 'telat';
        }

        $angsuran->update([
            'jumlah_terbayar' => $jumlahTerbayar,
            'denda' => $denda,
            'status_bayar' => $statusBayar,
            'tgl_bayar' => $tglBayar,
        ]);

        // Check if all angsuran sudah lunas, update pinjaman
        if ($pinjaman) {
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }
        }

        return redirect()->back()
            ->with('success', 'Pembayaran angsuran berhasil diperbarui');
    }

    /**
     * Pelunasan dipercepat (early payment).
     */
    public function pelunasanDipercepat(Request $request, $id)
    {
        $request->validate([
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pinjaman = PinjamanH::with(['tempoBulanan', 'tempoMingguan'])
            ->findOrFail($id);

        // Cek apakah pinjaman sudah lunas
        if ($pinjaman->lunas === 'lunas') {
            return redirect()->back()
                ->with('error', 'Pinjaman ini sudah lunas');
        }

        // Hitung sisa tagihan
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalTerbayar = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan->sum('jumlah_terbayar')
            : $pinjaman->tempoMingguan->sum('jumlah_terbayar');
        
        $sisaTagihanPokok = $totalTagihan - $totalTerbayar;

        // Hitung total denda dari semua angsuran yang belum lunas
        $totalDenda = 0;
        $angsuranBelumLunas = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->where('status_bayar', '!=', 'lunas')->get()
            : $pinjaman->tempoMingguan()->where('status_bayar', '!=', 'lunas')->get();

        foreach ($angsuranBelumLunas as $a) {
            $denda = $this->hitungDenda($a, $pinjaman);
            $totalDenda += $denda;
        }

        // Hitung potongan (opsional)
        $potongan = $request->potongan ?? 0;
        $jumlahBayar = $sisaTagihanPokok + $totalDenda - $potongan;

        // Update semua angsuran yang belum lunas
        foreach ($angsuranBelumLunas as $a) {
            $denda = $this->hitungDenda($a, $pinjaman);
            $totalPerAngsuran = $a->jumlah_tagihan + $denda;
            
            $a->update([
                'jumlah_terbayar' => $totalPerAngsuran,
                'denda' => 0, // Denda sudah dibayar
                'status_bayar' => 'lunas',
                'tgl_bayar' => now(),
            ]);
        }
        
        // Update pinjaman menjadi lunas
        $pinjaman->update(['lunas' => 'lunas']);

        return redirect()->route('admin.pinjaman.detail-pinjaman', $pinjaman->id)
            ->with('success', 'Pinjaman berhasil dilunasi dipercepat. Total pembayaran: Rp ' . number_format($jumlahBayar, 0, ',', '.'));
    }

    /**
     * Display list of pengajuan pembayaran pinjaman.
     */
    public function pembayaran(Request $request)
    {
        $query = PengajuanPembayaranPinjaman::with(['nasabah.user', 'pinjaman.pengajuan', 'janjiTemu.lokasi'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', '1');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.pinjaman.pembayaran', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan pembayaran.
     */
    public function detailPembayaran($id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'pinjaman.pengajuan',
            'janjiTemu.lokasi',
            'buktiFoto'
        ])->findOrFail($id);

        // Get angsuran yang terkait
        $angsuran = null;
        if ($pengajuan->tempo_id && $pengajuan->jenis_tempo) {
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }
        }

        // Get lokasi untuk janji temu (jika cash)
        $lokasi = JnsLokasiPerusahaan::all();

        return view('admin.pinjaman.detail-pembayaran', compact('pengajuan', 'angsuran', 'lokasi'));
    }

    /**
     * Approve pengajuan pembayaran.
     */
    public function approvePembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk disetujui');
        }

        // Update status menjadi disetujui
        $pengajuan->update([
            'status' => '3', // Disetujui
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.pinjaman.pembayaran')
            ->with('success', 'Pengajuan pembayaran berhasil disetujui');
    }

    /**
     * Reject pengajuan pembayaran.
     */
    public function rejectPembayaran(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        if ($pengajuan->status !== '1') {
            return redirect()->back()
                ->with('error', 'Status pengajuan tidak valid untuk ditolak');
        }

        $pengajuan->update([
            'status' => '2', // Ditolak
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.pinjaman.pembayaran')
            ->with('success', 'Pengajuan pembayaran ditolak');
    }

    /**
     * Upload bukti transfer / konfirmasi pembayaran.
     */
    public function konfirmasiPembayaran(Request $request, $id)
    {
        $pengajuan = PengajuanPembayaranPinjaman::with(['pinjaman'])->findOrFail($id);

        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pembayaran harus disetujui terlebih dahulu');
        }

        try {
            DB::beginTransaction();

            // Get angsuran
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if (!$angsuran) {
                return redirect()->back()
                    ->with('error', 'Data angsuran tidak ditemukan');
            }

            $pinjaman = $pengajuan->pinjaman;

            // Hitung denda
            $denda = $this->hitungDenda($angsuran, $pinjaman);
            $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

            // Update angsuran
            $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + $pengajuan->nominal;
            
            $statusBayar = 'belum';
            if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                $statusBayar = 'lunas';
                $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                $denda = 0;
            } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayarBaru < $angsuran->jumlah_tagihan) {
                $statusBayar = 'telat';
            }

            $angsuran->update([
                'jumlah_terbayar' => $jumlahTerbayarBaru,
                'denda' => $denda,
                'status_bayar' => $statusBayar,
                'tgl_bayar' => now(),
            ]);

            // Jika transfer, upload bukti bisa dilakukan disini
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti-pembayaran-pinjaman', 'public');
                
                BuktiFotoPembayaranPinjaman::create([
                    'id_pengajuan' => $pengajuan->id,
                    'file_photo' => $path,
                    'jenis' => 'bukti_transfer',
                    'keterangan' => $request->keterangan,
                ]);
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
            ]);

            // Check if all angsuran sudah lunas
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }

            DB::commit();

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Pembayaran berhasil dikonfirmasi dan angsuran diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Upload foto serah terima untuk pembayaran cash.
     */
    public function uploadSerahTerima(Request $request, $id)
    {
        $request->validate([
            'foto_serah_terima' => 'required|image|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanPembayaranPinjaman::findOrFail($id);

        if ($pengajuan->status !== '3') {
            return redirect()->back()
                ->with('error', 'Pembayaran harus disetujui terlebih dahulu');
        }

        try {
            DB::beginTransaction();

            // Upload foto serah terima
            $file = $request->file('foto_serah_terima');
            $path = $file->store('bukti-pembayaran-pinjaman', 'public');
            
            BuktiFotoPembayaranPinjaman::create([
                'id_pengajuan' => $pengajuan->id,
                'file_photo' => $path,
                'jenis' => 'serah_terima',
                'keterangan' => $request->keterangan,
            ]);

            // Get angsuran dan update
            $angsuran = null;
            if ($pengajuan->jenis_tempo === 'bulanan') {
                $angsuran = TempoPinjamanB::where('id', $pengajuan->tempo_id)->first();
            } else {
                $angsuran = TempoPinjamanM::where('id', $pengajuan->tempo_id)->first();
            }

            if ($angsuran) {
                $pinjaman = $pengajuan->pinjaman;
                $denda = $this->hitungDenda($angsuran, $pinjaman);
                $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + $denda;

                $jumlahTerbayarBaru = ($angsuran->jumlah_terbayar ?? 0) + $pengajuan->nominal;
                
                $statusBayar = 'belum';
                if ($jumlahTerbayarBaru >= $totalTagihanPlusDenda) {
                    $statusBayar = 'lunas';
                    $jumlahTerbayarBaru = $totalTagihanPlusDenda;
                    $denda = 0;
                } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayarBaru < $angsuran->jumlah_tagihan) {
                    $statusBayar = 'telat';
                }

                $angsuran->update([
                    'jumlah_terbayar' => $jumlahTerbayarBaru,
                    'denda' => $denda,
                    'status_bayar' => $statusBayar,
                    'tgl_bayar' => now(),
                ]);

                // Check if all angsuran sudah lunas
                $allAngsuran = $pinjaman->jenis === 'bulanan' 
                    ? $pinjaman->tempoBulanan 
                    : $pinjaman->tempoMingguan;
                
                $allLunas = $allAngsuran->every(function($item) {
                    return $item->status_bayar === 'lunas';
                });

                if ($allLunas) {
                    $pinjaman->update(['lunas' => 'lunas']);
                }
            }

            // Update status pengajuan menjadi terlaksana
            $pengajuan->update([
                'status' => '4', // Terlaksana
                'tgl_pembayaran' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.pinjaman.pembayaran')
                ->with('success', 'Foto serah terima berhasil diupload dan pembayaran dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
