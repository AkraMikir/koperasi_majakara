<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                    $nasabah->user->update([
                        'nama' => $dataBaru['nama'] ?? $nasabah->user->nama,
                        'email' => $dataBaru['email'] ?? $nasabah->user->email,
                        'nomor_hp' => $dataBaru['nomor_hp'] ?? $nasabah->user->nomor_hp,
                    ]);
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
        $pengajuan = PengajuanPerubahanData::findOrFail($id);

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

            Log::info('Perubahan data nasabah ditolak', [
                'admin_id' => auth()->id(),
                'pengajuan_id' => $pengajuan->id,
            ]);

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

        return view('admin.nasabah.detail', compact('nasabah', 'pendingChanges'));
    }
}
