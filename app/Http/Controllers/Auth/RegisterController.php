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

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
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
                    $formData['foto_ktp'] = $nasabahTemp->foto_ktp;
                    $formData['foto_kk'] = $nasabahTemp->foto_kk;
                    
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
     * Handle registration request - Step 1: Save all form data to temp.
     */
    public function register(Request $request)
    {
        $step = $request->input('step', 1);
        $subStep = $request->input('substep', 1);
        
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
            $currentEmail = $userTemp ? $userTemp->email : null;
            
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
                'password' => 'required|string|min:8|confirmed',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
        }
        // Sub-step 2: Detail Nasabah
        elseif ($subStep == 2) {
            // Get nasabah_temp_id dari session untuk exclude dari unique validation
            $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
            
            $validationRules = [
                'no_kk' => [
                    'nullable',
                    'string',
                    'max:16',
                    // Unique validation: exclude current nasabah_temp record
                    \Illuminate\Validation\Rule::unique('tbl_nasabah_temp', 'no_kk')->ignore($nasabahTempId),
                ],
                'tempat_lahir' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ];
        }
        // Sub-step 3: Pekerjaan
        elseif ($subStep == 3) {
            $validationRules = [
                'pekerjaan' => 'nullable|string|max:255',
                'penghasilan' => 'nullable|string|max:50', // Sekarang menggunakan string (range)
                'nama_perusahaan' => 'nullable|string|max:255',
                'nama_bank' => 'nullable|string|max:255',
            ];
        }
        // Sub-step 4: Rekening
        elseif ($subStep == 4) {
            $validationRules = [
                'no_rekening' => 'nullable|regex:/^[0-9]+$/|max:16',
                'nama_pemilik_rekening' => 'nullable|string|max:255',
                'jenis_atm' => 'nullable|string|max:20',
            ];
        }
        // Sub-step 5: Data KTP
        elseif ($subStep == 5) {
            $validationRules = [
                'nik' => 'nullable|string|max:16',
                'nama_lengkap_ktp' => 'nullable|string|max:100',
                'tempat_lahir_ktp' => 'nullable|string|max:100',
                'tanggal_lahir_ktp' => 'nullable|date',
                'rt_rw' => 'nullable|string|max:50',
                'kel_desa' => 'nullable|string|max:100',
                'kecamatan' => 'nullable|string|max:100',
                'alamat_ktp' => 'nullable|string', // Fallback jika tidak menggunakan RT/RW format
                'jenis_kelamin_ktp' => 'nullable|in:Laki-laki,Perempuan',
                'file_ktp' => 'nullable|string',
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
        if ($subStep == 4) {
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
                // Check if user_temp already exists for this email
                $userTemp = \App\Models\UserTemp::where('email', $request->email)->first();
                
                if (!$userTemp) {
                    // Handle foto upload - storage path: registrasi/temp/data_diri/foto_profil
                    $fotoPath = 'default-profile.jpg';
                    if ($request->hasFile('foto')) {
                        $foto = $request->file('foto');
                        $fotoPath = $foto->store('registrasi/temp/data_diri/foto_profil', 'public');
                    }

                    $userTemp = \App\Models\UserTemp::create([
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'nomor_hp' => $request->nomor_hp,
                        'foto' => $fotoPath,
                    ]);
                } else {
                    // Update existing - handle foto update
                    $updateData = [
                        'nama' => $request->nama ?? $userTemp->nama,
                        'nomor_hp' => $request->nomor_hp ?? $userTemp->nomor_hp,
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

            // Sub-step 2: Create/Update NasabahTemp
            if ($subStep == 2) {
                $nasabahTemp = NasabahTemp::where('user_id', $userTemp->id)->first();
                
                $nasabahData = [
                    'user_id' => $userTemp->id,
                ];
                
                // Only add fields that have values
                if ($request->filled('no_kk')) {
                    $nasabahData['no_kk'] = $request->no_kk;
                }
                if ($request->filled('tempat_lahir')) {
                    $nasabahData['tempat_lahir'] = $request->tempat_lahir;
                }
                if ($request->filled('tanggal_lahir')) {
                    $nasabahData['tanggal_lahir'] = $request->tanggal_lahir;
                }
                if ($request->filled('jenis_kelamin')) {
                    $nasabahData['jenis_kelamin'] = $request->jenis_kelamin;
                }
                if ($request->filled('alamat')) {
                    $nasabahData['alamat'] = $request->alamat;
                }

                // Handle foto uploads - storage path: registrasi/temp/users_{id}/foto_ktp dan foto_kk
                $userId = $userTemp->id;
                if ($request->hasFile('foto_ktp')) {
                    // Delete old foto_ktp if exists
                    if ($nasabahTemp && $nasabahTemp->foto_ktp && Storage::disk('public')->exists($nasabahTemp->foto_ktp)) {
                        Storage::disk('public')->delete($nasabahTemp->foto_ktp);
                    }
                    
                    $fotoKtp = $request->file('foto_ktp');
                    $nasabahData['foto_ktp'] = $fotoKtp->store("registrasi/temp/users_{$userId}/foto_ktp", 'public');
                }
                if ($request->hasFile('foto_kk')) {
                    // Delete old foto_kk if exists
                    if ($nasabahTemp && $nasabahTemp->foto_kk && Storage::disk('public')->exists($nasabahTemp->foto_kk)) {
                        Storage::disk('public')->delete($nasabahTemp->foto_kk);
                    }
                    
                    $fotoKk = $request->file('foto_kk');
                    $nasabahData['foto_kk'] = $fotoKk->store("registrasi/temp/users_{$userId}/foto_kk", 'public');
                }

                if ($nasabahTemp) {
                    // Update existing record - only update fields that have values
                    $updateData = [];
                    
                    // Only update fields that are provided and different from current value
                    if ($request->has('no_kk')) { // Use has() instead of filled() to detect empty string
                        $newNoKk = trim($request->no_kk);
                        // Get current no_kk, but filter out temporary values for comparison
                        $currentNoKkRaw = $nasabahTemp->no_kk;
                        $currentNoKk = ($currentNoKkRaw && strpos($currentNoKkRaw, 'TEMP') !== 0) ? trim($currentNoKkRaw) : '';
                        
                        // Only update if:
                        // 1. User provided a value (not empty)
                        // 2. Different from current value (excluding temporary values)
                        // 3. Not a temporary value itself
                        if ($newNoKk !== '' && $newNoKk !== $currentNoKk && strpos($newNoKk, 'TEMP') !== 0) {
                            // Check if new no_kk already exists in another record
                            $existingNoKk = NasabahTemp::where('no_kk', $newNoKk)
                                ->where('id', '!=', $nasabahTemp->id)
                                ->first();
                            
                            if ($existingNoKk) {
                                // If exists in another record, don't update no_kk (keep current value)
                                \Log::warning('no_kk already exists in another record, keeping current value', [
                                    'current_no_kk' => $currentNoKkRaw,
                                    'attempted_no_kk' => $newNoKk,
                                    'existing_record_id' => $existingNoKk->id,
                                ]);
                                // Don't add no_kk to updateData - keep current value
                            } else {
                                // Safe to update
                                $updateData['no_kk'] = $newNoKk;
                                \Log::info('no_kk will be updated', [
                                    'from' => $currentNoKkRaw,
                                    'to' => $newNoKk,
                                ]);
                            }
                        } else if ($newNoKk === '' && $currentNoKkRaw && strpos($currentNoKkRaw, 'TEMP') === 0) {
                            // If user clears the field and current value is temporary, set to NULL
                            $updateData['no_kk'] = null;
                            \Log::info('Clearing temporary no_kk value, setting to NULL');
                        } else {
                            \Log::info('no_kk unchanged, skipping update', [
                                'current_no_kk' => $currentNoKkRaw,
                                'new_no_kk' => $newNoKk,
                            ]);
                        }
                    }
                    
                    if ($request->filled('tempat_lahir') && $request->tempat_lahir !== $nasabahTemp->tempat_lahir) {
                        $updateData['tempat_lahir'] = $request->tempat_lahir;
                    }
                    
                    if ($request->filled('tanggal_lahir')) {
                        $newTanggalLahir = $request->tanggal_lahir;
                        $currentTanggalLahir = $nasabahTemp->tanggal_lahir ? $nasabahTemp->tanggal_lahir->format('Y-m-d') : null;
                        if ($newTanggalLahir !== $currentTanggalLahir) {
                            $updateData['tanggal_lahir'] = $request->tanggal_lahir;
                        }
                    }
                    
                    if ($request->filled('jenis_kelamin') && $request->jenis_kelamin !== $nasabahTemp->jenis_kelamin) {
                        $updateData['jenis_kelamin'] = $request->jenis_kelamin;
                    }
                    
                    if ($request->filled('alamat') && $request->alamat !== $nasabahTemp->alamat) {
                        $updateData['alamat'] = $request->alamat;
                    }
                    
                    // Add foto paths if they exist
                    if (isset($nasabahData['foto_ktp'])) {
                        $updateData['foto_ktp'] = $nasabahData['foto_ktp'];
                    }
                    if (isset($nasabahData['foto_kk'])) {
                        $updateData['foto_kk'] = $nasabahData['foto_kk'];
                    }
                    
                    if (!empty($updateData)) {
                        \Log::info('Updating NasabahTemp', [
                            'id' => $nasabahTemp->id,
                            'update_data' => $updateData,
                            'current_no_kk' => $nasabahTemp->no_kk,
                        ]);
                        
                        try {
                            $nasabahTemp->update($updateData);
                            \Log::info('NasabahTemp updated successfully');
                        } catch (\Illuminate\Database\QueryException $e) {
                            \Log::error('Database error updating NasabahTemp', [
                                'error' => $e->getMessage(),
                                'code' => $e->getCode(),
                                'sql_state' => $e->errorInfo[0] ?? null,
                                'update_data' => $updateData,
                            ]);
                            
                            // If unique constraint violation on no_kk, remove it from update and retry
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
                                strpos($e->getMessage(), 'UNIQUE constraint') !== false ||
                                ($e->errorInfo[0] ?? '') === '23000') {
                                if (isset($updateData['no_kk'])) {
                                    \Log::warning('Unique constraint violation on no_kk, removing from update', [
                                        'attempted_no_kk' => $updateData['no_kk'],
                                        'current_no_kk' => $nasabahTemp->no_kk,
                                    ]);
                                    unset($updateData['no_kk']);
                                    
                                    // Retry update without no_kk
                                    if (!empty($updateData)) {
                                        $nasabahTemp->update($updateData);
                                        \Log::info('NasabahTemp updated without no_kk');
                                    } else {
                                        \Log::info('No other fields to update, skipping update');
                                    }
                                } else {
                                    throw $e; // Re-throw if it's a different unique constraint
                                }
                            } else {
                                throw $e; // Re-throw if it's a different error
                            }
                        }
                    } else {
                        \Log::info('No changes to update for NasabahTemp', ['id' => $nasabahTemp->id]);
                    }
                } else {
                    // Create new record - ensure all required fields have default values
                    // Handle no_kk: if empty, use NULL (database allows nullable, unique constraint allows multiple NULLs)
                    $noKk = $request->no_kk;
                    $noKkTrimmed = $noKk ? trim($noKk) : '';
                    
                    if (empty($noKkTrimmed) || strpos($noKkTrimmed, 'TEMP') === 0) {
                        // Use NULL instead of temporary value - database allows nullable
                        // Multiple NULL values are allowed in unique constraint (in most databases)
                        $noKk = null;
                        \Log::info('no_kk is empty or temporary, setting to NULL for create');
                    } else {
                        $noKk = $noKkTrimmed;
                    }
                    
                    $createData = [
                        'user_id' => $userTemp->id,
                        'no_kk' => $noKk, // Can be null if user doesn't provide
                        'tempat_lahir' => $request->tempat_lahir ?? null,
                        'tanggal_lahir' => $request->tanggal_lahir ?? null,
                        'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                        'alamat' => $request->alamat ?? null,
                    ];
                    
                    // Add foto paths if they exist
                    if (isset($nasabahData['foto_ktp'])) {
                        $createData['foto_ktp'] = $nasabahData['foto_ktp'];
                    }
                    if (isset($nasabahData['foto_kk'])) {
                        $createData['foto_kk'] = $nasabahData['foto_kk'];
                    }
                    
                    try {
                        \Log::info('Creating NasabahTemp', [
                            'data' => array_merge($createData, [
                                'no_kk' => $createData['no_kk'] ? '***provided***' : 'NULL',
                                'no_kk_length' => $createData['no_kk'] ? strlen($createData['no_kk']) : 0
                            ])
                        ]);
                        $nasabahTemp = NasabahTemp::create($createData);
                        \Log::info('NasabahTemp created successfully', ['id' => $nasabahTemp->id]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        \Log::error('Database error creating NasabahTemp', [
                            'error' => $e->getMessage(),
                            'code' => $e->getCode(),
                            'sql_state' => $e->errorInfo[0] ?? null,
                        ]);
                        
                        // If unique constraint violation on no_kk and no_kk is not null
                        if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
                            strpos($e->getMessage(), 'UNIQUE constraint') !== false ||
                            ($e->errorInfo[0] ?? '') === '23000') {
                            if (strpos($e->getMessage(), 'no_kk') !== false || strpos($e->getMessage(), 'tbl_nasabah_temp') !== false) {
                                // If no_kk was provided and causes duplicate, set to NULL instead
                                if ($createData['no_kk'] !== null) {
                                    \Log::warning('no_kk duplicate, setting to NULL', [
                                        'attempted_no_kk' => $createData['no_kk'],
                                    ]);
                                    $createData['no_kk'] = null;
                                    $nasabahTemp = NasabahTemp::create($createData);
                                } else {
                                    throw $e; // If already null, re-throw
                                }
                            } else {
                                throw $e; // Re-throw if it's a different unique constraint
                            }
                        } else {
                            throw $e; // Re-throw if it's a different error
                        }
                    } catch (\Exception $e) {
                        \Log::error('General error creating NasabahTemp', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        throw $e;
                    }
                }
                
                // Ensure session is set
                if (isset($nasabahTemp) && $nasabahTemp) {
                    $request->session()->put('register_nasabah_temp_id', $nasabahTemp->id);
                    \Log::info('Session updated with nasabah_temp_id', ['id' => $nasabahTemp->id]);
                } else {
                    \Log::error('NasabahTemp is null after create/update');
                    throw new \Exception('Gagal menyimpan data nasabah. Silakan coba lagi.');
                }
            }

            // Sub-step 3: Create/Update PekerjaanTemp
            if ($subStep == 3) {
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
                        $pekerjaanTemp->update(array_filter($pekerjaanData));
                    } else {
                        PekerjaanTemp::create($pekerjaanData);
                    }
                }
            }

            // Sub-step 4: Create/Update DataRekTemp
            if ($subStep == 4) {
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
                        $dataRekTemp->update(array_filter($rekData));
                    } else {
                        DataRekTemp::create($rekData);
                    }
                }
            }

            // Sub-step 5: Create/Update DataKtpTemp
            if ($subStep == 5) {
                $nasabahTempId = $request->session()->get('register_nasabah_temp_id');
                $nasabahTemp = $nasabahTempId ? NasabahTemp::find($nasabahTempId) : null;
                
                if ($nasabahTemp) {
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
                        DataKtpTemp::create($ktpData);
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
     * TODO: Implement OTP verification logic here
     * Structure maintained for future implementation
     */
    private function handleStep2Otp(Request $request)
    {
        // Check if step 1 data exists
        $userTempId = $request->session()->get('register_user_temp_id');
        if (!$userTempId) {
            return redirect()->route('register', ['step' => 1])
                ->with('error', 'Silakan lengkapi data diri terlebih dahulu');
        }

        $userTemp = \App\Models\UserTemp::find($userTempId);
        if (!$userTemp || !$userTemp->nomor_hp) {
            return redirect()->route('register', ['step' => 1])
                ->with('error', 'Nomor HP belum diisi');
        }

        // Store phone in session for display
        $request->session()->put('register_phone', $userTemp->nomor_hp);

        // TODO: Implement OTP generation and sending logic here
        // Example structure:
        // 1. Generate OTP code
        // 2. Save to database (tbl_otp)
        // 3. Send OTP via WhatsApp/SMS/Email
        // 4. Return success/error message

        // If OTP not sent yet, send it automatically
        if (!$request->has('otp_code')) {
            // TODO: Generate and send OTP
            // For now, skip OTP verification and go to step 3
            $request->session()->put('register_otp_verified', true);
            
            return redirect()->route('register', ['step' => 3])
                ->with('success', 'Silakan buat PIN Anda.');
        }

        // Verify OTP
        $validator = Validator::make($request->all(), [
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register', ['step' => 2])
                ->withErrors($validator)
                ->withInput();
        }

        // TODO: Implement OTP verification logic here
        // Example structure:
        // 1. Check OTP code from database (tbl_otp)
        // 2. Verify OTP is valid and not expired
        // 3. Mark OTP as verified
        // 4. Set session register_otp_verified = true

        // For now, accept any 6-digit code (temporary)
        $request->session()->put('register_otp_verified', true);
        
        return redirect()->route('register', ['step' => 3])
            ->with('success', 'OTP berhasil diverifikasi. Silakan buat PIN Anda.');
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
                    'foto_ktp' => $nasabahTemp->foto_ktp,
                    'foto_kk' => $nasabahTemp->foto_kk,
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
            ]);

            // Auto login user
            \Illuminate\Support\Facades\Auth::login($user);

            return redirect()->route('nasabah.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di Koperasi Majakara.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('register', ['step' => 3])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
