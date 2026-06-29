<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Nasabah;
use App\Models\PengajuanPerubahanData;
use App\Models\User;
use App\Models\Pekerjaan;
use App\Models\DataKtp;
use App\Models\DataRek;
use App\Models\Darurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class NasabahManagementController extends Controller
{
    /**
     * Display list of all nasabah
     */
    public function index(Request $request)
    {
        $query = Nasabah::with(['user', 'pekerjaan', 'dataKtp', 'dataRek', 'darurat']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        $nasabahList = $query->latest()->paginate(20);

        // Get count of pending changes
        $pendingChangesCount = PengajuanPerubahanData::where('status', 'pending')->count();

        return view('admin.nasabah.index', compact('nasabahList', 'pendingChangesCount'));
    }

    /**
     * Display pending profile change requests
     */
    public function pendingChanges()
    {
        $pendingRequests = PengajuanPerubahanData::with(['nasabah.user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.nasabah.pending-changes', compact('pendingRequests'));
    }

    /**
     * Show detail of a change request
     */
    public function showChangeDetail($id)
    {
        $pengajuan = PengajuanPerubahanData::with(['nasabah.user', 'nasabah.pekerjaan', 'nasabah.dataKtp', 'nasabah.dataRek', 'nasabah.darurat'])
            ->findOrFail($id);

        return view('admin.nasabah.change-detail', compact('pengajuan'));
    }

    /**
     * Approve change request and update data
     */
    public function approveChange(Request $request, $id)
    {
        // Authorization: Admin Utama & Admin Operasional can approve nasabah changes
        if (!app(\App\Services\AdminPermissionService::class)->canApproveNasabahChanges(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin yang dapat menyetujui perubahan data nasabah.'
            ], 403);
        }

        $pengajuan = PengajuanPerubahanData::with('nasabah')->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Update data berdasarkan jenis_data
            $nasabah = $pengajuan->nasabah;
            $dataBaru = $pengajuan->data_baru;

            switch ($pengajuan->jenis_data) {
                case 'data_user':
                    // Update user table only
                    $updateDataUser = [
                        'nama' => $dataBaru['nama'] ?? $nasabah->user->nama,
                        'email' => $dataBaru['email'] ?? $nasabah->user->email,
                        'nomor_hp' => $dataBaru['nomor_hp'] ?? $nasabah->user->nomor_hp,
                    ];
                    
                    if (isset($dataBaru['foto']) && $dataBaru['foto']) {
                        $updateDataUser['foto'] = $dataBaru['foto'];
                    }
                    
                    $nasabah->user->update($updateDataUser);
                    break;

                case 'data_pribadi':
                    // Update user table
                    $nasabah->user->update([
                        'nama' => $dataBaru['nama'] ?? $nasabah->user->nama,
                        'email' => $dataBaru['email'] ?? $nasabah->user->email,
                        'nomor_hp' => $dataBaru['nomor_hp'] ?? $nasabah->user->nomor_hp,
                    ]);

                    // Update nasabah table
                    $nasabah->update([
                        'no_kk' => $dataBaru['no_kk'] ?? $nasabah->no_kk,
                        'tempat_lahir' => $dataBaru['tempat_lahir'] ?? $nasabah->tempat_lahir,
                        'tanggal_lahir' => $dataBaru['tanggal_lahir'] ?? $nasabah->tanggal_lahir,
                        'jenis_kelamin' => $dataBaru['jenis_kelamin'] ?? $nasabah->jenis_kelamin,
                        'alamat' => $dataBaru['alamat'] ?? $nasabah->alamat,
                    ]);
                    break;

                case 'data_ktp':
                    if ($nasabah->dataKtp) {
                        $nasabah->dataKtp->update($dataBaru);
                    } else {
                        DataKtp::create(array_merge($dataBaru, ['id_nasabah' => $nasabah->id]));
                    }
                    break;

                case 'pekerjaan':
                    if ($nasabah->pekerjaan) {
                        $nasabah->pekerjaan->update($dataBaru);
                    } else {
                        Pekerjaan::create(array_merge($dataBaru, ['id_nasabah' => $nasabah->id]));
                    }
                    break;

                case 'rekening':
                    if ($nasabah->dataRek) {
                        $nasabah->dataRek->update($dataBaru);
                    } else {
                        DataRek::create(array_merge($dataBaru, ['id_nasabah' => $nasabah->id]));
                    }
                    break;

                case 'kontak_darurat':
                    if ($nasabah->darurat) {
                        $nasabah->darurat->update($dataBaru);
                    } else {
                        Darurat::create(array_merge($dataBaru, ['id_nasabah' => $nasabah->id]));
                    }
                    break;
            }

            // Update status pengajuan
            $pengajuan->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan_admin' => $request->input('catatan_admin'),
            ]);

            DB::commit();

            Log::info('Perubahan data nasabah disetujui', [
                'admin_id' => auth()->id(),
                'pengajuan_id' => $pengajuan->id,
                'nasabah_id' => $nasabah->id,
            ]);

            app(ActivityLogService::class)->logApprovePerubahanData($pengajuan->id, $nasabah->user->nama ?? 'N/A');

            \App\Models\NasabahNotification::notify(
                $nasabah->id,
                'profil',
                'Perubahan data disetujui',
                'Pengajuan perubahan data profil Anda telah disetujui dan diterapkan.',
                route('nasabah.profile'),
                (string) $pengajuan->id,
                'pengajuan_perubahan_data'
            );

            return redirect()->route('admin.nasabah.pending-changes')
                ->with('success', 'Perubahan data berhasil disetujui dan diterapkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error approve perubahan data', [
                'admin_id' => auth()->id(),
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui perubahan data.');
        }
    }

    /**
     * Reject change request
     */
    public function rejectChange(Request $request, $id)
    {
        // Authorization: Admin Utama & Admin Operasional can reject nasabah changes
        if (!app(\App\Services\AdminPermissionService::class)->canApproveNasabahChanges(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin yang dapat menolak perubahan data nasabah.'
            ], 403);
        }

        $pengajuan = PengajuanPerubahanData::with('nasabah')->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        try {
            $pengajuan->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan_admin' => $request->input('catatan_admin', 'Ditolak oleh admin'),
            ]);

            \App\Models\NasabahNotification::notify(
                $pengajuan->nasabah->id,
                'profil',
                'Perubahan data ditolak',
                'Pengajuan perubahan data profil Anda ditolak. ' . ($request->input('catatan_admin') ?? ''),
                route('nasabah.profile'),
                (string) $pengajuan->id,
                'pengajuan_perubahan_data'
            );

            Log::info('Perubahan data nasabah ditolak', [
                'admin_id' => auth()->id(),
                'pengajuan_id' => $pengajuan->id,
            ]);

            app(ActivityLogService::class)->logRejectPerubahanData($pengajuan->id, $pengajuan->nasabah->user->nama ?? 'N/A');

            return redirect()->route('admin.nasabah.pending-changes')
                ->with('success', 'Perubahan data ditolak.');

        } catch (\Exception $e) {
            Log::error('Error reject perubahan data', [
                'admin_id' => auth()->id(),
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak perubahan data.');
        }
    }

    /**
     * Show detail nasabah
     */
    public function show($id)
    {
        $nasabah = Nasabah::with(['user', 'pekerjaan', 'dataKtp', 'dataRek', 'darurat'])
            ->findOrFail($id);

        // Get pending changes for this nasabah
        $pendingChanges = PengajuanPerubahanData::where('id_nasabah', $nasabah->id)
            ->where('status', 'pending')
            ->get();

        $pinVerified = session('admin_pin_verified', false);

        return view('admin.nasabah.detail', compact('nasabah', 'pendingChanges', 'pinVerified'));
    }

    /**
     * Verifikasi PIN Admin untuk melihat dokumen foto
     */
    public function verifyAdminPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN Anda belum diatur. Silakan atur PIN Anda terlebih dahulu di Pengaturan.',
            ], 400);
        }

        // Clean up separators if any
        $inputPin = str_replace(['.', ','], '', $request->pin);

        if (Hash::check($inputPin, $user->pin)) {
            session(['admin_pin_verified' => true]);

            return response()->json([
                'success' => true,
                'message' => 'PIN berhasil diverifikasi.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN yang Anda masukkan salah.',
        ], 422);
    }

    /**
     * Reset PIN nasabah (for lupa PIN cases)
     * Route: POST /admin/nasabah/{id}/reset-pin
     */
    public function resetPin(Request $request, $id)
    {
        // Authorization: Only Admin Utama can reset nasabah PIN
        if (!app(\App\Services\AdminPermissionService::class)->canManageNasabah(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mereset PIN nasabah.'
            ], 403);
        }

        $nasabah = Nasabah::with('user')->findOrFail($id);
        
        // Validasi input PIN baru
        $request->validate([
            'pin_baru' => 'required|digits:6',
        ], [
            'pin_baru.required' => 'PIN baru harus diisi',
            'pin_baru.digits' => 'PIN harus 6 digit',
        ]);

        try {
            $pinBaru = (int) $request->pin_baru;

            // Update PIN nasabah
            $nasabah->user->update([
                'pin' => $pinBaru,
            ]);

            Log::info('Admin reset PIN nasabah', [
                'admin_id' => auth()->id(),
                'admin_email' => auth()->user()->email,
                'nasabah_id' => $nasabah->id,
                'nasabah_email' => $nasabah->user->email,
                'timestamp' => now(),
            ]);

            app(ActivityLogService::class)->logResetPin($nasabah->id, $nasabah->user->nama ?? 'N/A');

            return redirect()->back()->with('success', 'PIN nasabah berhasil direset. Silakan informasikan PIN baru kepada nasabah melalui WhatsApp.');
        } catch (\Exception $e) {
            Log::error('Error reset PIN nasabah', [
                'admin_id' => auth()->id(),
                'nasabah_id' => $nasabah->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat reset PIN. Silakan coba lagi.');
        }
    }

    /**
     * Generate random PIN 6 digit untuk nasabah
     * Route: GET /admin/nasabah/{id}/generate-pin (API)
     */
    public function generateRandomPin()
    {
        // Authorization: Only Admin Utama can generate random PIN
        if (!app(\App\Services\AdminPermissionService::class)->canManageNasabah(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini.'
            ], 403);
        }

        $pin = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        return response()->json([
            'pin' => $pin,
        ]);
    }

    /**
     * Verify a newly registered nasabah account
     * Route: POST /admin/nasabah/{id}/verify
     */
    public function verifyNasabah(Request $request, $id)
    {
        // Authorization: Admin Utama & Admin Operasional can verify nasabah
        if (!app(\App\Services\AdminPermissionService::class)->canVerifyNasabah(auth()->user())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin yang dapat memverifikasi nasabah.');
        }

        $nasabah = Nasabah::with('user')->findOrFail($id);

        if ($nasabah->user->verified !== null) {
            return redirect()->back()->with('error', 'Nasabah ini sudah diverifikasi sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $nasabah->user->update([
                'verified' => now(),
            ]);

            DB::commit();

            Log::info('Admin verifikasi akun nasabah', [
                'admin_id' => auth()->id(),
                'admin_email' => auth()->user()->email,
                'nasabah_id' => $nasabah->id,
                'nasabah_email' => $nasabah->user->email,
                'timestamp' => now(),
            ]);

            app(ActivityLogService::class)->logVerifyNasabah($nasabah->id, $nasabah->user->nama ?? 'N/A');

            // Send notification to nasabah
            \App\Models\NasabahNotification::notify(
                $nasabah->id,
                'profil',
                'Akun diverifikasi',
                'Selamat! Akun Anda telah diverifikasi oleh admin. Sekarang Anda dapat menggunakan seluruh layanan kami.',
                route('nasabah.dashboard'),
                (string) $nasabah->id,
                'verifikasi_akun'
            );

            return redirect()->back()->with('success', 'Akun nasabah berhasil diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error verify nasabah', [
                'admin_id' => auth()->id(),
                'nasabah_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi nasabah.');
        }
    }
}
