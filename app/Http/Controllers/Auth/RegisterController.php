<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NasabahTemp;
use App\Models\PekerjaanTemp;
use App\Models\DataRekTemp;
use App\Models\DataKtpTemp;
use App\Models\DaruratTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $step = $request->get('step', 1);
        
        // Validate step
        if (!in_array($step, [1, 2, 3, 4, 5, 6])) {
            $step = 1;
        }
        
        return view('auth.register', [
            'step' => $step
        ]);
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $step = $request->input('step', 1);
        
        // Validate step
        if (!in_array($step, [1, 2, 3, 4, 5, 6])) {
            return redirect()->route('register')->with('error', 'Invalid step');
        }
        
        // Handle step 1: Basic user registration
        if ($step == 1) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'nomor_hp' => 'required|string|max:12',
                'password' => 'required|string|min:8|confirmed',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 1])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Store step 1 data in session
            $step1Data = $request->except(['_token', 'step', 'password_confirmation']);
            
            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoPath = $foto->store('profiles', 'public');
                $step1Data['foto'] = $fotoPath;
            } else {
                $step1Data['foto'] = 'default-profile.jpg'; // Default foto
            }
            
            $request->session()->put('register_step1', $step1Data);
            
            // Redirect to step 2
            return redirect()->route('register', ['step' => 2]);
        }
        
        // Handle step 2: Detail Nasabah
        if ($step == 2) {
            // Check if step 1 is completed
            if (!$request->session()->has('register_step1')) {
                return redirect()->route('register', ['step' => 1])
                    ->with('error', 'Silakan lengkapi data diri terlebih dahulu');
            }
            
            $validator = Validator::make($request->all(), [
                'no_kk' => 'required|string|size:16|unique:tbl_nasabah_temp',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 2])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $step2Data = $request->except(['_token', 'step']);
            
            // Handle foto uploads
            if ($request->hasFile('foto_ktp')) {
                $fotoKtp = $request->file('foto_ktp');
                $fotoKtpPath = $fotoKtp->store('ktp', 'public');
                $step2Data['foto_ktp'] = $fotoKtpPath;
            }
            
            if ($request->hasFile('foto_kk')) {
                $fotoKk = $request->file('foto_kk');
                $fotoKkPath = $fotoKk->store('kk', 'public');
                $step2Data['foto_kk'] = $fotoKkPath;
            }
            
            $request->session()->put('register_step2', $step2Data);
            
            // Redirect to step 3
            return redirect()->route('register', ['step' => 3]);
        }
        
        // Handle step 3: Pekerjaan
        if ($step == 3) {
            // Check if step 2 is completed
            if (!$request->session()->has('register_step2')) {
                return redirect()->route('register', ['step' => 2])
                    ->with('error', 'Silakan lengkapi data nasabah terlebih dahulu');
            }
            
            $validator = Validator::make($request->all(), [
                'pekerjaan' => 'nullable|string|max:255',
                'penghasilan' => 'nullable|numeric|min:0',
                'nama_perusahaan' => 'nullable|string|max:255',
                'nama_bank' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 3])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $request->session()->put('register_step3', $request->except(['_token', 'step']));
            
            // Redirect to step 4
            return redirect()->route('register', ['step' => 4]);
        }
        
        // Handle step 4: Data Rekening
        if ($step == 4) {
            // Check if step 3 is completed
            if (!$request->session()->has('register_step3')) {
                return redirect()->route('register', ['step' => 3])
                    ->with('error', 'Silakan lengkapi data pekerjaan terlebih dahulu');
            }
            
            $validator = Validator::make($request->all(), [
                'no_rekening' => 'required|string|size:16|unique:tbl_data_rek_temp',
                'nama_pemilik_rekening' => 'required|string|max:255',
                'jenis_atm' => 'required|string|max:20',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 4])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $request->session()->put('register_step4', $request->except(['_token', 'step']));
            
            // Redirect to step 5
            return redirect()->route('register', ['step' => 5]);
        }
        
        // Handle step 5: OCR KTP (Data KTP)
        if ($step == 5) {
            // Check if step 4 is completed
            if (!$request->session()->has('register_step4')) {
                return redirect()->route('register', ['step' => 4])
                    ->with('error', 'Silakan lengkapi data rekening terlebih dahulu');
            }
            
            $validator = Validator::make($request->all(), [
                'nik' => 'required|string|size:16|unique:tbl_data_ktp_temp',
                'nama_lengkap' => 'required|string|max:100',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'file_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 5])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $step5Data = $request->except(['_token', 'step']);
            
            // Handle file KTP upload
            if ($request->hasFile('file_ktp')) {
                $fileKtp = $request->file('file_ktp');
                $fileKtpPath = $fileKtp->store('ktp', 'public');
                $step5Data['file_ktp'] = $fileKtpPath;
            }
            
            $request->session()->put('register_step5', $step5Data);
            
            // Redirect to step 6
            return redirect()->route('register', ['step' => 6]);
        }
        
        // Handle step 6: Data Darurat and final submission
        if ($step == 6) {
            // Check if step 5 is completed
            if (!$request->session()->has('register_step5')) {
                return redirect()->route('register', ['step' => 5])
                    ->with('error', 'Silakan lengkapi data KTP terlebih dahulu');
            }
            
            $validator = Validator::make($request->all(), [
                'darurat_nama_lengkap' => 'required|string|max:255',
                'hubungan_peminjam' => 'required|string|max:100',
                'darurat_no_telepon' => 'required|string|max:12|unique:tbl_darurat_temp,no_telepon',
                'darurat_alamat' => 'required|string',
                'darurat_pekerjaan' => 'required|string|max:100',
                'darurat_email' => 'required|string|email|max:255',
                'darurat_no_ktp' => 'required|string|size:16|unique:tbl_darurat_temp,no_ktp',
                'darurat_foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            
            if ($validator->fails()) {
                return redirect()->route('register', ['step' => 6])
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $step6Data = $request->except(['_token', 'step']);
            
            // Handle foto KTP darurat upload
            if ($request->hasFile('darurat_foto_ktp')) {
                $daruratFotoKtp = $request->file('darurat_foto_ktp');
                $daruratFotoKtpPath = $daruratFotoKtp->store('ktp', 'public');
                $step6Data['darurat_foto_ktp'] = $daruratFotoKtpPath;
            }
            
            $request->session()->put('register_step6', $step6Data);
            
            // Process all registration data and save to database
            try {
                DB::beginTransaction();
                
                // Get all step data from session
                $step1 = $request->session()->get('register_step1');
                $step2 = $request->session()->get('register_step2');
                $step3 = $request->session()->get('register_step3');
                $step4 = $request->session()->get('register_step4');
                $step5 = $request->session()->get('register_step5');
                $step6 = $request->session()->get('register_step6');
                
                // Step 1: Create User
                $user = User::create([
                    'nama' => $step1['nama'],
                    'email' => $step1['email'],
                    'password' => Hash::make($step1['password']),
                    'nomor_hp' => $step1['nomor_hp'],
                    'foto' => $step1['foto'],
                    'role' => 'nasabah',
                ]);
                
                // Step 2: Create NasabahTemp
                $nasabahTemp = NasabahTemp::create([
                    'user_id' => $user->id,
                    'no_kk' => $step2['no_kk'],
                    'tempat_lahir' => $step2['tempat_lahir'],
                    'tanggal_lahir' => $step2['tanggal_lahir'],
                    'jenis_kelamin' => $step2['jenis_kelamin'],
                    'foto_ktp' => $step2['foto_ktp'] ?? null,
                    'foto_kk' => $step2['foto_kk'] ?? null,
                ]);
                
                // Step 3: Create PekerjaanTemp
                PekerjaanTemp::create([
                    'nasabah_id' => $nasabahTemp->id,
                    'pekerjaan' => $step3['pekerjaan'] ?? null,
                    'penghasilan' => $step3['penghasilan'] ?? null,
                    'nama_perusahaan' => $step3['nama_perusahaan'] ?? null,
                    'nama_bank' => $step3['nama_bank'] ?? null,
                ]);
                
                // Step 4: Create DataRekTemp
                DataRekTemp::create([
                    'nasabah_id' => $nasabahTemp->id,
                    'no_rekening' => $step4['no_rekening'],
                    'nama_pemilik_rekening' => $step4['nama_pemilik_rekening'],
                    'jenis_atm' => $step4['jenis_atm'],
                ]);
                
                // Step 5: Create DataKtpTemp
                DataKtpTemp::create([
                    'nasabah_id' => $nasabahTemp->id,
                    'nik' => $step5['nik'],
                    'nama_lengkap' => $step5['nama_lengkap'],
                    'tempat_lahir' => $step5['tempat_lahir'],
                    'tanggal_lahir' => $step5['tanggal_lahir'],
                    'alamat' => $step5['alamat'],
                    'jenis_kelamin' => $step5['jenis_kelamin'],
                    'file_ktp' => $step5['file_ktp'],
                ]);
                
                // Step 6: Create DaruratTemp
                DaruratTemp::create([
                    'id_nasabah' => $nasabahTemp->id,
                    'nama_lengkap' => $step6['darurat_nama_lengkap'],
                    'hubungan_peminjam' => $step6['hubungan_peminjam'],
                    'no_telepon' => $step6['darurat_no_telepon'],
                    'alamat' => $step6['darurat_alamat'],
                    'pekerjaan' => $step6['darurat_pekerjaan'],
                    'email' => $step6['darurat_email'],
                    'no_ktp' => $step6['darurat_no_ktp'],
                    'foto_ktp' => $step6['darurat_foto_ktp'],
                ]);
                
                DB::commit();
                
                // Clear session data
                $request->session()->forget([
                    'register_step1',
                    'register_step2',
                    'register_step3',
                    'register_step4',
                    'register_step5',
                    'register_step6'
                ]);
                
                // Redirect to success page or login
                return redirect()->route('login')
                    ->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                
                return redirect()->route('register', ['step' => 6])
                    ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                    ->withInput();
            }
        }
        
        return redirect()->route('register');
    }
}
