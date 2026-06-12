<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Services\BankAccessService;
use App\Models\DepositoH;
use App\Models\JnsTenorDeposito;
use App\Models\PengajuanDeposito;
use App\Models\PencairanDeposito;
use App\Models\NasabahNotification;
use App\Models\SukuBungaDeposito;
use App\Models\PengajuanTabungan;
use App\Models\PaketDeposito;
use App\Models\JnsBank;
use App\Models\KategoriDeposito;
use App\Models\MasterDendaDeposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DepositoController extends Controller
{
    /**
     * Dashboard Deposito Nasabah
     */
    public function index()
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        // Ambil semua paket deposito aktif
        $pakets = PaketDeposito::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('tenor_bulan')
            ->orderBy('minimal_nominal')
            ->get();

        // Jenis deposito dinamis dari database
        $jenisDeposito = KategoriDeposito::where('status', 'aktif')->get();

        // Ambil deposito aktif nasabah
        $depositoAktif = [];
        $riwayatPengajuan = [];

        if ($nasabah) {
            $depositoAktif = DepositoH::where('id_nasabah', $nasabah->id)
                ->whereIn('status', ['aktif'])
                ->with('tenor')
                ->latest()
                ->get();

            $riwayatPengajuan = PengajuanDeposito::where('id_nasabah', $nasabah->id)
                ->with('tenor')
                ->latest()
                ->take(5)
                ->get();
        }

        $now = now();
        $isLeap = (($now->year % 4 === 0 && $now->year % 100 !== 0) || ($now->year % 400 === 0));
        $pembagiHari = $isLeap ? 366 : 365;
        $pajakRate = \App\Models\Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20;

        return view('nasabah.deposito.index', compact(
            'pakets',
            'jenisDeposito',
            'depositoAktif',
            'riwayatPengajuan',
            'pembagiHari',
            'pajakRate'
        ));
    }

    /**
     * Riwayat Seluruh Transaksi Deposito Nasabah
     */
    public function riwayat()
    {
        $nasabah = Auth::user()->nasabah;

        // BANK ACCESS GUARD dihapus dari riwayat agar nasabah selalu bisa melihat riwayat deposito

        $riwayat = PengajuanDeposito::where('id_nasabah', $nasabah->id)
            ->with(['tenor', 'deposito.pencairan'])
            ->latest()
            ->paginate(10);

        return view('nasabah.deposito.riwayat', compact('riwayat'));
    }

    /**
     * Get saldo nasabah (copied from TabunganController)
     */
    private function getSaldoNasabah($idAnggota)
    {
        $totalSetoran = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
            ->sum('nominal') ?? 0;

        $totalPenarikan = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
            ->sum('nominal') ?? 0;

        $pengajuanApproved = \App\Models\PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->sum('nominal') ?? 0;

        $saldo = max(0, $totalSetoran + $pengajuanApproved - $totalPenarikan);

        // Kurangi juga dengan deposito yang diajukan menggunakan metode saldo tabungan namun belum diproses
        $pendingDepositoTabungan = \App\Models\PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1') // Pending
            ->where('metode_setor', 'saldo_tabungan')
            ->sum('nominal') ?? 0;

        return max(0, $saldo - $pendingDepositoTabungan);
    }

    /**
     * Form Pengajuan Deposito
     */
    public function pengajuan()
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $pakets = PaketDeposito::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('tenor_bulan')
            ->orderBy('minimal_nominal')
            ->get();

        // Jenis deposito dinamis
        $jenisDeposito = KategoriDeposito::where('status', 'aktif')->get();

        // Saldo tabungan nasabah dari history transaksi
        $saldoTabungan = $nasabah ? $this->getSaldoNasabah($nasabah->id) : 0;

        // Daftar Bank untuk Info Rekening
        $banks = JnsBank::where('status', 'aktif')->get();

        $now = now();
        $isLeap = (($now->year % 4 === 0 && $now->year % 100 !== 0) || ($now->year % 400 === 0));
        $pembagiHari = $isLeap ? 366 : 365;
        $pajakRate = \App\Models\Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20;

        return view('nasabah.deposito.pengajuan', compact(
            'pakets',
            'jenisDeposito',
            'saldoTabungan',
            'banks',
            'pembagiHari',
            'pajakRate'
        ));
    }

    /**
     * Submit Pengajuan Deposito
     */
    public function submitPengajuan(Request $request)
    {
        $request->validate([
            'pin'            => 'required|numeric|digits:6',
            'nominal'        => 'required|numeric|min:1000000',
            'paket_id'       => 'required|exists:paket_depositos,id',
            'metode_setor'   => 'required|in:transfer,saldo_tabungan',
            'foto_bukti_tf'  => 'nullable|required_if:metode_setor,transfer|image|max:5120',
        ]);

        $nasabah = Auth::user()->nasabah;

        // Verify PIN
        $user = Auth::user();
        if (!$user->pin) {
            return back()->with('error', 'PIN belum diatur. Silakan atur PIN terlebih dahulu di profil Anda.')
                ->withInput($request->except('pin'));
        }

        if (!Hash::check($request->pin, $user->pin)) {
            return back()->with('error', 'PIN yang Anda masukkan salah!')
                ->withInput($request->except('pin'));
        }

        // ── BANK ACCESS GUARD (server-side double check) ───────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────
        
        $paket = PaketDeposito::findOrFail($request->paket_id);
        if ($request->nominal < $paket->minimal_nominal) {
            return back()->with('error', 'Nominal pengajuan kurang dari batas minimal paket ini.')->withInput();
        }
        if ($paket->maksimal_nominal && $request->nominal > $paket->maksimal_nominal) {
            return back()->with('error', 'Nominal pengajuan melebihi batas maksimal paket ini.')->withInput();
        }

        if ($request->metode_setor === 'saldo_tabungan') {
            $saldo = $this->getSaldoNasabah($nasabah->id);
            if ($saldo < $request->nominal) {
                return back()->with('error', 'Saldo Tabungan tidak mencukupi untuk membuka Deposito.')->withInput();
            }
        }
        
        // Cari mapping tenor_id untuk backward compatibility
        $tenorDb = JnsTenorDeposito::where('tenor_bulan', $paket->tenor_bulan)->first();
        $tenorId = $tenorDb ? $tenorDb->id : 1; // Default fallback if not found

        $data = [
            'id_nasabah'     => $nasabah->id,
            'paket_id'       => $paket->id,
            'nominal'        => $request->nominal,
            'tenor_id'       => $tenorId, // Dipertahankan untuk compatibility Warning System
            'metode_setor'   => $request->metode_setor,
            'status'         => '1',
            'catatan'        => $request->catatan,
        ];

        if ($request->hasFile('foto_bukti_tf') && $request->metode_setor === 'transfer') {
            $data['foto_bukti_tf'] = $request->file('foto_bukti_tf')
                ->store('deposito/bukti-tf', 'public');
        }

        $pengajuan = PengajuanDeposito::create($data);

        app(\App\Services\ActivityLogService::class)->logSubmitPengajuanDeposito(
            $pengajuan->id,
            $request->nominal
        );

        return redirect()->route('nasabah.deposito.status-pengajuan', $pengajuan->id)
            ->with('success', 'Pengajuan deposito berhasil dikirim! Kami akan memproses pengajuan Anda.');
    }

    /**
     * Verify PIN AJAX.
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diatur. Silakan atur PIN terlebih dahulu.'
            ], 400);
        }

        if (!Hash::check($request->pin, $user->pin)) {
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
     * Status Pengajuan Deposito
     */
    public function statusPengajuan($id)
    {
        $nasabah = Auth::user()->nasabah;

        $pengajuan = PengajuanDeposito::where('id_nasabah', $nasabah->id)
            ->with(['tenor'])
            ->findOrFail($id);

        return view('nasabah.deposito.status-pengajuan', compact('pengajuan'));
    }

    /**
     * Detail Deposito Aktif
     */
    public function detail($id)
    {
        $nasabah = Auth::user()->nasabah;

        // ── BANK ACCESS GUARD ──────────────────────────────────────
        if ($nasabah) {
            $access = app(BankAccessService::class)->checkPremiumAccess($nasabah->id);
            if (!$access['allowed']) {
                return redirect()->route('nasabah.dashboard')
                    ->with('error', $access['reason']);
            }
        }
        // ──────────────────────────────────────────────────────────

        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->with(['tenor', 'bungaHarian', 'transDeposito', 'pencairan'])
            ->findOrFail($id);

        // Data denda untuk tampilan pembatalan
        $dendaAktif = MasterDendaDeposito::getDendaAktif();
        $dendaPersen = $dendaAktif ? (float) $dendaAktif->denda_persen : 0;
        $nominalDenda = $deposito->nominal_awal * ($dendaPersen / 100);
        $nominalSetelahDenda = $deposito->nominal_awal - $nominalDenda;

        $tglJatuhTempo = $deposito->tgl_jatuh_tempo ?? now();
        $isLeap = (($tglJatuhTempo->year % 4 === 0 && $tglJatuhTempo->year % 100 !== 0) || ($tglJatuhTempo->year % 400 === 0));
        $pembagiHari = $isLeap ? 366 : 365;
        $pajakRate = \App\Models\Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20;

        return view('nasabah.deposito.detail', compact('deposito', 'dendaPersen', 'nominalDenda', 'nominalSetelahDenda', 'pembagiHari', 'pajakRate'));
    }

    /**
     * Ajukan Pencairan Deposito
     */
    public function ajukanCairkan(Request $request, $id)
    {
        $request->validate([
            'jenis_pencairan' => 'required|in:rek_nasabah,saldo_tabungan',
        ]);

        $nasabah  = Auth::user()->nasabah;
        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->with('tenor')->findOrFail($id);

        // Hitung nominal akhir: pokok + bunga bersih
        $tglJatuhTempo = $deposito->tgl_jatuh_tempo;
        $isLeap = $tglJatuhTempo ? (($tglJatuhTempo->year % 4 === 0 && $tglJatuhTempo->year % 100 !== 0) || ($tglJatuhTempo->year % 400 === 0)) : false;
        $pembagi = $isLeap ? 366 : 365;
        
        $pajakRate   = \App\Models\Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20;
        $bungaKotor  = $deposito->nominal_awal * $deposito->bunga * (($deposito->tenor?->tenor_hari ?? 30) / $pembagi);
        $pajak       = $bungaKotor * $pajakRate;
        $nominalAkhir = $deposito->nominal_awal + $bungaKotor - $pajak;

        // Cek apakah sudah ada request yang pending
        $existing = PencairanDeposito::where('deposito_id', $deposito->id)
            ->where('status', 'pending')->first();

        if ($existing) {
            return back()->with('error', 'Permintaan pencairan sudah diajukan sebelumnya dan masih dalam proses.');
        }

        // Buat record pencairan
        PencairanDeposito::create([
            'deposito_id'     => $deposito->id,
            'id_nasabah'      => $nasabah->id,
            'jenis_pencairan' => $request->jenis_pencairan,
            'metode_pencairan'=> $request->jenis_pencairan, // compat
            'nominal_akhir'   => $nominalAkhir,
            'status'          => 'pending',
            'catatan'         => 'Pengajuan pencairan oleh nasabah via ' .
                ($request->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening' : 'Saldo Tabungan'),
        ]);

        return back()->with('success', 'Permintaan pencairan berhasil diajukan. Admin kami akan segera memprosesnya.');
    }
    /**
     * Ajukan Cancel Deposito
     */
    public function ajukanCancel(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|numeric',
            'jenis_pencairan' => 'required|in:saldo_tabungan,rek_nasabah',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->pin, $user->pin)) {
            return back()->with('error', 'PIN yang Anda masukkan salah.');
        }

        $nasabah  = $user->nasabah;
        $deposito = DepositoH::where('id_nasabah', $nasabah->id)
            ->findOrFail($id);

        // Cek apakah sudah ada request yang pending
        $existing = PencairanDeposito::where('deposito_id', $deposito->id)
            ->where('status', 'pending')->first();

        if ($existing) {
            return back()->with('error', 'Permintaan pencairan/pembatalan sudah diajukan sebelumnya dan masih dalam proses.');
        }

        // Hitung denda pembatalan dini
        $dendaAktif = MasterDendaDeposito::getDendaAktif();
        $dendaPersen = $dendaAktif ? (float) $dendaAktif->denda_persen : 0;
        $nominalDenda = $deposito->nominal_awal * ($dendaPersen / 100);
        $nominalAkhir = $deposito->nominal_awal - $nominalDenda;

        // Buat record pencairan dengan flag is_cancel = true
        PencairanDeposito::create([
            'deposito_id'     => $deposito->id,
            'id_nasabah'      => $nasabah->id,
            'jenis_pencairan' => $request->jenis_pencairan,
            'metode_pencairan'=> $request->jenis_pencairan, // compat
            'nominal_akhir'   => $nominalAkhir,
            'nominal_denda'   => $nominalDenda,
            'status'          => 'pending',
            'is_cancel'       => true,
            'catatan'         => 'Pengajuan pembatalan deposito oleh nasabah via ' .
                ($request->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening Bank' : 'Saldo Tabungan') .
                ($nominalDenda > 0 ? '. Denda ' . $dendaPersen . '% = Rp ' . number_format($nominalDenda, 0, ',', '.') : '') . '.',
        ]);

        return back()->with('success', 'Permintaan pembatalan deposito berhasil diajukan. Admin kami akan segera memproses pengembalian dana.');
    }
}
