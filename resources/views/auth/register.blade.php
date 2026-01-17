<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi - Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #674c1d;
            --primary-light: #8b6f2f;
            --primary-dark: #4a3514;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full">
            <!-- Progress Indicator -->
            <div class="mb-8 max-w-4xl mx-auto">
                @php
                    $step = $step ?? request()->get('step', 1);
                @endphp
                <div class="flex items-center justify-between">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="flex items-center {{ $i < 3 ? 'flex-1' : '' }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= $i ? 'bg-[#674c1d] text-white' : 'bg-gray-200 text-gray-500' }} transition-all duration-300">
                                <span class="text-sm font-semibold">{{ $i }}</span>
                            </div>
                            @if($i < 3)
                                <div class="flex-1 h-1 mx-2 {{ $step > $i ? 'bg-[#674c1d]' : 'bg-gray-200' }} transition-all duration-300"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-gray-600">
                    @php
                        $labels = [
                            1 => 'Data Lengkap',
                            2 => 'Verifikasi OTP',
                            3 => 'Buat PIN'
                        ];
                    @endphp
                    @for($i = 1; $i <= 3; $i++)
                        <div class="flex items-center {{ $i < 3 ? 'flex-1' : '' }}">
                            <span class="{{ $step >= $i ? 'text-[#674c1d] font-medium' : '' }} text-center">{{ $labels[$i] }}</span>
                            @if($i < 3)
                                <div class="flex-1 mx-2"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-6xl mx-auto">
                <div class="lg:grid lg:grid-cols-2">
                    <!-- Left Side - Form -->
                    <div class="p-8 lg:p-12">
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                                @if($step == 1)
                                    Registrasi Akun
                                @elseif($step == 2)
                                    Verifikasi Nomor HP
                                @else
                                    Buat PIN
                                @endif
                            </h2>
                            <p class="text-gray-600">
                                @if($step == 1)
                                    Lengkapi semua data diri Anda (Data Diri, Nasabah, Pekerjaan, Rekening, KTP, Kontak Darurat)
                                @elseif($step == 2)
                                    Masukkan kode OTP yang dikirim ke WhatsApp Anda
                                @else
                                    Buat PIN 6 digit untuk keamanan akun Anda
                                @endif
                            </p>
                        </div>

                        @php
                            $step = $step ?? request()->get('step', 1);
                        @endphp
                        <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" id="registerForm">
                            @csrf
                            <input type="hidden" name="step" value="{{ $step }}">

                            @if($step == 1)
                                <!-- Step 1: All Form Sections (Data Diri, Nasabah, Pekerjaan, Rekening, KTP dengan OCR, Kontak Darurat) -->
                                <!-- Data Diri Section -->
                                <div class="mb-8 pb-6 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">1. Data Diri</h3>
                                    <div class="space-y-4">
                                    <div>
                                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Masukkan nama lengkap">
                                        @error('nama')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="nama@email.com">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                                                <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" maxlength="20"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                    placeholder="089512543086">
                                        @error('nomor_hp')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                            </div>
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                                <input type="password" name="password" id="password"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Minimal 8 karakter">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Ulangi password">
                                            </div>
                                    </div>

                                    <div>
                                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil (Optional)</label>
                                            <label for="foto" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Pilih Foto</span>
                                                </div>
                                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoPreview')">
                                            </label>
                                        <div id="fotoPreview" class="mt-2 hidden">
                                            <img id="fotoPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Nasabah Section -->
                                <div class="mb-8 pb-6 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">2. Detail Nasabah</h3>
                                    <div class="space-y-4">
                                    <div>
                                        <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-2">Nomor KK</label>
                                            <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit Nomor KK">
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="Kota kelahiran">
                                        </div>

                                        <div>
                                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                            <textarea name="alamat" id="alamat" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                            placeholder="Alamat lengkap tempat tinggal">{{ old('alamat') }}</textarea>
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                                <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">Foto KTP (Optional)</label>
                                            <label for="foto_ktp" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KTP</span>
                                                </div>
                                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKtpPreview')">
                                            </label>
                                            <div id="fotoKtpPreview" class="mt-2 hidden">
                                                <img id="fotoKtpPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                        </div>

                                        <div>
                                                <label for="foto_kk" class="block text-sm font-medium text-gray-700 mb-2">Foto KK (Optional)</label>
                                            <label for="foto_kk" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KK</span>
                                                </div>
                                                <input type="file" name="foto_kk" id="foto_kk" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKkPreview')">
                                            </label>
                                            <div id="fotoKkPreview" class="mt-2 hidden">
                                                <img id="fotoKkPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pekerjaan Section -->
                                <div class="mb-8 pb-6 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">3. Data Pekerjaan (Optional)</h3>
                                    <div class="space-y-4">
                                    <div>
                                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama pekerjaan">
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                                <label for="penghasilan" class="block text-sm font-medium text-gray-700 mb-2">Penghasilan</label>
                                        <input type="number" name="penghasilan" id="penghasilan" value="{{ old('penghasilan') }}" step="0.01" min="0"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                    placeholder="0">
                                    </div>

                                    <div>
                                        <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                    placeholder="Nama perusahaan">
                                    </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rekening Section -->
                                <div class="mb-8 pb-6 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">4. Data Rekening Bank (Optional)</h3>
                                    <div class="space-y-4">
                                    <div>
                                        <label for="no_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                                            <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}" maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit nomor rekening">
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama_pemilik_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                                <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama sesuai rekening">
                                    </div>

                                    <div>
                                                <label for="jenis_atm" class="block text-sm font-medium text-gray-700 mb-2">Jenis Bank</label>
                                                <select name="jenis_atm" id="jenis_atm"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                                    <option value="">Pilih Bank</option>
                                            <option value="BCA" {{ old('jenis_atm') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                            <option value="Mandiri" {{ old('jenis_atm') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                            <option value="BNI" {{ old('jenis_atm') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                            <option value="BRI" {{ old('jenis_atm') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data KTP Section dengan OCR -->
                                <div class="mb-8 pb-6 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">5. Data KTP (dengan OCR)</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ambil/Upload Foto KTP</label>
                                            
                                            <!-- Camera/Upload Options -->
                                            <div class="flex items-center gap-3 mb-4">
                                                <button type="button" onclick="openCamera()" 
                                                    class="flex-1 px-4 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    Ambil dari Kamera
                                                </button>
                                                <span class="text-gray-500">atau</span>
                                                <label for="file_ktp_upload" class="flex-1 px-4 py-3 border-2 border-[#674c1d] text-[#674c1d] rounded-lg hover:bg-[#674c1d] hover:text-white transition-all font-medium cursor-pointer text-center">
                                                    Upload dari File
                                                </label>
                                            </div>

                                            <!-- Hidden file input for upload -->
                                            <input type="file" name="file_ktp_upload" id="file_ktp_upload" accept="image/*" class="hidden" onchange="handleKtpUpload(this)">
                                            
                                            <!-- Hidden file input for camera (with capture attribute) -->
                                            <input type="file" id="file_ktp_camera" accept="image/*" capture="environment" class="hidden" onchange="handleKtpUpload(this)">
                                            
                                            <!-- Camera Preview Modal -->
                                            <div id="cameraModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
                                                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h3 class="text-lg font-bold text-gray-900">Ambil Foto KTP</h3>
                                                        <button type="button" onclick="closeCamera()" class="text-gray-500 hover:text-gray-700">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <video id="cameraVideo" autoplay playsinline class="w-full rounded-lg mb-4 hidden"></video>
                                                    <canvas id="cameraCanvas" class="hidden"></canvas>
                                                    
                                                    <div id="cameraPreview" class="mb-4 hidden">
                                                        <img id="cameraPreviewImg" src="" alt="Preview" class="w-full rounded-lg border border-gray-300">
                                                    </div>
                                                    
                                                    <div class="flex gap-3">
                                                        <button type="button" onclick="capturePhoto()" id="btnCapture" 
                                                            class="flex-1 px-4 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium">
                                                            Ambil Foto
                                                        </button>
                                                        <button type="button" onclick="retakePhoto()" id="btnRetake" 
                                                            class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all font-medium hidden">
                                                            Ambil Ulang
                                                        </button>
                                                        <button type="button" onclick="usePhoto()" id="btnUsePhoto" 
                                                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-medium hidden">
                                                            Gunakan Foto Ini
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Preview Area -->
                                            <div class="mt-4">
                                                <div id="ktpPreview" class="hidden">
                                                    <p class="text-sm text-gray-600 mb-2">Preview Foto KTP:</p>
                                                    <div class="relative inline-block">
                                                        <img id="ktpPreviewImg" src="" alt="KTP Preview" class="max-w-full h-auto rounded-lg border border-gray-300 max-h-64">
                                                        <button type="button" onclick="removeKtpPhoto()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- OCR Button -->
                                                <div class="mt-4">
                                                    <button type="button" onclick="processOcr()" id="btnOcr" disabled
                                                        class="w-full px-6 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                                        <span id="ocrText">Proses OCR</span>
                                                        <span id="ocrLoading" class="hidden">Memproses...</span>
                                                    </button>
                                                </div>
                                                
                                                <div id="ocrResult" class="mt-4 hidden"></div>
                                            </div>
                                            
                                            <input type="hidden" name="file_ktp" id="file_ktp" value="{{ old('file_ktp') }}">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                                <input type="text" name="nik" id="nik" value="{{ old('nik') }}" maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit NIK">
                                    </div>

                                    <div>
                                                <label for="nama_lengkap_ktp" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap (Sesuai KTP)</label>
                                                <input type="text" name="nama_lengkap_ktp" id="nama_lengkap_ktp" value="{{ old('nama_lengkap_ktp') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama sesuai KTP">
                                            </div>
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                                <label for="tempat_lahir_ktp" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir_ktp" id="tempat_lahir_ktp" value="{{ old('tempat_lahir_ktp') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="Kota kelahiran">
                                        </div>

                                        <div>
                                                <label for="tanggal_lahir_ktp" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir_ktp" id="tanggal_lahir_ktp" value="{{ old('tanggal_lahir_ktp') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                        </div>
                                    </div>

                                    <div>
                                            <label for="jenis_kelamin_ktp" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                            <select name="jenis_kelamin_ktp" id="jenis_kelamin_ktp"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin_ktp') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin_ktp') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div>
                                            <label for="alamat_ktp" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Sesuai KTP)</label>
                                            <textarea name="alamat_ktp" id="alamat_ktp" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                                placeholder="Alamat lengkap sesuai KTP">{{ old('alamat_ktp') }}</textarea>
                                    </div>
                                    </div>
                                </div>

                                <!-- Kontak Darurat Section -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-[#674c1d] mb-4">6. Kontak Darurat (Optional)</h3>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="darurat_nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                                <input type="text" name="darurat_nama_lengkap" id="darurat_nama_lengkap" value="{{ old('darurat_nama_lengkap') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama lengkap kontak darurat">
                                    </div>

                                    <div>
                                        <label for="hubungan_peminjam" class="block text-sm font-medium text-gray-700 mb-2">Hubungan</label>
                                                <select name="hubungan_peminjam" id="hubungan_peminjam"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Hubungan</option>
                                            <option value="Suami/Istri" {{ old('hubungan_peminjam') == 'Suami/Istri' ? 'selected' : '' }}>Suami/Istri</option>
                                            <option value="Orang Tua" {{ old('hubungan_peminjam') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                            <option value="Anak" {{ old('hubungan_peminjam') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                            <option value="Saudara" {{ old('hubungan_peminjam') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                            <option value="Lainnya" {{ old('hubungan_peminjam') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                            </div>
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="darurat_no_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                                <input type="text" name="darurat_no_telepon" id="darurat_no_telepon" value="{{ old('darurat_no_telepon') }}" maxlength="20"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="081234567890">
                                        </div>

                                        <div>
                                            <label for="darurat_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                                <input type="email" name="darurat_email" id="darurat_email" value="{{ old('darurat_email') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="email@example.com">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="darurat_alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                            <textarea name="darurat_alamat" id="darurat_alamat" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                            placeholder="Alamat lengkap">{{ old('darurat_alamat') }}</textarea>
                                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="darurat_pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                                <input type="text" name="darurat_pekerjaan" id="darurat_pekerjaan" value="{{ old('darurat_pekerjaan') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Pekerjaan kontak darurat">
                                    </div>

                                        <div>
                                            <label for="darurat_no_ktp" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                                <input type="text" name="darurat_no_ktp" id="darurat_no_ktp" value="{{ old('darurat_no_ktp') }}" maxlength="16"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="16 digit NIK">
                                            </div>
                                        </div>

                                        <div>
                                            <label for="darurat_foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">Foto KTP (Optional)</label>
                                            <label for="darurat_foto_ktp" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KTP</span>
                                                </div>
                                                <input type="file" name="darurat_foto_ktp" id="darurat_foto_ktp" accept="image/*" class="hidden" onchange="previewImage(this, 'daruratKtpPreview')">
                                            </label>
                                            <div id="daruratKtpPreview" class="mt-2 hidden">
                                                <img id="daruratKtpPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @elseif($step == 2)
                                <!-- Step 2: OTP Verification -->
                                <div class="space-y-6">
                                    @if(session('success'))
                                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <p class="text-sm text-blue-800">
                                            <strong>Kode OTP telah dikirim ke WhatsApp Anda.</strong><br>
                                            Silakan cek pesan WhatsApp dan masukkan kode OTP 6 digit yang diterima.
                                        </p>
                                        <p class="text-xs text-blue-600 mt-2">
                                            Nomor HP: <strong>{{ session('register_phone') ?? '089512543086' }}</strong>
                                        </p>
                                    </div>

                                    <div>
                                        <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2">Kode OTP</label>
                                        <input type="text" name="otp_code" id="otp_code" maxlength="6" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                                            placeholder="000000" autocomplete="off">
                                        @error('otp_code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    <div class="flex items-center justify-between text-sm">
                                        <button type="button" onclick="resendOtp()" class="text-[#674c1d] hover:underline" disabled>
                                            Kirim ulang OTP
                                        </button>
                                        <span class="text-gray-500" id="otpTimer">Kode berlaku selama 5 menit</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <em>Fitur OTP sedang dalam pengembangan. Untuk sementara, masukkan kode OTP apa saja untuk melanjutkan.</em>
                                    </p>
                                </div>

                            @elseif($step == 3)
                                <!-- Step 3: Create PIN -->
                                <div class="space-y-6">
                                    @if(session('success'))
                                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Buat PIN 6 digit</strong><br>
                                            PIN ini akan digunakan untuk keamanan transaksi Anda. Pastikan PIN mudah diingat namun sulit ditebak.
                                        </p>
                                    </div>

                                    <div>
                                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN (6 digit)</label>
                                        <input type="password" name="pin" id="pin" maxlength="6" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                                            placeholder="000000" autocomplete="off" inputmode="numeric">
                                        @error('pin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="pin_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi PIN</label>
                                        <input type="password" name="pin_confirmation" id="pin_confirmation" maxlength="6" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                                            placeholder="000000" autocomplete="off" inputmode="numeric">
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-xs text-gray-600">
                                            <strong>Catatan:</strong> Setelah PIN dibuat, semua data Anda akan dipindahkan ke sistem dan akun Anda akan aktif. Pastikan semua data sudah benar sebelum melanjutkan.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="mt-8 flex justify-between">
                                @if($step > 1)
                                    <button type="button" data-step="{{ $step - 1 }}" onclick="goToStep(this.dataset.step)" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                        Kembali
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                        Kembali ke Login
                                    </a>
                                @endif

                                @if($step == 1)
                                    <button type="submit" 
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        Simpan & Lanjutkan
                                    </button>
                                @elseif($step == 2)
                                    <button type="submit" 
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        Verifikasi OTP
                                    </button>
                                @else
                                    <button type="submit" 
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        Buat PIN & Selesai
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Right Side - Image Placeholder -->
                    <div class="hidden lg:flex bg-gradient-to-br from-[#674c1d] to-[#4a3514] p-12 items-center justify-center">
                        <div class="text-center text-white">
                            <div class="mb-6">
                                <div class="w-64 h-64 mx-auto bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                                    <svg class="w-32 h-32 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Koperasi Majakara</h3>
                            <p class="text-white/80">Solusi Keuangan Terpercaya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-[#674c1d] font-medium hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function goToStep(step) {
            // Convert step to number
            step = parseInt(step);
            
            // Validate step (1-3)
            if (step < 1 || step > 3) {
                console.error('Invalid step:', step);
                return;
            }
            
            // Redirect ke step
            window.location.href = '{{ route("register") }}?step=' + step;
        }

        // Camera variables
        let cameraStream = null;
        let capturedPhotoBlob = null;

        // Open Camera
        function openCamera() {
            // Check if browser supports camera
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Browser Anda tidak mendukung akses kamera. Silakan gunakan opsi Upload dari File atau gunakan browser modern seperti Chrome, Firefox, atau Safari.');
                return;
            }
            
            const modal = document.getElementById('cameraModal');
            const video = document.getElementById('cameraVideo');
            const btnCapture = document.getElementById('btnCapture');
            const btnRetake = document.getElementById('btnRetake');
            const btnUsePhoto = document.getElementById('btnUsePhoto');
            const preview = document.getElementById('cameraPreview');
            
            modal.classList.remove('hidden');
            preview.classList.add('hidden');
            btnRetake.classList.add('hidden');
            btnUsePhoto.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            
            // Request camera access
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment', // Use back camera on mobile
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                } 
            })
            .then(function(stream) {
                cameraStream = stream;
                video.srcObject = stream;
                video.classList.remove('hidden');
            })
            .catch(function(err) {
                console.error('Error accessing camera:', err);
                let errorMsg = 'Tidak dapat mengakses kamera. ';
                if (err.name === 'NotAllowedError') {
                    errorMsg += 'Pastikan browser mengizinkan akses kamera.';
                } else if (err.name === 'NotFoundError') {
                    errorMsg += 'Kamera tidak ditemukan.';
                } else {
                    errorMsg += 'Silakan gunakan opsi Upload dari File.';
                }
                alert(errorMsg);
                closeCamera();
            });
        }

        // Close Camera
        function closeCamera() {
            const modal = document.getElementById('cameraModal');
            const video = document.getElementById('cameraVideo');
            const preview = document.getElementById('cameraPreview');
            const btnCapture = document.getElementById('btnCapture');
            const btnRetake = document.getElementById('btnRetake');
            const btnUsePhoto = document.getElementById('btnUsePhoto');
            
            // Stop camera stream
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            
            video.srcObject = null;
            video.classList.add('hidden');
            preview.classList.add('hidden');
            btnRetake.classList.add('hidden');
            btnUsePhoto.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            modal.classList.add('hidden');
            capturedPhotoBlob = null;
        }

        // Capture Photo
        function capturePhoto() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.getElementById('cameraCanvas');
            const preview = document.getElementById('cameraPreview');
            const previewImg = document.getElementById('cameraPreviewImg');
            const btnCapture = document.getElementById('btnCapture');
            const btnRetake = document.getElementById('btnRetake');
            const btnUsePhoto = document.getElementById('btnUsePhoto');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            
            // Convert to blob
            canvas.toBlob(function(blob) {
                capturedPhotoBlob = blob;
                const url = URL.createObjectURL(blob);
                previewImg.src = url;
                preview.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                btnUsePhoto.classList.remove('hidden');
                
                // Stop video stream
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    video.classList.add('hidden');
                }
            }, 'image/jpeg', 0.9);
        }

        // Retake Photo
        function retakePhoto() {
            const video = document.getElementById('cameraVideo');
            const preview = document.getElementById('cameraPreview');
            const btnCapture = document.getElementById('btnCapture');
            const btnRetake = document.getElementById('btnRetake');
            const btnUsePhoto = document.getElementById('btnUsePhoto');
            
            preview.classList.add('hidden');
            btnRetake.classList.add('hidden');
            btnUsePhoto.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            capturedPhotoBlob = null;
            
            // Restart camera
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    } 
                })
                .then(function(stream) {
                    cameraStream = stream;
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                })
                .catch(function(err) {
                    console.error('Error accessing camera:', err);
                    alert('Tidak dapat mengakses kamera. Silakan tutup modal dan coba lagi.');
                });
            }
        }

        // Use Photo
        function usePhoto() {
            if (!capturedPhotoBlob) {
                alert('Tidak ada foto yang diambil');
                return;
            }
            
            try {
                // Create a File object from blob
                const file = new File([capturedPhotoBlob], 'ktp-camera-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                
                // Create a DataTransfer object to simulate file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                
                // Set the file to the upload input
                const fileInput = document.getElementById('file_ktp_upload');
                if (fileInput) {
                    fileInput.files = dataTransfer.files;
                    
                    // Trigger the upload handler
                    handleKtpUpload(fileInput);
                } else {
                    // Fallback: directly show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('ktpPreview');
                        const previewImg = document.getElementById('ktpPreviewImg');
                        if (preview && previewImg) {
                            previewImg.src = e.target.result;
                            preview.classList.remove('hidden');
                        }
                        const btnOcr = document.getElementById('btnOcr');
                        if (btnOcr) btnOcr.disabled = false;
                    };
                    reader.readAsDataURL(capturedPhotoBlob);
                }
                
                // Close camera modal
                closeCamera();
            } catch (error) {
                console.error('Error using photo:', error);
                alert('Terjadi kesalahan saat menggunakan foto. Silakan coba lagi.');
            }
        }

        // Remove KTP Photo
        function removeKtpPhoto() {
            const preview = document.getElementById('ktpPreview');
            const fileInput = document.getElementById('file_ktp_upload');
            const fileInputCamera = document.getElementById('file_ktp_camera');
            const hiddenInput = document.getElementById('file_ktp');
            const btnOcr = document.getElementById('btnOcr');
            
            preview.classList.add('hidden');
            fileInput.value = '';
            fileInputCamera.value = '';
            hiddenInput.value = '';
            btnOcr.disabled = true;
            capturedPhotoBlob = null;
        }

        // Handle KTP Upload untuk OCR (from file or camera)
        function handleKtpUpload(input) {
            let file = null;
            
            if (input.files && input.files[0]) {
                file = input.files[0];
            } else if (capturedPhotoBlob) {
                file = capturedPhotoBlob;
            }
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('ktpPreview');
                    const previewImg = document.getElementById('ktpPreviewImg');
                    if (preview && previewImg) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
                
                // Enable OCR button
                document.getElementById('btnOcr').disabled = false;
            }
        }

        // Process OCR
        function processOcr() {
            let file = null;
            const fileInput = document.getElementById('file_ktp_upload');
            
            // Check if file from upload or camera
            if (fileInput.files && fileInput.files[0]) {
                file = fileInput.files[0];
            } else if (capturedPhotoBlob) {
                file = capturedPhotoBlob;
            }
            
            if (!file) {
                alert('Silakan ambil atau upload foto KTP terlebih dahulu');
                return;
            }

            const formData = new FormData();
            formData.append('file_ktp', file);
            formData.append('_token', '{{ csrf_token() }}');

            const btnOcr = document.getElementById('btnOcr');
            const ocrText = document.getElementById('ocrText');
            const ocrLoading = document.getElementById('ocrLoading');
            const ocrResult = document.getElementById('ocrResult');

            btnOcr.disabled = true;
            ocrText.classList.add('hidden');
            ocrLoading.classList.remove('hidden');

            fetch('{{ route("register.ocr") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                ocrText.classList.remove('hidden');
                ocrLoading.classList.add('hidden');
                btnOcr.disabled = false;

                if (data.success) {
                    // Auto-fill form dengan data OCR
                    if (data.data.nik) document.getElementById('nik').value = data.data.nik;
                    if (data.data.nama_lengkap) document.getElementById('nama_lengkap_ktp').value = data.data.nama_lengkap;
                    if (data.data.tempat_lahir) document.getElementById('tempat_lahir_ktp').value = data.data.tempat_lahir;
                    if (data.data.tanggal_lahir) document.getElementById('tanggal_lahir_ktp').value = data.data.tanggal_lahir;
                    if (data.data.alamat) document.getElementById('alamat_ktp').value = data.data.alamat;
                    if (data.data.jenis_kelamin) document.getElementById('jenis_kelamin_ktp').value = data.data.jenis_kelamin;
                    if (data.file_path) {
                        document.getElementById('file_ktp').value = data.file_path;
                        // Update file input with the uploaded file path
                        const fileInput = document.getElementById('file_ktp_upload');
                        if (fileInput.files && fileInput.files[0]) {
                            // File already in input, no need to update
                        }
                    }

                    // Show success message
                    ocrResult.innerHTML = '<div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">Data KTP berhasil diekstrak! Silakan periksa dan lengkapi jika ada yang kurang.</div>';
                    ocrResult.classList.remove('hidden');
                } else {
                    ocrResult.innerHTML = '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm">OCR gagal: ' + (data.message || 'Unknown error') + '. Silakan isi manual.</div>';
                    ocrResult.classList.remove('hidden');
                    if (data.file_path) document.getElementById('file_ktp').value = data.file_path;
                }
            })
            .catch(error => {
                ocrText.classList.remove('hidden');
                ocrLoading.classList.add('hidden');
                btnOcr.disabled = false;
                ocrResult.innerHTML = '<div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">Error: ' + error.message + '</div>';
                ocrResult.classList.remove('hidden');
            });
        }

        // Resend OTP
        // TODO: Implement OTP resend functionality
        function resendOtp() {
            alert('Fitur kirim ulang OTP sedang dalam pengembangan.');
            // TODO: Implement OTP resend logic here
            // Example:
            // 1. Call API to resend OTP
            // 2. Show success/error message
            // 3. Restart timer
        }

        // OTP Timer
        // TODO: Implement OTP timer functionality
        function startOtpTimer() {
            // TODO: Implement countdown timer for OTP expiration
            // Example:
            // let timeLeft = 300; // 5 minutes
            // Update timer element every second
            // Show expired message when timeLeft <= 0
        }

        // Auto-start timer jika di step 2
        @if($step == 2)
        document.addEventListener('DOMContentLoaded', function() {
            // TODO: Start OTP timer when step 2 loads
            // startOtpTimer();
        });
        @endif

        // PIN input hanya angka
        document.addEventListener('DOMContentLoaded', function() {
            const pinInputs = ['pin', 'pin_confirmation'];
            pinInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function(e) {
                        e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    });
                }
            });
        });

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    const previewImg = document.getElementById(previewId + 'Img');
                    if (preview && previewImg) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Load saved form data
        document.addEventListener('DOMContentLoaded', function() {
            const savedData = sessionStorage.getItem('registerData');
            if (savedData) {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'file') {
                        input.value = data[key];
                    }
                });
            }
        });
    </script>
</body>
</html>

