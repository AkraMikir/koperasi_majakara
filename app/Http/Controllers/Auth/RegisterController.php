<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NasabahTemp;
use App\Models\PekerjaanTemp;
use App\Models\DataRekTemp;
use App\Models\DataKtpTemp;
use App\Models\DaruratTemp;
use App\Models\Nasabah;
use App\Models\Pekerjaan;
use App\Models\DataRek;
use App\Models\DataKtp;
use App\Models\Darurat;
use App\Services\OcrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    protected $ocrService;
    protected $otpService;

    public function __construct(OcrService $ocrService, \App\Services\OtpService $otpService)
    {
        $this->ocrService = $ocrService;
        $this->otpService = $otpService;
    }

    /**
     * Normalisasi nomor HP: hanya angka, format 08xxx (Indonesia).
     * Contoh: "0812 3456 7890", "628123456789" → "081234567890"
     */
    private function normalizeNomorHp(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        $digits = preg_replace('/[^0-9]/', '', $value);
        if (str_starts_with($digits, '62') && strlen($digits) > 10) {
            return '0' . substr($digits, 2); // 62xxx → 0xxx
        }
        return $digits;
    }

    /**
     * Helper function: Move file from temporary storage to permanent storage
     * Permanent path: public/user/{userId}/dataori/{filename}
     */
    private function moveFileToPermanent($tempPath, $userId, $filename = null)
    {
        if (!$tempPath || $tempPath === 'default-profile.jpg') {
            return $tempPath; // Skip default or empty paths
        }

        // Skip if already in permanent storage
        if (strpos($tempPath, "user/{$userId}/dataori") !== false) {
            return $tempPath;
        }

        // Get filename from temp path if not provided
        if (!$filename) {
            $filename = basename($tempPath);
        }

        // Generate unique filename to avoid conflicts
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $uniqueFilename = $nameWithoutExt . '_' . time() . '.' . $extension;

        // New permanent path
        $permanentPath = "user/{$userId}/dataori/{$uniqueFilename}";

        // Check if file exists in temp storage
        if (Storage::disk('public')->exists($tempPath)) {
            // Ensure directory exists
            $directory = dirname($permanentPath);
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Copy file to permanent location
            Storage::disk('public')->copy($tempPath, $permanentPath);
            
            // Delete temporary file
            Storage::disk('public')->delete($tempPath);
            
            return $permanentPath;
        }

        return $tempPath; // Return original if file doesn't exist
    }

    /**
     * Helper function: Move all registration photos to permanent storage
     */
    private function moveAllPhotosToPermanent($userTempId, $nasabahTempId = null)
    {
        $userTemp = \App\Models\UserTemp::find($userTempId);
        if (!$userTemp) {
            return;
        }

        $userId = $userTemp->id;

        // Move foto profil (UserTemp)
        if ($userTemp->foto && $userTemp->foto !== 'default-profile.jpg') {
            $oldFotoPath = $userTemp->foto;
            $extension = pathinfo($oldFotoPath, PATHINFO_EXTENSION);
            $newFotoPath = $this->moveFileToPermanent($oldFotoPath, $userId, 'foto_profil.' . $extension);
            if ($newFotoPath !== $oldFotoPath) {
                $userTemp->update(['foto' => $newFotoPath]);
            }
        }

        if ($nasabahTempId) {
            $nasabahTemp = NasabahTemp::find($nasabahTempId);
            if ($nasabahTemp) {
                // Move foto KTP (NasabahTemp)
                if ($nasabahTemp->foto_ktp) {
                    $oldFotoKtpPath = $nasabahTemp->foto_ktp;
                    $extension = pathinfo($oldFotoKtpPath, PATHINFO_EXTENSION);
                    $newFotoKtpPath = $this->moveFileToPermanent($oldFotoKtpPath, $userId, 'foto_ktp.' . $extension);
                    if ($newFotoKtpPath !== $oldFotoKtpPath) {
                        $nasabahTemp->update(['foto_ktp' => $newFotoKtpPath]);
                    }
                }

                // Move foto KK (NasabahTemp)
                if ($nasabahTemp->foto_kk) {
                    $oldFotoKkPath = $nasabahTemp->foto_kk;
                    $extension = pathinfo($oldFotoKkPath, PATHINFO_EXTENSION);
                    $newFotoKkPath = $this->moveFileToPermanent($oldFotoKkPath, $userId, 'foto_kk.' . $extension);
                    if ($newFotoKkPath !== $oldFotoKkPath) {
                        $nasabahTemp->update(['foto_kk' => $newFotoKkPath]);
                    }
                }

                // Move foto Selfie (NasabahTemp)
                if ($nasabahTemp->foto_selfie) {
                    $oldFotoSelfiePath = $nasabahTemp->foto_selfie;
                    $extension = pathinfo($oldFotoSelfiePath, PATHINFO_EXTENSION);
                    $newFotoSelfiePath = $this->moveFileToPermanent($oldFotoSelfiePath, $userId, 'foto_selfie.' . $extension);
                    if ($newFotoSelfiePath !== $oldFotoSelfiePath) {
                        $nasabahTemp->update(['foto_selfie' => $newFotoSelfiePath]);
                    }
                }

                // Move file KTP (DataKtpTemp)
                $dataKtpTemp = DataKtpTemp::where('nasabah_id', $nasabahTemp->id)->first();
                if ($dataKtpTemp && $dataKtpTemp->file_ktp) {
                    $oldFileKtpPath = $dataKtpTemp->file_ktp;
                    $extension = pathinfo($oldFileKtpPath, PATHINFO_EXTENSION);
                    $newFileKtpPath = $this->moveFileToPermanent($oldFileKtpPath, $userId, 'file_ktp.' . $extension);
                    if ($newFileKtpPath !== $oldFileKtpPath) {
                        $dataKtpTemp->update(['file_ktp' => $newFileKtpPath]);
                    }
                }

                // Move foto KTP darurat (DaruratTemp)
                $daruratTemp = DaruratTemp::where('id_nasabah', $nasabahTemp->id)->first();
                if ($daruratTemp && $daruratTemp->foto_ktp) {
                    $oldDaruratFotoKtpPath = $daruratTemp->foto_ktp;
                    $extension = pathinfo($oldDaruratFotoKtpPath, PATHINFO_EXTENSION);
                    $newDaruratFotoKtpPath = $this->moveFileToPermanent($oldDaruratFotoKtpPath, $userId, 'darurat_foto_ktp.' . $extension);
                    if ($newDaruratFotoKtpPath !== $oldDaruratFotoKtpPath) {
                        $daruratTemp->update(['foto_ktp' => $newDaruratFotoKtpPath]);
                    }
                }
            }
        }

        // Clean up temporary directories if empty
        $this->cleanupTempDirectories($userId);
    }

    /**
     * Helper function: Clean up temporary directories
     */
    private function cleanupTempDirectories($userId)
    {
        // Clean up registrasi/temp/users_{id} directory
        $tempUserDir = "registrasi/temp/users_{$userId}";
        if (Storage::disk('public')->exists($tempUserDir)) {
            Storage::disk('public')->deleteDirectory($tempUserDir);
        }

        // Clean up registrasi/temp/data_diri directory (only if empty)
        $tempDataDiriDir = "registrasi/temp/data_diri";
        if (Storage::disk('public')->exists($tempDataDiriDir)) {
            $files = Storage::disk('public')->files($tempDataDiriDir);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($tempDataDiriDir);
            }
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $step = $request->get('step', 1);
        $subStep = $request->get('substep', 1); // Sub-step untuk Step 1 (1-6)
        
        // Validate step (1 = Form data dengan sub-step, 2 = OTP, 3 = PIN)
        if (!in_array($step, [1, 2, 3])) {
            $step = 1;
        }

        // Validate sub-step untuk Step 1 (1-6)
        if ($step == 1 && (!in_array($subStep, [1, 2, 3, 4, 5, 6]))) {
            $subStep = 1;
        }

        $banks = [];
        if ($step == 1 && $subStep == 5) {
            $banks = \App\Models\MasterDataBankRegis::where('status', true)->orderBy('nama_bank', 'asc')->get();
        }

        // Step 2 (OTP): GET /register?step=2 harus lewat handleStep2Otp agar nomor HP & session di-set
        if ($step == 2) {
            return $this->handleStep2Otp($request);
        }

        // Step 3 (PIN): redirect ke route yang benar jika user buka langsung
        if ($step == 3) {
            if (!$request->session()->get('register_otp_verified')) {
                return redirect()->route('register', ['step' => 2])
                    ->with('error', 'Silakan verifikasi OTP terlebih dahulu');
            }
            // Tampilkan form PIN (logic bisa dipindah ke sini atau tetap di register())
            // Untuk konsistensi, step 3 tetap di-handle oleh register() saat submit; untuk GET kita hanya perlu view
        }

        // Get session data
        $sessionData = [];
        if ($step == 1) {
            $sessionData = [
                'step1' => $request->session()->get('register_step1'),
                'step2' => $request->session()->get('register_step2'),
                'step3' => $request->session()->get('register_step3'),
                'step4' => $request->session()->get('register_step4'),
                'step5' => $request->session()->get('register_step5'),
                'step6' => $request->session()->get('register_step6'),
            ];
        }

        // Generate session ID if not exists
        if (!$request->session()->has('register_session_id')) {
            $request->session()->put('register_session_id', Str::uuid()->toString());
        }

        // Load data dari database jika sudah ada (untuk back navigation)
        $formData = [];
        if ($step == 1) {
            $userTempId = $request->session()->get('register_user_temp_id');
            $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
            
            if ($userTempId) {
                $userTemp = \App\Models\UserTemp::find($userTempId);
                if ($userTemp) {
                    $formData['nama'] = $userTemp->nama;
                    $formData['email'] = $userTemp->email;
                    $formData['nomor_hp'] = $userTemp->nomor_hp;
                    $formData['foto'] = $userTemp->foto;
                }
            }
            
            if ($nasabahTempId) {
                $nasabahTemp = NasabahTemp::find($nasabahTempId);
                if ($nasabahTemp) {
                    // Filter out temporary no_kk values when loading for display
                    // Don't show temporary value (TEMP...) to user - show empty instead
                    $noKkValue = $nasabahTemp->no_kk;
                    if ($noKkValue && (strpos($noKkValue, 'TEMP') === 0 || strpos($noKkValue, 'TEMP') !== false)) {
                        $formData['no_kk'] = ''; // Don't show temporary value to user
                        \Log::info('Filtered out temporary no_kk value for display', ['temp_value' => $noKkValue]);
                    } else {
                        $formData['no_kk'] = $noKkValue ?? '';
                    }
                    
                    $formData['tempat_lahir'] = $nasabahTemp->tempat_lahir;
                    $formData['tanggal_lahir'] = $nasabahTemp->tanggal_lahir ? $nasabahTemp->tanggal_lahir->format('Y-m-d') : null;
                    $formData['jenis_kelamin'] = $nasabahTemp->jenis_kelamin;
                    $formData['alamat'] = $nasabahTemp->alamat;
                    $formData['alamat_domisili'] = $nasabahTemp->alamat_domisili;
                    $formData['kode_pos'] = $nasabahTemp->kode_pos;
                    $formData['foto_ktp'] = $nasabahTemp->foto_ktp;
                    $formData['foto_kk'] = $nasabahTemp->foto_kk;
                    $formData['foto_selfie'] = $nasabahTemp->foto_selfie;
                    
                    // Load pekerjaan
                    $pekerjaanTemp = PekerjaanTemp::where('nasabah_id', $nasabahTemp->id)->first();
                    if ($pekerjaanTemp) {
                        $formData['pekerjaan'] = $pekerjaanTemp->pekerjaan;
                        $formData['penghasilan'] = $pekerjaanTemp->penghasilan;
                        $formData['nama_perusahaan'] = $pekerjaanTemp->nama_perusahaan;
                        $formData['nama_bank'] = $pekerjaanTemp->nama_bank;
                    }
                    
                    // Load rekening
                    $dataRekTemp = DataRekTemp::where('nasabah_id', $nasabahTemp->id)->first();
                    if ($dataRekTemp) {
                        $formData['no_rekening'] = $dataRekTemp->no_rekening;
                        $formData['nama_pemilik_rekening'] = $dataRekTemp->nama_pemilik_rekening;
                        $formData['jenis_atm'] = $dataRekTemp->jenis_atm;
                    }
                    
                    // Load KTP
                    $dataKtpTemp = DataKtpTemp::where('nasabah_id', $nasabahTemp->id)->first();
                    if ($dataKtpTemp) {
                        $formData['nik'] = $dataKtpTemp->nik;
                        $formData['nama_lengkap_ktp'] = $dataKtpTemp->nama_lengkap;
                        $formData['tempat_lahir_ktp'] = $dataKtpTemp->tempat_lahir;
                        $formData['tanggal_lahir_ktp'] = $dataKtpTemp->tanggal_lahir ? $dataKtpTemp->tanggal_lahir->format('Y-m-d') : null;
                        // Parse alamat jika format sesuai (RT/RW: xxx, Kel/Desa: xxx, Kecamatan: xxx)
                        $alamat = $dataKtpTemp->alamat;
                        if (preg_match('/RT\/RW:\s*([^,]+)/i', $alamat, $rtRwMatch)) {
                            $formData['rt_rw'] = trim($rtRwMatch[1]);
                        }
                        if (preg_match('/Kel\/Desa:\s*([^,]+)/i', $alamat, $kelDesaMatch)) {
                            $formData['kel_desa'] = trim($kelDesaMatch[1]);
                        }
                        if (preg_match('/Kecamatan:\s*([^,]+)/i', $alamat, $kecamatanMatch)) {
                            $formData['kecamatan'] = trim($kecamatanMatch[1]);
                        }
                        // Fallback: jika tidak bisa di-parse, simpan sebagai alamat_ktp
                        if (empty($formData['rt_rw']) && empty($formData['kel_desa']) && empty($formData['kecamatan'])) {
                            $formData['alamat_ktp'] = $alamat;
                        }
                        $formData['jenis_kelamin_ktp'] = $dataKtpTemp->jenis_kelamin;
                        $formData['file_ktp'] = $dataKtpTemp->file_ktp;
                    }
                    
                    // Load darurat
                    $daruratTemp = DaruratTemp::where('id_nasabah', $nasabahTemp->id)->first();
                    if ($daruratTemp) {
                        $formData['darurat_nama_lengkap'] = $daruratTemp->nama_lengkap;
                        $formData['hubungan_peminjam'] = $daruratTemp->hubungan_peminjam;
                        $formData['darurat_no_telepon'] = $daruratTemp->no_telepon;
                        $formData['darurat_alamat'] = $daruratTemp->alamat;
                        $formData['darurat_pekerjaan'] = $daruratTemp->pekerjaan;
                        $formData['darurat_email'] = $daruratTemp->email;
                        $formData['darurat_no_ktp'] = $daruratTemp->no_ktp;
                        $formData['darurat_foto_ktp'] = $daruratTemp->foto_ktp;
                    }
                }
            }
        }

        return view('auth.register', [
            'step' => $step,
            'subStep' => $subStep,
            'sessionData' => $sessionData,
            'sessionId' => $request->session()->get('register_session_id'),
            'formData' => $formData,
            'banks' => $banks,
        ]);
    }

    /**
     * Handle OCR request for KTP.
     */
    public function processOcr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File KTP tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload file - storage path: registrasi/temp/ocr/file_ktp (temporary untuk OCR)
        $fileKtp = $request->file('file_ktp');
        $fileKtpPath = $fileKtp->store('registrasi/temp/ocr/file_ktp', 'public');

        // Process OCR
        $ocrResult = $this->ocrService->extractKtpData($fileKtpPath);

        if ($ocrResult['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Data KTP berhasil diekstrak',
                'data' => $ocrResult['data'],
                'raw_text' => $ocrResult['raw_text'],
                'file_path' => $fileKtpPath
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengekstrak data KTP: ' . ($ocrResult['error'] ?? 'Unknown error'),
            'file_path' => $fileKtpPath // Tetap return file path untuk digunakan manual
        ], 500);
    }

    /**
     * Check if email or nomor_hp is unique (AJAX).
     */
    public function checkUnique(Request $request)
    {
        $type = $request->input('type');
        $value = $request->input('value');
        $userTempId = $request->session()->get('register_user_temp_id');
        $nasabahTempId = $request->session()->get('register_nasabah_temp_id');

        if ($type === 'email') {
            $existsInUsers = \App\Models\User::where('email', $value)->exists();
            $existsInTemp = \App\Models\UserTemp::where('email', $value)
                ->where('id', '!=', $userTempId)
                ->exists();

            if ($existsInUsers || $existsInTemp) {
                return response()->json([
                    'unique' => false,
                    'message' => 'Email sudah terdaftar. Silakan gunakan email lain.'
                ]);
            }
        } elseif ($type === 'nomor_hp') {
            $normalized = $this->normalizeNomorHp($value);
            if (empty($normalized)) {
                $normalized = $value;
            }
            $existsInUsers = \App\Models\User::where('nomor_hp', $normalized)->exists();
            $existsInTemp = \App\Models\UserTemp::where('nomor_hp', $normalized)
                ->where('id', '!=', $userTempId)
                ->exists();

            if ($existsInUsers || $existsInTemp) {
                return response()->json([
                    'unique' => false,
                    'message' => 'Nomor HP sudah terdaftar. Silakan gunakan nomor lain.'
                ]);
            }
        } elseif ($type === 'no_kk') {
            $existsInPermanent = \App\Models\Nasabah::where('no_kk', $value)->exists();
            $existsInTemp = \App\Models\NasabahTemp::where('no_kk', $value)
                ->where('id', '!=', $nasabahTempId)
                ->exists();

            if ($existsInPermanent || $existsInTemp) {
                return response()->json([
                    'unique' => false,
                    'message' => 'Nomor KK sudah terdaftar. Silakan gunakan nomor KK lain.'
                ]);
            }
        } elseif ($type === 'nik') {
            $dataKtpTempId = null;
            if ($nasabahTempId) {
                $tempKtp = \App\Models\DataKtpTemp::where('nasabah_id', $nasabahTempId)->first();
                if ($tempKtp) {
                    $dataKtpTempId = $tempKtp->id;
                }
            }

            $existsInPermanent = \App\Models\DataKtp::where('nik', $value)->exists();
            $existsInTemp = \App\Models\DataKtpTemp::where('nik', $value)
                ->when($dataKtpTempId, function ($query) use ($dataKtpTempId) {
                    return $query->where('id', '!=', $dataKtpTempId);
                })
                ->exists();

            if ($existsInPermanent || $existsInTemp) {
                return response()->json([
                    'unique' => false,
                    'message' => 'NIK sudah terdaftar. Silakan gunakan NIK lain.'
                ]);
            }
        }

        return response()->json([
            'unique' => true
        ]);
    }

    /**
     * Handle registration request - Step 1: Save all form data to temp.
     */
    public function register(Request $request)
    {
        $step = (int) $request->input('step', 1);
        $subStep = (int) $request->input('substep', 1);
        
        // Debug: log setiap POST ke register (untuk cek apakah tombol Kirim OTP sampai ke sini)
        if ($request->isMethod('post')) {
            \Log::info('Register POST received', [
                'step' => $step,
                'substep' => $subStep,
                'has_send_otp' => $request->has('send_otp'),
                'send_otp_value' => $request->input('send_otp'),
            ]);
        }
        
        // Validate step
        if (!in_array($step, [1, 2, 3])) {
            return redirect()->route('register')->with('error', 'Invalid step');
        }

        // Step 1: Save form data to temp tables (dengan sub-step)
        if ($step == 1) {
            return $this->handleStep1($request, $subStep);
        }

        // Step 2: Verify OTP
        if ($step == 2) {
            return $this->handleStep2Otp($request);
        }

        // Step 3: Create PIN and finalize (move from temp to original)
        if ($step == 3) {
            return $this->handleStep3Pin($request);
        }

        return redirect()->route('register');
    }

    /**
     * Handle Step 1: Save form data to temp tables berdasarkan sub-step.
     */
    private function handleStep1(Request $request, $subStep = 1)
    {
        \Log::info('handleStep1 called', [
            'substep' => $subStep,
            'user_temp_id' => $request->session()->get('register_user_temp_id'),
            'nasabah_temp_id' => $request->session()->get('register_nasabah_temp_id'),
        ]);
        
        // Validasi berdasarkan sub-step
        $validationRules = [];
        
        // Sub-step 1: Data Diri
        if ($subStep == 1) {
            // Get user_temp_id dari session untuk exclude dari unique validation
            $userTempId = $request->session()->get('register_user_temp_id');
            $userTemp = $userTempId ? \App\Models\UserTemp::find($userTempId) : null;
            $hasFoto = $userTemp && $userTemp->foto && $userTemp->foto !== 'default-profile.jpg';
            
            $validationRules = [
                'nama' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    \Illuminate\Validation\Rule::unique('users', 'email'),
                    \Illuminate\Validation\Rule::unique('users_temp', 'email')->ignore($userTempId),
                ],
                'nomor_hp' => 'required|string|max:20',
                'password' => $userTemp ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
                'foto' => $hasFoto ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            ];
        }
        // Sub-step 2: Data KTP (Moved from Substep 5)
        elseif ($subStep == 2) {
            $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
            $dataKtpTemp = $nasabahTempId ? \App\Models\DataKtpTemp::where('nasabah_id', $nasabahTempId)->first() : null;
            $hasFileKtp = $dataKtpTemp && $dataKtpTemp->file_ktp;

            $validationRules = [
                'nik' => [
                    'required',
                    'string',
                    'digits:16',
                    \Illuminate\Validation\Rule::unique('tbl_data_ktp', 'nik'),
                    \Illuminate\Validation\Rule::unique('tbl_data_ktp_temp', 'nik')->when($dataKtpTemp, function ($query) use ($dataKtpTemp) {
                        return $query->ignore($dataKtpTemp->id);
                    }),
                ],
                'nama_lengkap_ktp' => 'required|string|max:100',
                'tempat_lahir_ktp' => 'required|string|max:100',
                'tanggal_lahir_ktp' => 'required|date',
                'rt_rw' => 'required|string|max:50',
                'kel_desa' => 'required|string|max:100',
                'kecamatan' => 'required|string|max:100',
                'alamat_ktp' => 'nullable|string',
                'jenis_kelamin_ktp' => 'required|in:Laki-laki,Perempuan',
                'file_ktp' => $hasFileKtp ? 'nullable|string' : 'required_without:file_ktp_upload|nullable|string',
                'file_ktp_upload' => $hasFileKtp ? 'nullable|image|mimes:jpeg,png,jpg|max:5120' : 'required_without:file_ktp|nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];
        }
        // Sub-step 3: Detail Nasabah (Moved from Substep 2, with foto_selfie)
        elseif ($subStep == 3) {
            // Get nasabah_temp_id dari session untuk exclude dari unique validation
            $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
            $nasabahTemp = $nasabahTempId ? \App\Models\NasabahTemp::find($nasabahTempId) : null;
            $hasFotoKtp = $nasabahTemp && $nasabahTemp->foto_ktp;
            $hasFotoKk = $nasabahTemp && $nasabahTemp->foto_kk;
            $hasFotoSelfie = $nasabahTemp && $nasabahTemp->foto_selfie;
            
            $validationRules = [
                'no_kk' => [
                    'required',
                    'string',
                    'max:16',
                    \Illuminate\Validation\Rule::unique('tbl_nasabah', 'no_kk'),
                    \Illuminate\Validation\Rule::unique('tbl_nasabah_temp', 'no_kk')->ignore($nasabahTempId),
                ],
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'required|string',
                'alamat_domisili' => 'required|string',
                'kode_pos' => 'required|digits:5',
                'foto_ktp' => $hasFotoKtp ? 'nullable|string' : 'required_without:foto_ktp_upload|nullable|string',
                'foto_ktp_upload' => $hasFotoKtp ? 'nullable|image|mimes:jpeg,png,jpg|max:5120' : 'required_without:foto_ktp|nullable|image|mimes:jpeg,png,jpg|max:5120',
                'foto_kk' => $hasFotoKk ? 'nullable|string' : 'required_without:foto_kk_upload|nullable|string',
                'foto_kk_upload' => $hasFotoKk ? 'nullable|image|mimes:jpeg,png,jpg|max:5120' : 'required_without:foto_kk|nullable|image|mimes:jpeg,png,jpg|max:5120',
                'foto_selfie' => $hasFotoSelfie ? 'nullable|string' : 'required_without:foto_selfie_upload|nullable|string',
                'foto_selfie_upload' => $hasFotoSelfie ? 'nullable|image|mimes:jpeg,png,jpg|max:5120' : 'required_without:foto_selfie|nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];
        }
        // Sub-step 4: Pekerjaan (Moved from Substep 3)
        elseif ($subStep == 4) {
            $validationRules = [
                'pekerjaan' => 'required|string|max:255',
                'penghasilan' => 'required|string|max:100', // String untuk range (mis. Rp1.000.000 – Rp2.500.000)
                'nama_perusahaan' => 'required|string|max:255',
                'nama_bank' => 'nullable|string|max:255',
            ];
        }
        // Sub-step 5: Rekening (Moved from Substep 4)
        elseif ($subStep == 5) {
            $validationRules = [
                'no_rekening' => 'required|regex:/^[0-9]+$/|max:16',
                'nama_pemilik_rekening' => 'required|string|max:255',
                'jenis_atm' => 'required|string|max:255',
            ];
        }
        // Sub-step 6: Kontak Darurat
        elseif ($subStep == 6) {
            $validationRules = [
                'darurat_nama_lengkap' => 'nullable|string|max:255',
                'hubungan_peminjam' => 'nullable|string|max:100',
                'darurat_no_telepon' => 'nullable|string|max:20',
                'darurat_alamat' => 'nullable|string',
                'darurat_pekerjaan' => 'nullable|string|max:100',
                'darurat_email' => 'nullable|string|email|max:255',
                'darurat_no_ktp' => 'nullable|string|max:16',
                'darurat_foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];
        }

        // Log request data for debugging
        \Log::info('Validation rules for substep ' . $subStep, [
            'rules' => $validationRules,
            'request_data_keys' => array_keys($request->except(['password', 'password_confirmation', '_token'])),
        ]);

        // Custom validation messages
        $customMessages = [];
        if ($subStep == 2) {
            $customMessages = [
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus tepat 16 digit angka.',
                'nik.unique' => 'NIK sudah terdaftar di sistem.',
            ];
        } elseif ($subStep == 4 || $subStep == 5) {
            $customMessages = [
                'no_rekening.regex' => 'Nomor rekening hanya boleh berisi angka.',
            ];
        }
        
        $validator = Validator::make($request->all(), $validationRules, $customMessages);

        if ($validator->fails()) {
            \Log::warning('Registration validation failed at Step 1, SubStep ' . $subStep, [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->except(['password', 'password_confirmation', '_token']),
            ]);
            
            return redirect()->route('register', ['step' => 1, 'substep' => $subStep])
                ->withErrors($validator)
                ->with('error', 'Terdapat kesalahan pada data yang Anda masukkan. Silakan periksa kembali.')
                ->withInput();
        }
        
        \Log::info('Validation passed for substep ' . $subStep);

        try {
            DB::beginTransaction();

            // Get or create session ID
            $sessionId = $request->session()->get('register_session_id');
            if (!$sessionId) {
                $sessionId = Str::uuid()->toString();
                $request->session()->put('register_session_id', $sessionId);
            }

            // Get or create UserTemp (harus ada dari sub-step 1)
            $userTempId = $request->session()->get('register_user_temp_id');
            $userTemp = $userTempId ? \App\Models\UserTemp::find($userTempId) : null;

            // Sub-step 1: Create UserTemp
            if ($subStep == 1) {
                if (!$userTemp) {
                    // Handle foto upload - storage path: registrasi/temp/data_diri/foto_profil
                    $fotoPath = 'default-profile.jpg';
                    if ($request->hasFile('foto')) {
                        $foto = $request->file('foto');
                        $fotoPath = $foto->store('registrasi/temp/data_diri/foto_profil', 'public');
                    }

                    $nomorHp = $this->normalizeNomorHp($request->nomor_hp);
                    $userTemp = \App\Models\UserTemp::create([
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'nomor_hp' => $nomorHp ?: $request->nomor_hp,
                        'foto' => $fotoPath,
                    ]);
                } else {
                    // Update existing - handle foto update (nomor_hp dinormalisasi)
                    $nomorHp = $this->normalizeNomorHp($request->nomor_hp ?? $userTemp->nomor_hp);
                    $updateData = [
                        'nama' => $request->nama ?? $userTemp->nama,
                        'nomor_hp' => $nomorHp ?: ($request->nomor_hp ?? $userTemp->nomor_hp),
                        'password' => $request->password ? Hash::make($request->password) : $userTemp->password,
                    ];
                    
                    // Update foto if new file uploaded
                    if ($request->hasFile('foto')) {
                        // Delete old foto if exists and not default
                        if ($userTemp->foto && $userTemp->foto !== 'default-profile.jpg' && Storage::disk('public')->exists($userTemp->foto)) {
                            Storage::disk('public')->delete($userTemp->foto);
                        }
                        
                        $foto = $request->file('foto');
                        $updateData['foto'] = $foto->store('registrasi/temp/data_diri/foto_profil', 'public');
                    }
                    
                    $userTemp->update($updateData);
                }
                $request->session()->put('register_user_temp_id', $userTemp->id);
            }

            // Pastikan userTemp ada untuk sub-step selanjutnya
            if (!$userTemp && $subStep > 1) {
                throw new \Exception('Silakan lengkapi data diri terlebih dahulu');
            }

            // Sub-step 2–6: pastikan data Data Diri (nomor_hp, nama, email) dari hidden input tetap tersimpan
            if ($userTemp && $subStep > 1) {
                $refresh = [];
                if ($request->filled('nomor_hp')) {
                    $n = $this->normalizeNomorHp($request->nomor_hp);
                    if ($n !== '') {
                        $refresh['nomor_hp'] = $n;
                    }
                }
                if ($request->filled('nama')) {
                    $refresh['nama'] = $request->nama;
                }
                if ($request->filled('email')) {
                    $refresh['email'] = $request->email;
                }
                if (!empty($refresh)) {
                    $userTemp->update($refresh);
                }
            }

            // Sub-step 2: Create/Update DataKtpTemp & Ensure NasabahTemp exists (Moved from Substep 5)
            if ($subStep == 2) {
                // Ensure NasabahTemp exists first
                $nasabahTemp = NasabahTemp::where('user_id', $userTemp->id)->first();
                if (!$nasabahTemp) {
                    $nasabahTemp = NasabahTemp::create([
                        'user_id' => $userTemp->id,
                        'no_kk' => null,
                        'tempat_lahir' => null,
                        'tanggal_lahir' => null,
                        'jenis_kelamin' => 'L',
                        'alamat' => '',
                    ]);
                }
                $request->session()->put('register_nasabah_temp_id', $nasabahTemp->id);

                $dataKtpTemp = DataKtpTemp::where('nasabah_id', $nasabahTemp->id)->first();
                
                // Gabungkan alamat dari RT/RW, Kel/Desa, Kecamatan menjadi satu alamat lengkap
                $alamatParts = [];
                if ($request->rt_rw) {
                    $alamatParts[] = 'RT/RW: ' . $request->rt_rw;
                }
                if ($request->kel_desa) {
                    $alamatParts[] = 'Kel/Desa: ' . $request->kel_desa;
                }
                if ($request->kecamatan) {
                    $alamatParts[] = 'Kecamatan: ' . $request->kecamatan;
                }
                $alamatLengkap = !empty($alamatParts) ? implode(', ', $alamatParts) : ($request->alamat_ktp ?? '');
                
                $ktpData = [
                    'nasabah_id' => $nasabahTemp->id,
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap_ktp,
                    'tempat_lahir' => $request->tempat_lahir_ktp,
                    'tanggal_lahir' => $request->tanggal_lahir_ktp,
                    'alamat' => $alamatLengkap,
                    'jenis_kelamin' => $request->jenis_kelamin_ktp,
                    'file_ktp' => $request->file_ktp,
                ];

                // Handle file KTP upload - storage path: registrasi/temp/users_{id}/file_ktp
                $userId = $userTemp->id;
                if ($request->hasFile('file_ktp_upload') && !$request->file_ktp) {
                    // Delete old file_ktp if exists
                    if ($dataKtpTemp && $dataKtpTemp->file_ktp && Storage::disk('public')->exists($dataKtpTemp->file_ktp)) {
                        Storage::disk('public')->delete($dataKtpTemp->file_ktp);
                    }
                    
                    $fileKtp = $request->file('file_ktp_upload');
                    $ktpData['file_ktp'] = $fileKtp->store("registrasi/temp/users_{$userId}/file_ktp", 'public');
                }

                if ($dataKtpTemp) {
                    $dataKtpTemp->update(array_filter($ktpData));
                } else {
                    $dataKtpTemp = DataKtpTemp::create($ktpData);
                }

                // UX Enhancement: Auto pre-populate NasabahTemp's foto_ktp with this file path
                if ($dataKtpTemp && $dataKtpTemp->file_ktp) {
                    $nasabahTemp->update(['foto_ktp' => $dataKtpTemp->file_ktp]);
                }
            }

            // Sub-step 3: Create/Update NasabahTemp (Moved from Substep 2, with foto_selfie)
            if ($subStep == 3) {
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;
                
                if (!$nasabahTemp) {
                    // Fallback in case they skipped step 2
                    $nasabahTemp = NasabahTemp::create([
                        'user_id' => $userTemp->id,
                        'no_kk' => null,
                        'tempat_lahir' => null,
                        'tanggal_lahir' => null,
                        'jenis_kelamin' => 'L',
                        'alamat' => '',
                        'alamat_domisili' => '',
                        'kode_pos' => null,
                    ]);
                    $request->session()->put('register_nasabah_temp_id', $nasabahTemp->id);
                }
                
                $nasabahData = [
                    'no_kk' => $request->no_kk,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'alamat_domisili' => $request->alamat_domisili,
                    'kode_pos' => $request->kode_pos,
                ];

                $userId = $userTemp->id;

                // Handle foto KTP upload or camera capture in Detail Nasabah
                if ($request->hasFile('foto_ktp_upload')) {
                    if ($nasabahTemp->foto_ktp && Storage::disk('public')->exists($nasabahTemp->foto_ktp)) {
                        Storage::disk('public')->delete($nasabahTemp->foto_ktp);
                    }
                    $fotoKtp = $request->file('foto_ktp_upload');
                    $nasabahData['foto_ktp'] = $fotoKtp->store("registrasi/temp/users_{$userId}/foto_ktp", 'public');
                } elseif ($request->filled('foto_ktp') && $request->foto_ktp !== $nasabahTemp->foto_ktp) {
                    // If camera captured path is supplied
                    $nasabahData['foto_ktp'] = $request->foto_ktp;
                }

                // Handle foto KK upload or camera capture
                if ($request->hasFile('foto_kk_upload')) {
                    if ($nasabahTemp->foto_kk && Storage::disk('public')->exists($nasabahTemp->foto_kk)) {
                        Storage::disk('public')->delete($nasabahTemp->foto_kk);
                    }
                    $fotoKk = $request->file('foto_kk_upload');
                    $nasabahData['foto_kk'] = $fotoKk->store("registrasi/temp/users_{$userId}/foto_kk", 'public');
                } elseif ($request->filled('foto_kk') && $request->foto_kk !== $nasabahTemp->foto_kk) {
                    $nasabahData['foto_kk'] = $request->foto_kk;
                }

                // Handle foto Selfie upload or camera capture
                if ($request->hasFile('foto_selfie_upload')) {
                    if ($nasabahTemp->foto_selfie && Storage::disk('public')->exists($nasabahTemp->foto_selfie)) {
                        Storage::disk('public')->delete($nasabahTemp->foto_selfie);
                    }
                    $fotoSelfie = $request->file('foto_selfie_upload');
                    $nasabahData['foto_selfie'] = $fotoSelfie->store("registrasi/temp/users_{$userId}/foto_selfie", 'public');
                } elseif ($request->filled('foto_selfie') && $request->foto_selfie !== $nasabahTemp->foto_selfie) {
                    $nasabahData['foto_selfie'] = $request->foto_selfie;
                }

                // Update NasabahTemp
                $nasabahTemp->update($nasabahData);
            }

            // Sub-step 4: Create/Update PekerjaanTemp (Moved from Substep 3)
            if ($subStep == 4) {
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;
                
                if ($nasabahTemp) {
                    $pekerjaanTemp = PekerjaanTemp::where('nasabah_id', $nasabahTemp->id)->first();
                    
                    $pekerjaanData = [
                        'nasabah_id' => $nasabahTemp->id,
                        'pekerjaan' => $request->pekerjaan,
                        'penghasilan' => $request->penghasilan,
                        'nama_perusahaan' => $request->nama_perusahaan,
                        'nama_bank' => $request->nama_bank,
                    ];

                    if ($pekerjaanTemp) {
                        $pekerjaanTemp->update($pekerjaanData);
                    } else {
                        PekerjaanTemp::create($pekerjaanData);
                    }
                }
            }

            // Sub-step 5: Create/Update DataRekTemp (Moved from Substep 4)
            if ($subStep == 5) {
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;
                
                if ($nasabahTemp) {
                    $dataRekTemp = DataRekTemp::where('nasabah_id', $nasabahTemp->id)->first();
                    
                    $rekData = [
                        'nasabah_id' => $nasabahTemp->id,
                        'no_rekening' => $request->no_rekening,
                        'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
                        'jenis_atm' => $request->jenis_atm,
                    ];

                    if ($dataRekTemp) {
                        $dataRekTemp->update($rekData);
                    } else {
                        DataRekTemp::create($rekData);
                    }
                }
            }

            // Sub-step 6: Create/Update DaruratTemp
            if ($subStep == 6) {
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;
                
                if ($nasabahTemp) {
                    $daruratTemp = DaruratTemp::where('id_nasabah', $nasabahTemp->id)->first();
                    
                    $daruratData = [
                        'id_nasabah' => $nasabahTemp->id,
                        'nama_lengkap' => $request->darurat_nama_lengkap,
                        'hubungan_peminjam' => $request->hubungan_peminjam,
                        'no_telepon' => $request->darurat_no_telepon,
                        'alamat' => $request->darurat_alamat,
                        'pekerjaan' => $request->darurat_pekerjaan,
                        'email' => $request->darurat_email,
                        'no_ktp' => $request->darurat_no_ktp,
                    ];

                    // Handle foto KTP darurat upload - storage path: registrasi/temp/users_{id}/darurat_foto_ktp
                    $userId = $userTemp->id;
                    if ($request->hasFile('darurat_foto_ktp')) {
                        // Delete old darurat_foto_ktp if exists
                        if ($daruratTemp && $daruratTemp->foto_ktp && Storage::disk('public')->exists($daruratTemp->foto_ktp)) {
                            Storage::disk('public')->delete($daruratTemp->foto_ktp);
                        }
                        
                        $daruratFotoKtp = $request->file('darurat_foto_ktp');
                        $daruratData['foto_ktp'] = $daruratFotoKtp->store("registrasi/temp/users_{$userId}/darurat_foto_ktp", 'public');
                    }

                    if ($daruratTemp) {
                        $daruratTemp->update(array_filter($daruratData));
                    } else {
                        DaruratTemp::create($daruratData);
                    }
                }
            }

            DB::commit();

            // Log success for debugging
            \Log::info('Registration Step 1, SubStep ' . $subStep . ' completed successfully', [
                'user_temp_id' => $request->session()->get('register_user_temp_id'),
                'nasabah_temp_id' => $request->session()->get('register_nasabah_temp_id'),
            ]);

            // Redirect berdasarkan sub-step
            if ($subStep < 6) {
                // Lanjut ke sub-step berikutnya
                $nextSubStep = $subStep + 1;
                \Log::info('Redirecting to Step 1, SubStep ' . $nextSubStep, [
                    'current_substep' => $subStep,
                    'next_substep' => $nextSubStep,
                    'url' => route('register', ['step' => 1, 'substep' => $nextSubStep]),
                ]);
                
                // Use full URL redirect to ensure it works
                $redirectUrl = route('register', ['step' => 1, 'substep' => $nextSubStep]);
                return redirect($redirectUrl)
                    ->with('success', 'Data berhasil disimpan. Lanjutkan ke langkah berikutnya.');
            } else {
                // Sub-step 6 selesai - Move all photos to permanent storage before redirect
                $userTempId = $request->session()->get('register_user_temp_id');
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                
                if ($userTempId) {
                    try {
                        $this->moveAllPhotosToPermanent($userTempId, $nasabahTempId);
                    } catch (\Exception $e) {
                        // Log error but don't block registration
                        \Log::error('Error moving photos to permanent storage: ' . $e->getMessage());
                    }
                }
                
                // Redirect ke Step 2 (OTP)
                return redirect()->route('register', ['step' => 2])
                    ->with('success', 'Data berhasil disimpan. Silakan verifikasi nomor HP Anda.');
            }
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            \Log::error('Registration Database Error at Step 1, SubStep ' . $subStep . ': ' . $e->getMessage(), [
                'code' => $e->getCode(),
                'sql_state' => $e->errorInfo[0] ?? null,
                'message' => $e->getMessage()
            ]);

            $errorCode = $e->errorInfo[1] ?? null;
            $errorMessage = 'Terjadi kesalahan database.';
            
            if ($errorCode == 1062 || ($e->errorInfo[0] ?? null) === '23000') {
                if (strpos($e->getMessage(), 'email') !== false) {
                    $errorMessage = 'Email sudah terdaftar. Silakan gunakan email lain.';
                } elseif (strpos($e->getMessage(), 'nomor_hp') !== false) {
                    $errorMessage = 'Nomor HP sudah terdaftar. Silakan gunakan nomor lain.';
                } elseif (strpos($e->getMessage(), 'no_kk') !== false) {
                    $errorMessage = 'Nomor KK sudah terdaftar. Silakan periksa kembali data Anda.';
                } elseif (strpos($e->getMessage(), 'nik') !== false) {
                    $errorMessage = 'NIK sudah terdaftar. Silakan periksa kembali data Anda.';
                } else {
                    $errorMessage = 'Terdapat data duplikat yang sudah terdaftar di sistem.';
                }
            }
            
            return redirect()->route('register', ['step' => 1, 'substep' => $subStep])
                ->with('error', $errorMessage)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error for debugging
            \Log::error('Registration Error at Step 1, SubStep ' . $subStep . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);
            
            return redirect()->route('register', ['step' => 1, 'substep' => $subStep])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle Step 2: Verify OTP.
     * 
     * Alur:
     * 1. First landing → Tampilkan konfirmasi nomor WA + button "Kirim OTP"
     * 2. User klik "Kirim OTP" → Generate & send OTP
     * 3. User input OTP → Verify
     */
    private function handleStep2Otp(Request $request)
    {
        // Check if step 1 data exists
        $userTempId = $request->session()->get('register_user_temp_id');
        \Log::info('Register Step 2 OTP: session register_user_temp_id = ' . ($userTempId ?? 'null'));

        if (!$userTempId) {
            return redirect()->route('register', ['step' => 1, 'substep' => 1])
                ->with('error', 'Silakan lengkapi data diri terlebih dahulu');
        }

        $userTemp = \App\Models\UserTemp::find($userTempId);
        $nomorHp = $userTemp ? trim((string) $userTemp->nomor_hp) : '';

        \Log::info('Register Step 2 OTP: loaded UserTemp', [
            'user_temp_id' => $userTempId,
            'found' => (bool) $userTemp,
            'email' => $userTemp->email ?? null,
            'nomor_hp_raw' => $userTemp->nomor_hp ?? null,
            'nomor_hp_trimmed' => $nomorHp,
        ]);

        // Fallback: jika record dari session tidak punya nomor HP, cari record lain dengan email sama yang punya nomor (data mungkin ada di record lain)
        if ($userTemp && $nomorHp === '' && $userTemp->email) {
            $other = \App\Models\UserTemp::where('email', $userTemp->email)
                ->whereNotNull('nomor_hp')
                ->where('nomor_hp', '!=', '')
                ->orderByDesc('updated_at')
                ->first();
            if ($other && trim((string) $other->nomor_hp) !== '') {
                \Log::info('Register Step 2 OTP: fallback ke UserTemp lain dengan nomor_hp', [
                    'fallback_id' => $other->id,
                    'nomor_hp' => $other->nomor_hp,
                ]);
                // Salin nomor ke record yang dipakai session agar konsisten
                $userTemp->update(['nomor_hp' => $other->nomor_hp]);
                $nomorHp = trim((string) $other->nomor_hp);
            }
        }

        if (!$userTemp || $nomorHp === '') {
            \Log::warning('Register Step 2 OTP: redirect ke step 1 karena nomor_hp kosong', [
                'user_temp_id' => $userTempId,
            ]);
            return redirect()->route('register', ['step' => 1, 'substep' => 1])
                ->with('error', 'Nomor HP belum diisi. Silakan isi nomor HP Anda di Langkah 1 (Data Diri).');
        }

        // Gunakan nomor yang dinormalisasi untuk tampilan dan OTP (konsisten dengan format yang disimpan)
        $nomorHpDisplay = $this->normalizeNomorHp($nomorHp) ?: $nomorHp;

        // Simpan nomor yang sudah dinormalisasi ke DB agar data lama (dengan spasi/format salah) ikut terperbaiki
        if ($nomorHpDisplay !== $nomorHp) {
            $userTemp->update(['nomor_hp' => $nomorHpDisplay]);
        }

        $sessionId = $request->session()->get('register_session_id');
        
        // Store phone in session for display
        $request->session()->put('register_phone', $nomorHpDisplay);

        // === CASE 1: User click "Kirim OTP" atau "Kirim Ulang" — CEK INI DULU agar tidak kena validasi "OTP harus diisi" ===
        if ($request->has('send_otp') && $request->send_otp == '1' && $request->isMethod('post')) {
            \Log::info('User requesting to send OTP', [
                'user_temp_id' => $userTempId,
                'phone' => $nomorHpDisplay,
                'is_resend' => $request->session()->has('otp_sent_at'),
            ]);

            // Cek apakah ini resend (OTP sudah pernah dikirim)
            $isResend = $request->session()->has('otp_sent_at');

            if ($isResend) {
                // Resend OTP - invalidate old OTP first
                $otpResult = $this->otpService->resend(
                    $nomorHpDisplay,
                    $sessionId,
                    null, // user_id null karena masih temp
                    'registration'
                );
            } else {
                // First time send OTP
                $otpResult = $this->otpService->generateAndSend(
                    $nomorHpDisplay,
                    $sessionId,
                    null, // user_id null karena masih temp
                    'registration'
                );
            }

            \Log::info('Register: OTP send result', [
                'success' => $otpResult['success'] ?? false,
                'message' => $otpResult['message'] ?? '',
            ]);

            if (!empty($otpResult['success'])) {
                // Mark that OTP has been sent - Use PERSISTENT session data
                $request->session()->put('otp_sent_at', now()->toDateTimeString());
                $request->session()->put('otp_session_id', $sessionId); 
                // Store expiration (sesuai config: 1 menit)
                $expiryMinutes = (int) config('services.otp.expiry_minutes', 1);
                $request->session()->put('otp_expires_at', now()->addMinutes($expiryMinutes)->toDateTimeString());
                // Hapus error & errors lama agar tidak tampil "tidak valid atau sudah digunakan" setelah kirim ulang
                $request->session()->forget('error');
                $request->session()->forget('errors');

                \Log::info('OTP sent successfully - Session Updated', [
                    'user_temp_id' => $userTempId,
                    'phone' => $nomorHpDisplay,
                    'session_id' => $sessionId,
                    'is_resend' => $isResend,
                ]);

                $message = $isResend 
                    ? 'Kode OTP baru telah dikirim ke WhatsApp Anda.' 
                    : 'Kode OTP telah dikirim ke WhatsApp nomor ' . $nomorHpDisplay . '. Silakan cek pesan masuk Anda.';

                return redirect()->route('register', ['step' => 2])
                    ->with('success', $message);
                    // 'otp_sent' flash data is no longer strictly needed as we check session, 
                    // but kept for backward compatibility if view relies on it.
            } else {
                \Log::error('Failed to send OTP', [
                    'user_temp_id' => $userTempId,
                    'phone' => $nomorHpDisplay,
                    'error' => $otpResult['message'] ?? 'Unknown error',
                ]);

                return redirect()->route('register', ['step' => 2])
                    ->with('error', 'Gagal mengirim OTP: ' . ($otpResult['message'] ?? 'Unknown error'));
            }
        }

        // === CASE 2: User submit OTP code untuk verifikasi (bukan kirim ulang) ===
        if ($request->has('otp_code') && $request->filled('otp_code') && $request->isMethod('post')) {
            \Log::info('User submitting OTP for verification', [
                'user_temp_id' => $userTempId,
                'phone' => $nomorHpDisplay,
            ]);

            $validator = Validator::make($request->all(), [
                'otp_code' => 'required|string|size:6',
            ], [
                'otp_code.required' => 'Kode OTP harus diisi',
                'otp_code.size' => 'Kode OTP harus 6 digit',
            ]);

            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 2])
                    ->withErrors($validator)
                    ->withInput();
            }

            $verifyResult = $this->otpService->verify(
                $request->otp_code,
                $nomorHpDisplay,
                $sessionId
            );

            if (!$verifyResult['success']) {
                \Log::warning('OTP verification failed', [
                    'user_temp_id' => $userTempId,
                    'phone' => $nomorHpDisplay,
                    'message' => $verifyResult['message'],
                ]);
                return redirect()->route('register', ['step' => 2])
                    ->with('error', $verifyResult['message'])
                    ->withInput();
            }

            \Log::info('OTP verified successfully', ['user_temp_id' => $userTempId, 'phone' => $nomorHpDisplay]);
            $request->session()->put('register_otp_verified', true);
            return redirect()->route('register', ['step' => 3])
                ->with('success', 'Nomor HP berhasil diverifikasi. Silakan buat PIN Anda.');
        }

        // === CASE 3: Default - Tampilkan halaman Step 2 ===
        // Check if OTP has been sent and is still valid
        $otpSentAt = $request->session()->get('otp_sent_at');
        $otpExpiresAt = $request->session()->get('otp_expires_at');
        
        $otpSent = false;
        if ($otpSentAt && $otpExpiresAt) {
            // Check if not expired
            if (now()->lessThan($otpExpiresAt)) {
                $otpSent = true;
            } else {
                // Expired - clear session? 
                // Currently we keep it to show "Expired" state in frontend if desired, 
                // but strictly speaking, if expired, we might want to force resend UI.
                // For now, let's keep it true so user sees the input form and expiry message.
                $otpSent = true; 
            }
        }
        
        // Fallback: check session flash (legacy)
        if (session('otp_sent')) {
            $otpSent = true;
        }

        // Get remaining cooldown if OTP already sent
        $remainingCooldown = 0;
        if ($otpSent) {
            $remainingCooldown = $this->otpService->getRemainingCooldown($nomorHpDisplay);
        }

        \Log::info('Displaying Step 2 OTP page', [
            'user_temp_id' => $userTempId,
            'phone' => $nomorHpDisplay,
            'otp_sent' => $otpSent,
            'otp_sent_at' => $otpSentAt,
            'remaining_cooldown' => $remainingCooldown,
        ]);

        // Jangan tampilkan halaman OTP jika nomor kosong (safeguard terakhir)
        if ($nomorHpDisplay === '' || trim($nomorHpDisplay) === '') {
            return redirect()->route('register', ['step' => 1, 'substep' => 1])
                ->with('error', 'Nomor HP belum tersimpan. Silakan isi nomor HP di Langkah 1 (Data Diri) lalu lanjutkan kembali.');
        }

        // Sisa waktu OTP (detik) dihitung di server; maksimal 60 detik (1 menit) untuk tampilan
        $otpExpiresAt = $request->session()->get('otp_expires_at');
        $otpExpiresAtRemainingSeconds = 0;
        if ($otpExpiresAt) {
            $expiresAt = \Carbon\Carbon::parse($otpExpiresAt);
            $otpExpiresAtRemainingSeconds = max(0, (int) $expiresAt->diffInSeconds(now(), false));
            $otpExpiresAtRemainingSeconds = min(60, $otpExpiresAtRemainingSeconds); // kode berlaku 1 menit saja
        }

        // Tampilkan view dengan data (phone dinormalisasi agar tidak "Nomor tidak ditemukan")
        return view('auth.register', [
            'step' => 2,
            'subStep' => null,
            'sessionData' => [],
            'sessionId' => $sessionId,
            'formData' => [],
            'phone' => $nomorHpDisplay,
            'otpSent' => $otpSent, // Flag apakah OTP sudah dikirim
            'remainingCooldown' => $remainingCooldown, // Seconds remaining for resend
            'otpExpiresAt' => $otpExpiresAt,
            'otpExpiresAtRemainingSeconds' => $otpExpiresAtRemainingSeconds, // Untuk timer "Kode berlaku" (hindari bug timezone)
        ]);
    }

    /**
     * Handle Step 3: Create PIN and finalize (move from temp to original).
     */
    private function handleStep3Pin(Request $request)
    {
        // Check if OTP verified
        if (!$request->session()->get('register_otp_verified')) {
            return redirect()->route('register', ['step' => 2])
                ->with('error', 'Silakan verifikasi OTP terlebih dahulu');
        }

        $validator = Validator::make($request->all(), [
            'pin' => 'required|string|size:6|confirmed',
            'pin_confirmation' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register', ['step' => 3])
                ->withErrors($validator)
                ->withInput();
        }

        $userTempId = $request->session()->get('register_user_temp_id');
        $nasabahTempId = $request->session()->get('register_nasabah_temp_id');

        if (!$userTempId) {
            return redirect()->route('register', ['step' => 1])
                ->with('error', 'Data tidak ditemukan. Silakan mulai dari awal.');
        }

        try {
            DB::beginTransaction();

            $userTemp = \App\Models\UserTemp::find($userTempId);
            $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;

            if (!$userTemp) {
                throw new \Exception('Data user tidak ditemukan');
            }

            // Ensure all photos are in permanent storage before creating user
            $this->moveAllPhotosToPermanent($userTempId, $nasabahTempId);
            
            // Reload userTemp to get updated photo paths
            $userTemp->refresh();

            // Create User di tabel users dengan PIN
            $user = User::create([
                'nama' => $userTemp->nama,
                'email' => $userTemp->email,
                'password' => $userTemp->password,
                'nomor_hp' => $userTemp->nomor_hp,
                'foto' => $userTemp->foto,
                'pin' => $request->pin, // PIN langsung ke users
                'role' => 'nasabah',
            ]);

            // Update user_temp dengan user_id
            $userTemp->update(['user_id' => $user->id]);

            // Move data from temp to original tables
            // Photos should already be in permanent storage from moveAllPhotosToPermanent above
            if ($nasabahTemp) {
                // Reload nasabahTemp to get updated photo paths
                $nasabahTemp->refresh();
                
                // Create Nasabah
                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'no_kk' => $nasabahTemp->no_kk,
                    'tempat_lahir' => $nasabahTemp->tempat_lahir,
                    'tanggal_lahir' => $nasabahTemp->tanggal_lahir,
                    'jenis_kelamin' => $nasabahTemp->jenis_kelamin,
                    'alamat' => $nasabahTemp->alamat,
                    'alamat_domisili' => $nasabahTemp->alamat_domisili,
                    'foto_ktp' => $nasabahTemp->foto_ktp,
                    'foto_kk' => $nasabahTemp->foto_kk,
                    'foto_selfie' => $nasabahTemp->foto_selfie,
                ]);

                // Move Pekerjaan
                $pekerjaanTemp = PekerjaanTemp::where('nasabah_id', $nasabahTemp->id)->first();
                if ($pekerjaanTemp) {
                    Pekerjaan::create([
                        'nasabah_id' => $nasabah->id,
                        'pekerjaan' => $pekerjaanTemp->pekerjaan,
                        'penghasilan' => $pekerjaanTemp->penghasilan,
                        'nama_perusahaan' => $pekerjaanTemp->nama_perusahaan,
                        // Note: nama_bank tidak ada di tabel pekerjaan utama
                    ]);
                }

                // Move DataRek
                $dataRekTemp = DataRekTemp::where('nasabah_id', $nasabahTemp->id)->first();
                if ($dataRekTemp) {
                    DataRek::create([
                        'nasabah_id' => $nasabah->id,
                        'no_rekening' => $dataRekTemp->no_rekening,
                        'nama_pemilik_rekening' => $dataRekTemp->nama_pemilik_rekening,
                        'nama_bank' => $dataRekTemp->jenis_atm, // Map jenis_atm to nama_bank
                    ]);
                }

                // Move DataKtp
                $dataKtpTemp = DataKtpTemp::where('nasabah_id', $nasabahTemp->id)->first();
                if ($dataKtpTemp) {
                    // Reload to get updated file path
                    $dataKtpTemp->refresh();
                    
                    DataKtp::create([
                        'nasabah_id' => $nasabah->id,
                        'nik' => $dataKtpTemp->nik,
                        'nama_lengkap' => $dataKtpTemp->nama_lengkap,
                        'tempat_lahir' => $dataKtpTemp->tempat_lahir,
                        'tanggal_lahir' => $dataKtpTemp->tanggal_lahir,
                        'alamat' => $dataKtpTemp->alamat,
                        'jenis_kelamin' => $dataKtpTemp->jenis_kelamin,
                        'file_ktp' => $dataKtpTemp->file_ktp,
                    ]);
                }

                // Move Darurat - only if nama_lengkap is provided (required field)
                // Data darurat is optional, so we skip if not filled
                $daruratTemp = DaruratTemp::where('id_nasabah', $nasabahTemp->id)->first();
                if ($daruratTemp) {
                    // Reload to get updated photo path
                    $daruratTemp->refresh();
                    
                    // Only create Darurat if nama_lengkap is provided and not empty
                    // This is the minimum required field for tbl_darurat
                    if (!empty($daruratTemp->nama_lengkap) && trim($daruratTemp->nama_lengkap) !== '') {
                        // Generate unique values for unique fields if empty to avoid constraint violations
                        // For no_telepon (char 12, unique): use nasabah_id + timestamp
                        $noTelepon = !empty($daruratTemp->no_telepon) && trim($daruratTemp->no_telepon) !== '' 
                            ? trim($daruratTemp->no_telepon) 
                            : str_pad($nasabah->id, 12, '0', STR_PAD_LEFT);
                        
                        // For no_ktp (char 16, unique): use nasabah_id + timestamp
                        $noKtp = !empty($daruratTemp->no_ktp) && trim($daruratTemp->no_ktp) !== '' 
                            ? trim($daruratTemp->no_ktp) 
                            : str_pad($nasabah->id . time(), 16, '0', STR_PAD_LEFT);
                        
                        $daruratData = [
                            'id_nasabah' => $nasabah->id,
                            'nama_lengkap' => trim($daruratTemp->nama_lengkap),
                            'hubungan_peminjam' => !empty($daruratTemp->hubungan_peminjam) ? trim($daruratTemp->hubungan_peminjam) : '-',
                            'no_telepon' => $noTelepon,
                            'alamat' => !empty($daruratTemp->alamat) ? trim($daruratTemp->alamat) : '-',
                            'pekerjaan' => !empty($daruratTemp->pekerjaan) ? trim($daruratTemp->pekerjaan) : '-',
                            'email' => !empty($daruratTemp->email) ? trim($daruratTemp->email) : '-',
                            'no_ktp' => $noKtp,
                            'foto_ktp' => !empty($daruratTemp->foto_ktp) ? $daruratTemp->foto_ktp : '',
                        ];
                        
                        try {
                            Darurat::create($daruratData);
                            \Log::info('Darurat created successfully', ['nasabah_id' => $nasabah->id]);
                        } catch (\Illuminate\Database\QueryException $e) {
                            // Handle unique constraint violations
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
                                strpos($e->getMessage(), 'UNIQUE constraint') !== false) {
                                // Retry with more unique values
                                $daruratData['no_telepon'] = str_pad($nasabah->id . microtime(true), 12, '0', STR_PAD_LEFT);
                                $daruratData['no_ktp'] = str_pad($nasabah->id . microtime(true) . rand(100, 999), 16, '0', STR_PAD_LEFT);
                                try {
                                    Darurat::create($daruratData);
                                    \Log::info('Darurat created successfully after retry', ['nasabah_id' => $nasabah->id]);
                                } catch (\Exception $e2) {
                                    \Log::error('Error creating Darurat after retry', [
                                        'error' => $e2->getMessage(),
                                        'nasabah_id' => $nasabah->id,
                                    ]);
                                    // Don't throw - data darurat is optional, continue registration
                                }
                            } else {
                                \Log::error('Error creating Darurat', [
                                    'error' => $e->getMessage(),
                                    'nasabah_id' => $nasabah->id,
                                ]);
                                // Don't throw - data darurat is optional, continue registration
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error creating Darurat', [
                                'error' => $e->getMessage(),
                                'nasabah_id' => $nasabah->id,
                            ]);
                            // Don't throw - data darurat is optional, continue registration
                        }
                    } else {
                        \Log::info('Skipping Darurat creation: nama_lengkap is empty', [
                            'darurat_temp_id' => $daruratTemp->id,
                        ]);
                    }
                } else {
                    \Log::info('Skipping Darurat creation: no darurat data found');
                }
            }

            DB::commit();

            // Clear all session data
            $request->session()->forget([
                'register_step1',
                'register_step2',
                'register_step3',
                'register_step4',
                'register_step5',
                'register_step6',
                'register_user_temp_id',
                'register_nasabah_temp_id',
                'register_otp_verified',
                'register_session_id',
                'otp_sent_at',
                'register_phone',
            ]);

            // Auto login user
            \Illuminate\Support\Facades\Auth::login($user);

            return redirect()->route('nasabah.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di Koperasi Majakara.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            \Log::error('Registration Database Error at Step 3 (PIN): ' . $e->getMessage(), [
                'code' => $e->getCode(),
                'sql_state' => $e->errorInfo[0] ?? null,
                'message' => $e->getMessage()
            ]);

            $errorCode = $e->errorInfo[1] ?? null;
            $errorMessage = 'Terjadi kesalahan database saat menyimpan akun.';
            
            if ($errorCode == 1062 || ($e->errorInfo[0] ?? null) === '23000') {
                if (strpos($e->getMessage(), 'email') !== false) {
                    $errorMessage = 'Email sudah terdaftar. Silakan gunakan email lain.';
                } elseif (strpos($e->getMessage(), 'nomor_hp') !== false) {
                    $errorMessage = 'Nomor HP sudah terdaftar. Silakan gunakan nomor lain.';
                } elseif (strpos($e->getMessage(), 'no_kk') !== false) {
                    $errorMessage = 'Nomor KK sudah terdaftar. Silakan periksa kembali.';
                } elseif (strpos($e->getMessage(), 'nik') !== false) {
                    $errorMessage = 'NIK sudah terdaftar. Silakan periksa kembali.';
                } else {
                    $errorMessage = 'Terdapat data unik yang sudah terdaftar di sistem.';
                }
            }
            
            return redirect()->route('register', ['step' => 3])
                ->with('error', $errorMessage)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('register', ['step' => 3])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
