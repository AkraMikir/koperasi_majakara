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
     * Show the registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $step = $request->get('step', 1);
        
        // Validate step (1 = Form data, 2 = OTP, 3 = PIN)
        if (!in_array($step, [1, 2, 3])) {
            $step = 1;
        }

        // Get session data for step 1
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

        return view('auth.register', [
            'step' => $step,
            'sessionData' => $sessionData,
            'sessionId' => $request->session()->get('register_session_id'),
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

        // Upload file
        $fileKtp = $request->file('file_ktp');
        $fileKtpPath = $fileKtp->store('ktp', 'public');

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
        
        // Validate step
        if (!in_array($step, [1, 2, 3])) {
            return redirect()->route('register')->with('error', 'Invalid step');
        }

        // Step 1: Save all form data to temp tables
        if ($step == 1) {
            return $this->handleStep1($request);
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
     * Handle Step 1: Save all form data to temp tables.
     */
    private function handleStep1(Request $request)
    {
        // Validasi semua data (field bisa nullable sesuai kebutuhan)
        $validator = Validator::make($request->all(), [
            // Step 1: Data Diri
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email|unique:users_temp,email',
            'nomor_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Step 2: Detail Nasabah
            'no_kk' => 'nullable|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            
            // Step 3: Pekerjaan
            'pekerjaan' => 'nullable|string|max:255',
            'penghasilan' => 'nullable|numeric|min:0',
            'nama_perusahaan' => 'nullable|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            
            // Step 4: Rekening
            'no_rekening' => 'nullable|string|max:16',
            'nama_pemilik_rekening' => 'nullable|string|max:255',
            'jenis_atm' => 'nullable|string|max:20',
            
            // Step 5: Data KTP
            'nik' => 'nullable|string|max:16',
            'nama_lengkap_ktp' => 'nullable|string|max:100',
            'tempat_lahir_ktp' => 'nullable|string|max:100',
            'tanggal_lahir_ktp' => 'nullable|date',
            'alamat_ktp' => 'nullable|string',
            'jenis_kelamin_ktp' => 'nullable|in:Laki-laki,Perempuan',
            'file_ktp' => 'nullable|string', // Path dari OCR atau upload
            
            // Step 6: Kontak Darurat
            'darurat_nama_lengkap' => 'nullable|string|max:255',
            'hubungan_peminjam' => 'nullable|string|max:100',
            'darurat_no_telepon' => 'nullable|string|max:20',
            'darurat_alamat' => 'nullable|string',
            'darurat_pekerjaan' => 'nullable|string|max:100',
            'darurat_email' => 'nullable|string|email|max:255',
            'darurat_no_ktp' => 'nullable|string|max:16',
            'darurat_foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register', ['step' => 1])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get or create session ID
            $sessionId = $request->session()->get('register_session_id');
            if (!$sessionId) {
                $sessionId = Str::uuid()->toString();
                $request->session()->put('register_session_id', $sessionId);
            }

            // Step 1: Create UserTemp (jika ada data)
            $userTemp = null;
            if ($request->filled('nama') && $request->filled('email') && $request->filled('password')) {
                // Check if user_temp already exists for this email
                $userTemp = \App\Models\UserTemp::where('email', $request->email)->first();
                
                if (!$userTemp) {
                    // Handle foto upload
                    $fotoPath = 'default-profile.jpg';
                    if ($request->hasFile('foto')) {
                        $foto = $request->file('foto');
                        $fotoPath = $foto->store('profiles', 'public');
                    }

                    $userTemp = \App\Models\UserTemp::create([
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'nomor_hp' => $request->nomor_hp,
                        'foto' => $fotoPath,
                    ]);
                } else {
                    // Update existing
                    $userTemp->update([
                        'nama' => $request->nama ?? $userTemp->nama,
                        'nomor_hp' => $request->nomor_hp ?? $userTemp->nomor_hp,
                        'password' => $request->password ? Hash::make($request->password) : $userTemp->password,
                    ]);
                }
            }

            // Step 2: Create/Update NasabahTemp
            if ($userTemp && ($request->filled('no_kk') || $request->filled('tempat_lahir'))) {
                $nasabahTemp = NasabahTemp::where('user_id', $userTemp->id)->first();
                
                $nasabahData = [
                    'user_id' => $userTemp->id,
                    'no_kk' => $request->no_kk,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                ];

                // Handle foto uploads
                if ($request->hasFile('foto_ktp')) {
                    $fotoKtp = $request->file('foto_ktp');
                    $nasabahData['foto_ktp'] = $fotoKtp->store('ktp', 'public');
                }
                if ($request->hasFile('foto_kk')) {
                    $fotoKk = $request->file('foto_kk');
                    $nasabahData['foto_kk'] = $fotoKk->store('kk', 'public');
                }

                if ($nasabahTemp) {
                    $nasabahTemp->update(array_filter($nasabahData));
                } else {
                    $nasabahTemp = NasabahTemp::create($nasabahData);
                }
            } else {
                $nasabahTemp = null;
            }

            // Step 3: Create/Update PekerjaanTemp
            if ($nasabahTemp && ($request->filled('pekerjaan') || $request->filled('penghasilan'))) {
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

            // Step 4: Create/Update DataRekTemp
            if ($nasabahTemp && $request->filled('no_rekening')) {
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

            // Step 5: Create/Update DataKtpTemp
            if ($nasabahTemp && ($request->filled('nik') || $request->filled('file_ktp'))) {
                $dataKtpTemp = DataKtpTemp::where('nasabah_id', $nasabahTemp->id)->first();
                
                $ktpData = [
                    'nasabah_id' => $nasabahTemp->id,
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap_ktp,
                    'tempat_lahir' => $request->tempat_lahir_ktp,
                    'tanggal_lahir' => $request->tanggal_lahir_ktp,
                    'alamat' => $request->alamat_ktp,
                    'jenis_kelamin' => $request->jenis_kelamin_ktp,
                    'file_ktp' => $request->file_ktp, // Path dari OCR atau upload
                ];

                // Handle file KTP upload jika belum ada dari OCR
                if ($request->hasFile('file_ktp_upload') && !$request->file_ktp) {
                    $fileKtp = $request->file('file_ktp_upload');
                    $ktpData['file_ktp'] = $fileKtp->store('ktp', 'public');
                }

                if ($dataKtpTemp) {
                    $dataKtpTemp->update(array_filter($ktpData));
                } else {
                    DataKtpTemp::create($ktpData);
                }
            }

            // Step 6: Create/Update DaruratTemp
            if ($nasabahTemp && $request->filled('darurat_nama_lengkap')) {
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

                // Handle foto KTP darurat upload
                if ($request->hasFile('darurat_foto_ktp')) {
                    $daruratFotoKtp = $request->file('darurat_foto_ktp');
                    $daruratData['foto_ktp'] = $daruratFotoKtp->store('ktp', 'public');
                }

                if ($daruratTemp) {
                    $daruratTemp->update(array_filter($daruratData));
                } else {
                    DaruratTemp::create($daruratData);
                }
            }

            DB::commit();

            // Store session data for next steps
            $request->session()->put('register_user_temp_id', $userTemp->id ?? null);
            $request->session()->put('register_nasabah_temp_id', $nasabahTemp->id ?? null);

            // Redirect to step 2 (OTP) jika data minimal sudah ada
            if ($userTemp && $userTemp->nomor_hp) {
                return redirect()->route('register', ['step' => 2])
                    ->with('success', 'Data berhasil disimpan. Silakan verifikasi nomor HP Anda.');
            }

            return redirect()->route('register', ['step' => 1])
                ->with('success', 'Data berhasil disimpan. Lanjutkan mengisi form.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('register', ['step' => 1])
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
            if ($nasabahTemp) {
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

                // Move Darurat
                $daruratTemp = DaruratTemp::where('id_nasabah', $nasabahTemp->id)->first();
                if ($daruratTemp) {
                    Darurat::create([
                        'id_nasabah' => $nasabah->id,
                        'nama_lengkap' => $daruratTemp->nama_lengkap,
                        'hubungan_peminjam' => $daruratTemp->hubungan_peminjam,
                        'no_telepon' => $daruratTemp->no_telepon,
                        'alamat' => $daruratTemp->alamat,
                        'pekerjaan' => $daruratTemp->pekerjaan,
                        'email' => $daruratTemp->email,
                        'no_ktp' => $daruratTemp->no_ktp,
                        'foto_ktp' => $daruratTemp->foto_ktp,
                    ]);
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
            auth()->login($user);

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
