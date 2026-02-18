<?php

namespace App\Http\Controllers\Nasabah;

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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Submit pengajuan perubahan data dengan PIN verification
     */
    public function submitUpdateRequest(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $nasabah = $user->nasabah;

        if (!$nasabah) {
            return redirect()->back()->with('error', 'Data nasabah tidak ditemukan.');
        }

        // Validasi PIN terlebih dahulu
        $validator = Validator::make($request->all(), [
            'pin' => 'required|string|size:6',
            'jenis_data' => 'required|in:data_user,data_pribadi,data_ktp,pekerjaan,rekening,kontak_darurat',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Data tidak valid.')
                ->withInput();
        }

        // Verify PIN
        $inputPin = (int) str_replace(['.', ','], '', $request->pin);
        $userPin = (int) $user->pin;

        if ($inputPin !== $userPin) {
            return redirect()->back()
                ->with('error', 'PIN yang Anda masukkan salah.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get data lama dan data baru berdasarkan jenis_data
            $jenisData = $request->jenis_data;
            $dataLama = $this->getDataLama($nasabah, $jenisData);
            $dataBaru = $this->getDataBaru($request, $jenisData);

            // Validasi data baru berdasarkan jenis
            $validationResult = $this->validateDataBaru($dataBaru, $jenisData);
            if ($validationResult !== true) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors($validationResult)
                    ->with('error', 'Terdapat kesalahan pada data yang diinput.')
                    ->withInput();
            }

            // Cek apakah ada pengajuan pending untuk jenis data yang sama
            $existingPending = PengajuanPerubahanData::where('id_nasabah', $nasabah->id)
                ->where('jenis_data', $jenisData)
                ->where('status', 'pending')
                ->first();

            if ($existingPending) {
                // Update pengajuan yang sudah ada
                $existingPending->update([
                    'data_baru' => $dataBaru,
                    'data_lama' => $dataLama,
                ]);

                DB::commit();

                Log::info('Pengajuan perubahan data diperbarui', [
                    'user_id' => $user->id,
                    'jenis_data' => $jenisData,
                ]);

                return redirect()->back()
                    ->with('success', 'Pengajuan perubahan data berhasil diperbarui! Menunggu persetujuan admin.');
            } else {
                // Buat pengajuan baru
                PengajuanPerubahanData::create([
                    'id_nasabah' => $nasabah->id,
                    'jenis_data' => $jenisData,
                    'data_lama' => $dataLama,
                    'data_baru' => $dataBaru,
                    'status' => 'pending',
                ]);

                DB::commit();

                Log::info('Pengajuan perubahan data dibuat', [
                    'user_id' => $user->id,
                    'jenis_data' => $jenisData,
                ]);

                $newPengajuan = PengajuanPerubahanData::where('id_nasabah', $nasabah->id)->where('status', 'pending')->latest()->first();
                if ($newPengajuan) {
                    app(ActivityLogService::class)->logSubmitPerubahanData($newPengajuan->id);
                }

                return redirect()->back()
                    ->with('success', 'Pengajuan perubahan data berhasil dikirim! Menunggu persetujuan admin.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error submit pengajuan perubahan data', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengajukan perubahan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Cancel pengajuan perubahan data
     */
    public function cancelUpdateRequest($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $nasabah = $user->nasabah;

        $pengajuan = PengajuanPerubahanData::where('id', $id)
            ->where('id_nasabah', $nasabah->id)
            ->where('status', 'pending')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan tidak ditemukan atau sudah diproses.');
        }

        try {
            $pengajuan->delete();

            Log::info('Pengajuan perubahan data dibatalkan', [
                'user_id' => $user->id,
                'pengajuan_id' => $id,
            ]);

            app(ActivityLogService::class)->logBatalPerubahanData($id);

            return redirect()->back()
                ->with('success', 'Pengajuan perubahan data berhasil dibatalkan.');

        } catch (\Exception $e) {
            Log::error('Error cancel pengajuan', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pengajuan.');
        }
    }

    /**
     * Get data lama berdasarkan jenis data
     */
    private function getDataLama($nasabah, $jenisData)
    {
        switch ($jenisData) {
            case 'data_user':
                return [
                    'nama' => $nasabah->user->nama ?? '',
                    'email' => $nasabah->user->email ?? '',
                    'nomor_hp' => $nasabah->user->nomor_hp ?? '',
                ];

            case 'data_pribadi':
                return [
                    'nama' => $nasabah->user->nama ?? '',
                    'email' => $nasabah->user->email ?? '',
                    'nomor_hp' => $nasabah->user->nomor_hp ?? '',
                    'no_kk' => $nasabah->no_kk ?? '',
                    'tempat_lahir' => $nasabah->tempat_lahir ?? '',
                    'tanggal_lahir' => $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format('Y-m-d') : '',
                    'jenis_kelamin' => $nasabah->jenis_kelamin ?? '',
                    'alamat' => $nasabah->alamat ?? '',
                ];

            case 'data_ktp':
                $dataKtp = $nasabah->dataKtp;
                return $dataKtp ? [
                    'nik' => $dataKtp->nik ?? '',
                    'nama_lengkap' => $dataKtp->nama_lengkap ?? '',
                    'tempat_lahir' => $dataKtp->tempat_lahir ?? '',
                    'tanggal_lahir' => $dataKtp->tanggal_lahir ? $dataKtp->tanggal_lahir->format('Y-m-d') : '',
                    'jenis_kelamin' => $dataKtp->jenis_kelamin ?? '',
                    'alamat' => $dataKtp->alamat ?? '',
                ] : [];

            case 'pekerjaan':
                $pekerjaan = $nasabah->pekerjaan;
                return $pekerjaan ? [
                    'pekerjaan' => $pekerjaan->pekerjaan ?? '',
                    'nama_perusahaan' => $pekerjaan->nama_perusahaan ?? '',
                    'penghasilan' => $pekerjaan->penghasilan ?? '',
                ] : [];

            case 'rekening':
                $dataRek = $nasabah->dataRek;
                return $dataRek ? [
                    'nama_bank' => $dataRek->nama_bank ?? '',
                    'no_rekening' => $dataRek->no_rekening ?? '',
                    'nama_pemilik_rekening' => $dataRek->nama_pemilik_rekening ?? '',
                ] : [];

            case 'kontak_darurat':
                $darurat = $nasabah->darurat;
                return $darurat ? [
                    'nama_lengkap' => $darurat->nama_lengkap ?? '',
                    'hubungan_peminjam' => $darurat->hubungan_peminjam ?? '',
                    'no_telepon' => $darurat->no_telepon ?? '',
                    'email' => $darurat->email ?? '',
                    'pekerjaan' => $darurat->pekerjaan ?? '',
                    'no_ktp' => $darurat->no_ktp ?? '',
                    'alamat' => $darurat->alamat ?? '',
                ] : [];

            default:
                return [];
        }
    }

    /**
     * Get data baru dari request berdasarkan jenis data
     */
    private function getDataBaru($request, $jenisData)
    {
        switch ($jenisData) {
            case 'data_user':
                return [
                    'nama' => $request->input('nama', ''),
                    'email' => $request->input('email', ''),
                    'nomor_hp' => $request->input('nomor_hp', ''),
                ];

            case 'data_pribadi':
                return [
                    'nama' => $request->input('nama', ''),
                    'email' => $request->input('email', ''),
                    'nomor_hp' => $request->input('nomor_hp', ''),
                    'no_kk' => $request->input('no_kk', ''),
                    'tempat_lahir' => $request->input('tempat_lahir', ''),
                    'tanggal_lahir' => $request->input('tanggal_lahir', ''),
                    'jenis_kelamin' => $request->input('jenis_kelamin', ''),
                    'alamat' => $request->input('alamat', ''),
                ];

            case 'data_ktp':
                return [
                    'nik' => $request->input('nik', ''),
                    'nama_lengkap' => $request->input('nama_lengkap', ''),
                    'tempat_lahir' => $request->input('tempat_lahir_ktp', ''),
                    'tanggal_lahir' => $request->input('tanggal_lahir_ktp', ''),
                    'jenis_kelamin' => $request->input('jenis_kelamin_ktp', ''),
                    'alamat' => $request->input('alamat_ktp', ''),
                ];

            case 'pekerjaan':
                return [
                    'pekerjaan' => $request->input('pekerjaan', ''),
                    'nama_perusahaan' => $request->input('nama_perusahaan', ''),
                    'penghasilan' => $request->input('penghasilan', ''),
                ];

            case 'rekening':
                return [
                    'nama_bank' => $request->input('nama_bank', ''),
                    'no_rekening' => $request->input('no_rekening', ''),
                    'nama_pemilik_rekening' => $request->input('nama_pemilik_rekening', ''),
                ];

            case 'kontak_darurat':
                return [
                    'nama_lengkap' => $request->input('nama_lengkap_darurat', ''),
                    'hubungan_peminjam' => $request->input('hubungan_peminjam', ''),
                    'no_telepon' => $request->input('no_telepon_darurat', ''),
                    'email' => $request->input('email_darurat', ''),
                    'pekerjaan' => $request->input('pekerjaan_darurat', ''),
                    'no_ktp' => $request->input('no_ktp_darurat', ''),
                    'alamat' => $request->input('alamat_darurat', ''),
                ];

            default:
                return [];
        }
    }

    /**
     * Validasi data baru berdasarkan jenis data
     */
    private function validateDataBaru($data, $jenisData)
    {
        $rules = [];

        switch ($jenisData) {
            case 'data_user':
                $rules = [
                    'nama' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'nomor_hp' => 'required|string|max:20',
                ];
                break;

            case 'data_pribadi':
                $rules = [
                    'nama' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'nomor_hp' => 'required|string|max:20',
                    'no_kk' => 'nullable|string|max:20',
                    'tempat_lahir' => 'nullable|string|max:255',
                    'tanggal_lahir' => 'nullable|date',
                    'jenis_kelamin' => 'nullable|in:L,P',
                    'alamat' => 'nullable|string',
                ];
                break;

            case 'data_ktp':
                $rules = [
                    'nik' => 'required|string|size:16',
                    'nama_lengkap' => 'required|string|max:255',
                    'tempat_lahir' => 'nullable|string|max:255',
                    'tanggal_lahir' => 'nullable|date',
                    'jenis_kelamin' => 'nullable|string|max:50',
                    'alamat' => 'nullable|string',
                ];
                break;

            case 'pekerjaan':
                $rules = [
                    'pekerjaan' => 'required|string|max:255',
                    'nama_perusahaan' => 'nullable|string|max:255',
                    'penghasilan' => 'nullable|string|max:255',
                ];
                break;

            case 'rekening':
                $rules = [
                    'nama_bank' => 'required|string|max:255',
                    'no_rekening' => 'required|string|max:50',
                    'nama_pemilik_rekening' => 'required|string|max:255',
                ];
                break;

            case 'kontak_darurat':
                $rules = [
                    'nama_lengkap' => 'required|string|max:255',
                    'hubungan_peminjam' => 'nullable|string|max:255',
                    'no_telepon' => 'required|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'pekerjaan' => 'nullable|string|max:255',
                    'no_ktp' => 'nullable|string|max:20',
                    'alamat' => 'nullable|string',
                ];
                break;
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }
}
