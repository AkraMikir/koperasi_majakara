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
use Illuminate\Support\Facades\Hash;

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

        // Verify PIN using Hash::check
        $inputPin = str_replace(['.', ','], '', $request->pin);

        if (!Hash::check($inputPin, $user->pin)) {
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

            if ($jenisData === 'kontak_darurat' && isset($dataBaru['no_telepon'])) {
                $dataBaru['no_telepon'] = $this->normalizePhone($dataBaru['no_telepon']);
            }

            // Validasi data baru berdasarkan jenis
            $jenisData = $request->jenis_data;
            $validationResult = $this->validateDataBaru($request, $jenisData);
            if ($validationResult !== true) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors($validationResult)
                    ->with('error', 'Terdapat kesalahan pada data yang diinput.')
                    ->withInput();
            }

            // Upload file setelah validasi berhasil
            if ($jenisData === 'kontak_darurat') {
                if ($request->hasFile('foto_ktp_darurat')) {
                    $path = $request->file('foto_ktp_darurat')->store('user/' . $user->id . '/dataori', 'public');
                    $dataBaru['foto_ktp'] = $path;
                } else {
                    $dataBaru['foto_ktp'] = $nasabah->darurat->foto_ktp ?? '';
                }
                
                // Fallback empty email to '-' for database compatibility
                if (empty($dataBaru['email']) || $dataBaru['email'] === '-') {
                    $dataBaru['email'] = '-';
                }
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
                    'foto' => $nasabah->user->foto ?? '',
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
                    'foto_ktp' => $darurat->foto_ktp ?? '',
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
                $newData = [
                    'nama' => $request->input('nama', ''),
                    'email' => $request->input('email', ''),
                    'nomor_hp' => $request->input('nomor_hp', ''),
                ];
                if ($request->hasFile('foto')) {
                    $path = $request->file('foto')->store('nasabah/foto', 'public');
                    $newData['foto'] = $path;
                }
                return $newData;

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
                $nasabah = Auth::user()->nasabah;
                $email = $request->input('email_darurat', '');
                return [
                    'nama_lengkap' => $request->input('nama_lengkap_darurat', ''),
                    'hubungan_peminjam' => $request->input('hubungan_peminjam', ''),
                    'no_telepon' => $request->input('no_telepon_darurat', ''),
                    'email' => !empty($email) ? trim($email) : null,
                    'pekerjaan' => $request->input('pekerjaan_darurat', ''),
                    'no_ktp' => $request->input('no_ktp_darurat', ''),
                    'alamat' => $request->input('alamat_darurat', ''),
                    'foto_ktp' => $nasabah && $nasabah->darurat ? $nasabah->darurat->foto_ktp : '',
                ];

            default:
                return [];
        }
    }

    /**
     * Validasi data baru berdasarkan jenis data
     */
    private function validateDataBaru($request, $jenisData)
    {
        $rules = [];

        switch ($jenisData) {
            case 'data_user':
                $rules = [
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
                $nasabah = Auth::user()->nasabah;
                $daruratId = $nasabah && $nasabah->darurat ? $nasabah->darurat->id : null;
                $rules = [
                    'nama_lengkap' => 'required|string|min:3|max:255',
                    'hubungan_peminjam' => 'required|string|min:2|max:100',
                    'no_telepon' => [
                        'required',
                        'string',
                        'regex:/^[0-9]+$/',
                        'min:10',
                        'max:12',
                        \Illuminate\Validation\Rule::unique('tbl_darurat', 'no_telepon')->ignore($daruratId),
                    ],
                    'email' => 'nullable|string|email|max:255',
                    'pekerjaan' => 'required|string|min:3|max:100',
                    'no_ktp' => [
                        'required',
                        'string',
                        'digits:16',
                        \Illuminate\Validation\Rule::unique('tbl_darurat', 'no_ktp')->ignore($daruratId),
                    ],
                    'alamat' => 'required|string|min:10',
                ];
                
                if (request()->hasFile('foto_ktp_darurat')) {
                    $data['foto_ktp_darurat'] = request()->file('foto_ktp_darurat');
                    $rules['foto_ktp_darurat'] = 'nullable|image|mimes:jpeg,png,jpg|max:5120';
                }
                break;
        }

        $messages = [];
        if ($jenisData === 'kontak_darurat') {
            $messages = [
                'nama_lengkap.required' => 'Nama lengkap kontak darurat wajib diisi.',
                'nama_lengkap.min' => 'Nama lengkap kontak darurat minimal 3 karakter.',
                'hubungan_peminjam.required' => 'Hubungan wajib diisi.',
                'hubungan_peminjam.min' => 'Hubungan minimal 2 karakter.',
                'hubungan_peminjam.max' => 'Hubungan maksimal 100 karakter.',
                'no_telepon.required' => 'Nomor telepon wajib diisi.',
                'no_telepon.regex' => 'Nomor telepon hanya boleh berisi angka.',
                'no_telepon.min' => 'Nomor telepon minimal 10 digit.',
                'no_telepon.max' => 'Nomor telepon maksimal 12 digit.',
                'no_telepon.unique' => 'Nomor telepon kontak darurat sudah terdaftar.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'pekerjaan.required' => 'Pekerjaan wajib diisi.',
                'pekerjaan.min' => 'Pekerjaan minimal 3 karakter.',
                'pekerjaan.max' => 'Pekerjaan maksimal 100 karakter.',
                'no_ktp.required' => 'NIK (No KTP) wajib diisi.',
                'no_ktp.digits' => 'NIK harus tepat 16 digit angka.',
                'no_ktp.unique' => 'NIK kontak darurat sudah terdaftar.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'foto_ktp_darurat.image' => 'File KTP harus berupa gambar.',
                'foto_ktp_darurat.mimes' => 'Format file KTP harus jpeg, png, atau jpg.',
                'foto_ktp_darurat.max' => 'Ukuran file KTP maksimal 5MB.',
            ];
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    /**
     * Normalize phone numbers (e.g. +62 to 0, strip non-digits)
     */
    private function normalizePhone($value)
    {
        if ($value === null || $value === '') return '';
        $digits = preg_replace('/[^0-9]/', '', $value);
        if (str_starts_with($digits, '62') && strlen($digits) > 10) {
            return '0' . substr($digits, 2);
        }
        return $digits;
    }
}
