<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\BuktiFotoTabungan;
use App\Models\JnsLokasiPerusahaan;
use App\Models\TransTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TabunganController extends Controller
{
    /**
     * Show the tabungan dashboard.
     */
    public function index()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldo = $this->getSaldoNasabah($idAnggota);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo' => $saldo,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];
        $transaksiTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        // Get riwayat janji temu from database
        $riwayatJanjiTemu = JanjiTemuTabungan::whereHas('pengajuan', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota);
            })
            ->with('lokasi')
            ->latest('tanggal_janji_temu')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.index', [
            'user' => auth()->user(),
            'tabunganInfo' => $tabunganInfo,
            'transaksiTabungan' => $transaksiTabungan,
            'riwayatJanjiTemu' => $riwayatJanjiTemu,
        ]);
    }

    /**
     * Show the nabung sekarang page.
     */
    public function nabungSekarang()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Get riwayat setoran from database
        $riwayatTabungan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => auth()->user(),
            'riwayatTabungan' => $riwayatTabungan,
        ]);
    }

    /**
     * Show the pengajuan transfer page.
     */
    public function pengajuanTransfer()
    {
        return view('nasabah.tabungan.pengajuan-transfer', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the penarikan tabungan page.
     */
    public function penarikanTabungan()
    {
        $idAnggota = $this->getIdAnggota();
        
        // Calculate saldo from database
        $saldo = $this->getSaldoNasabah($idAnggota);
        
        // Tabungan info from database
        $tabunganInfo = (object) [
            'saldo' => $saldo,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Get riwayat penarikan from database
        $riwayatPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->latest('tgl_transaksi')
            ->take(10)
            ->get();

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => auth()->user(),
            'tabunganInfo' => $tabunganInfo,
            'riwayatPenarikan' => $riwayatPenarikan,
        ]);
    }

    /**
     * Verify PIN before submit.
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();
        
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
            ], 400);
        }

        if ($user->pin != $request->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'PIN berhasil diverifikasi.'
        ]);
    }

    /**
     * Submit pengajuan setoran tabungan.
     */
    public function submitSetoran(Request $request)
    {
        // Check authentication first
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        $request->validate([
            'pin' => 'required|numeric|digits:6',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'bukti_foto.*' => 'required|image|max:5120',
            'nominal_foto.*' => 'required|string',
            'keterangan_foto.*' => 'nullable|string|max:255',
        ]);

        // Verify PIN
        $user = auth()->user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->back()
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.pengajuan-transfer')
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get nasabah ID from auth
            $idAnggota = $this->getIdAnggota();

            // This method is now only for transfer
            if ($request->metode ?? 'transfer' === 'transfer') {
                // Validate bukti foto exists
                if (!$request->hasFile('bukti_foto') || count($request->file('bukti_foto')) == 0) {
                    return redirect()->route('nasabah.tabungan.pengajuan-transfer')
                        ->with('error', 'Minimal upload 1 bukti transfer')
                        ->withInput($request->except('pin'));
                }

                // Create pengajuan tabungan
                $pengajuan = PengajuanTabungan::create([
                    'id_anggota' => $idAnggota,
                    'foto_bukti_tf' => 'transfer', // Indikator bahwa ini transfer
                    'keterangan' => $request->keterangan,
                    'status' => '1', // Pending
                ]);

                // Handle multiple bukti foto
                if ($request->hasFile('bukti_foto')) {
                    foreach ($request->file('bukti_foto') as $index => $file) {
                        $path = $file->store('bukti_tabungan', 'public');
                        
                        // Parse nominal from formatted currency string
                        $nominalStr = $request->nominal_foto[$index] ?? '0';
                        $nominal = (float) str_replace(['.', ','], '', $nominalStr);
                        
                        BuktiFotoTabungan::create([
                            'id_pengajuan' => $pengajuan->id,
                            'file_photo' => $path,
                            'jenis' => 'tabungan',
                            'nominal' => $nominal > 0 ? $nominal : $request->nominal,
                            'keterangan' => $request->keterangan_foto[$index] ?? 'Bukti transfer',
                        ]);
                    }
                }

                return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                    ->with('success', 'Pengajuan setoran via transfer berhasil dikirim!');
            }

            return redirect()->route('nasabah.tabungan.pengajuan-transfer')
                ->with('error', 'Metode tidak valid')
                ->withInput($request->except('pin'));
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.pengajuan-transfer')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Show janji temu page for setoran tunai.
     */
    public function janjiTemu(Request $request)
    {
        $lokasi = JnsLokasiPerusahaan::where('status_aktif', true)->get();
        
        return view('nasabah.tabungan.janji-temu', [
            'lokasi' => $lokasi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);
    }

    /**
     * Submit janji temu.
     */
    public function submitJanjiTemu(Request $request)
    {
        // Check authentication first
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        try {
            $validated = $request->validate([
                'pin' => 'required|numeric|digits:6',
                'nominal' => 'required|numeric|min:10000',
                'lokasi_temu' => 'required|exists:jns_lokasi_perusahaan,id',
                'tanggal_janji_temu' => 'required|date|after:today',
                'waktu_janji_temu' => 'required|date_format:H:i',
                'keterangan' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->withErrors($e->validator)
                ->withInput($request->except('pin'));
        }

        // Verify PIN
        $user = auth()->user();
        
        // Check if user has PIN
        if (!$user->pin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        // Convert both to integer for comparison (handles string/int mismatch)
        $userPin = (int) $user->pin;
        $inputPin = (int) $request->pin;

        if ($userPin !== $inputPin) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        try {
            // Get ID anggota after PIN verification
            $idAnggota = $this->getIdAnggota();

            // Create pengajuan tabungan
            $pengajuan = PengajuanTabungan::create([
                'id_anggota' => $idAnggota,
                'foto_bukti_tf' => 'tunai',
                'keterangan' => $request->keterangan,
                'status' => '1', // Pending
            ]);

            // Create janji temu
            $tanggalWaktu = \Carbon\Carbon::parse($request->tanggal_janji_temu . ' ' . $request->waktu_janji_temu);
            
            JanjiTemuTabungan::create([
                'id_pengajuan' => $pengajuan->id,
                'lokasi_temu' => $request->lokasi_temu,
                'nominal' => $request->nominal,
                'tanggal_janji_temu' => $tanggalWaktu,
                'waktu_janji_temu' => $tanggalWaktu,
            ]);

            return redirect()->route('nasabah.tabungan.status-pengajuan-setor')
                ->with('success', 'Janji temu berhasil dibuat!');
                
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return redirect()->route('login')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('nasabah.dashboard')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nasabah.tabungan.janji-temu', [
                'nominal' => $request->nominal ?? '',
                'keterangan' => $request->keterangan ?? '',
            ])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput($request->except('pin'));
        }
    }

    /**
     * Submit pengajuan penarikan tabungan.
     */
    public function submitPenarikan(Request $request)
    {
        $request->validate([
            'metode' => 'required|in:tunai,transfer',
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'no_rekening' => 'required_if:metode,transfer|string|max:50',
        ]);

        $idAnggota = $this->getIdAnggota();

        // Check saldo
        $saldo = $this->getSaldoNasabah($idAnggota);
        if ($saldo < $request->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo tidak mencukupi!')
                ->withInput();
        }

        // Create pengajuan penarikan
        PengajuanPenarikanTabungan::create([
            'id_anggota' => $idAnggota,
            'tgl_pengajuan' => now(),
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan . ($request->metode === 'transfer' ? ' | Rekening: ' . $request->no_rekening : ''),
            'status' => '1', // Pending
        ]);

        return redirect()->route('nasabah.tabungan.status-pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan berhasil dikirim!');
    }

    /**
     * Show status pengajuan setoran.
     */
    public function statusPengajuanSetor()
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto', 'janjiTemu.lokasi'])
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show status pengajuan penarikan.
     */
    public function statusPengajuanTarik()
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->latest()
            ->paginate(10);

        return view('nasabah.tabungan.status-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan setor.
     */
    public function detailPengajuanSetor($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->with(['buktiFoto', 'janjiTemu.lokasi'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-setor', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan tarik.
     */
    public function detailPengajuanTarik($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $pengajuan = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-pengajuan-tarik', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail transaksi tabungan.
     */
    public function detailTransaksi($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $transaksi = TransTabungan::where('id_anggota', $idAnggota)
            ->with(['pengajuanSetor.buktiFoto', 'pengajuanTarik'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-transaksi', [
            'transaksi' => $transaksi,
        ]);
    }

    /**
     * Show detail janji temu.
     */
    public function detailJanjiTemu($id)
    {
        $idAnggota = $this->getIdAnggota();
        
        $janjiTemu = JanjiTemuTabungan::whereHas('pengajuan', function($q) use ($idAnggota) {
                $q->where('id_anggota', $idAnggota);
            })
            ->with(['pengajuan', 'lokasi'])
            ->findOrFail($id);

        return view('nasabah.tabungan.detail-janji-temu', [
            'janjiTemu' => $janjiTemu,
        ]);
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

    /**
     * Get ID anggota from authenticated user.
     */
    private function getIdAnggota()
    {
        $user = auth()->user();
        
        if (!$user) {
            // Don't use abort here as it causes redirect issues
            // Throw exception instead and handle in try-catch at caller
            throw new \Illuminate\Auth\AuthenticationException('Unauthenticated');
        }

        $nasabah = $user->nasabah;
        
        if (!$nasabah) {
            // Throw exception instead of abort
            throw new \Illuminate\Auth\Access\AuthorizationException('User tidak memiliki data nasabah');
        }

        return $nasabah->id;
    }
}

