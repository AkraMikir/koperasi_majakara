@extends('layouts.auth')

@section('title', 'Registrasi - Koperasi Majakara')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full">
            @php
                $step = $step ?? request()->get('step', 1);
                $subStep = $subStep ?? request()->get('substep', 1);
            @endphp

            @if($step == 1)
                <!-- Progress Indicator untuk Sub-step Step 1 -->
                <div class="mb-8 max-w-5xl mx-auto fade-in-up">
                    <!-- Logo -->
                    <div class="text-center mb-6">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Koperasi Majakara"
                            class="h-16 w-auto mx-auto mb-2">
                        <h2 class="text-2xl font-bold text-[#674c1d] font-display">Registrasi Nasabah Baru</h2>
                        <p class="text-gray-600 text-sm mt-1">Lengkapi semua langkah untuk melanjutkan</p>
                    </div>

                <!-- Progress Steps -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center justify-between">
                        @php
                        $subStepLabels = [
                            1 => 'Data Diri',
                            2 => 'Data KTP',
                            3 => 'Detail Nasabah',
                            4 => 'Pekerjaan',
                            5 => 'Rekening',
                            6 => 'Kontak Darurat'
                        ];
                        $subStepIcons = [
                            1 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                            2 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>',
                            3 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                            4 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
                            5 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
                            6 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>',
                        ];
                        @endphp
                        @for($i = 1; $i <= 6; $i++)
                        <div class="flex items-center {{ $i < 6 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center progress-step {{ $subStep == $i ? 'active' : '' }}">
                                <div class="relative">
                                    <!-- Circle with icon -->
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $subStep > $i ? 'bg-linear-to-br from-green-500 to-green-600' : ($subStep == $i ? 'bg-linear-to-br from-[#674c1d] to-[#8b6f2f]' : 'bg-gray-200') }} text-white transition-all duration-300 shadow-md {{ $subStep == $i ? 'ring-4 ring-[#d4af37]/30' : '' }}">
                                        @if($subStep > $i)
                                            <!-- Checkmark for completed -->
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <!-- Icon for current/upcoming -->
                                            <svg class="w-5 h-5 {{ $subStep < $i ? 'text-gray-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $subStepIcons[$i] !!}
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Step number badge -->
                                    @if($subStep >= $i)
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-[#d4af37] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ $i }}
                                    </div>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs text-center {{ $subStep >= $i ? 'text-[#674c1d] font-semibold' : 'text-gray-500' }} max-w-[90px] leading-tight">
                                    {{ $subStepLabels[$i] }}
                                </span>
                            </div>
                            @if($i < 6)
                            <!-- Progress line -->
                            <div class="flex-1 h-1 mx-2 rounded-full relative overflow-hidden bg-gray-200">
                                <div class="absolute inset-0 {{ $subStep > $i ? 'bg-linear-to-r from-green-500 to-green-600' : ($subStep == $i ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] w-1/2' : 'w-0') }} transition-all duration-500"></div>
                            </div>
                            @endif
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Card -->
            <div
                class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden max-w-6xl mx-auto border border-gray-200/50 fade-in-up">
                <div class="lg:grid lg:grid-cols-2">
                    <!-- Left Side - Form -->
                    <div class="p-8 lg:p-12">
                        <!-- Header with Icon -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-lg">
                                    @if($step == 1)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    @elseif($step == 2)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 font-display">
                                        @if($step == 1)
                                            Registrasi Akun
                                        @elseif($step == 2)
                                            Verifikasi Nomor HP
                                        @else
                                            Buat PIN
                                        @endif
                                    </h2>
                                    <p class="text-gray-600 text-sm">
                                        @if($step == 1)
                                            Langkah {{ $subStep }} dari 6
                                        @elseif($step == 2)
                                            Langkah 2 dari 3
                                        @else
                                            Langkah terakhir
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">
                                @if($step == 1)
                                    Lengkapi data dengan benar untuk mempercepat proses verifikasi
                                @elseif($step == 2)
                                    Kode OTP telah dikirim ke WhatsApp Anda
                                @else
                                    Buat PIN 6 digit untuk keamanan transaksi Anda
                                @endif
                            </p>
                        </div>

                {{-- Global alerts are handled by sweetalert2 component --}}

                        <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data"
                            id="registerForm" onsubmit="return validatePasswordSubmit(event)">
                            @csrf
                            <input type="hidden" name="step" value="{{ $step }}">
                            @if($step == 1)
                                <input type="hidden" name="substep" value="{{ $subStep }}">
                                {{-- Bawa data kritis Langkah 1 (Data Diri) di setiap submit substep 2–6 agar nomor HP tidak
                                hilang --}}
                                @if($subStep > 1)
                                    <input type="hidden" name="nama" value="{{ old('nama', $formData['nama'] ?? '') }}">
                                    <input type="hidden" name="email" value="{{ old('email', $formData['email'] ?? '') }}">
                                    <input type="hidden" name="nomor_hp"
                                        value="{{ old('nomor_hp', $formData['nomor_hp'] ?? '') }}">
                                @endif
                            @endif

                            @if($step == 1)
                                <!-- Step 1 dengan Sub-step -->
                                @if($subStep == 1)
                                    <!-- Sub-step 1: Data Diri -->
                                    <div class="space-y-6">
                                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Data Diri</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                                    Lengkap</label>
                                                <input type="text" name="nama" id="nama"
                                                    value="{{ old('nama', $formData['nama'] ?? '') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                    placeholder="Masukkan nama lengkap">
                                                @error('nama')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="email"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                                    <input type="email" name="email" id="email"
                                                        value="{{ old('email', $formData['email'] ?? '') }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="nama@email.com">
                                                    @error('email')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="nomor_hp"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                                        HP</label>
                                                    <input type="text" name="nomor_hp" id="nomor_hp"
                                                        value="{{ old('nomor_hp', $formData['nomor_hp'] ?? '') }}"
                                                        maxlength="20"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="08xxxxxxxxxx">
                                                    @error('nomor_hp')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="password"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                                    <div class="relative">
                                                        <input type="password" name="password" id="password"
                                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                            placeholder="Minimal 8 karakter" value="{{ old('password') }}">
                                                        <button type="button"
                                                            onclick="togglePassword('password', 'passwordToggle')"
                                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                                            <svg id="passwordToggle" class="w-5 h-5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @error('password')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="password_confirmation"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                                                        Password</label>
                                                    <div class="relative">
                                                        <input type="password" name="password_confirmation"
                                                            id="password_confirmation"
                                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                            placeholder="Ulangi password"
                                                            value="{{ old('password_confirmation') }}">
                                                        <button type="button"
                                                            onclick="togglePassword('password_confirmation', 'passwordConfirmationToggle')"
                                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                                            <svg id="passwordConfirmationToggle" class="w-5 h-5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @error('password_confirmation')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                            <div>
                                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <button type="button" onclick="openWebcam('foto', 'fotoPreview', 'Ambil Foto Profil')"
                                        class="w-full px-3 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-md transition-all text-xs font-semibold flex items-center justify-center gap-1.5">
                                        Buka Kamera
                                    </button>
                                    <!-- <label for="foto" class="w-full px-3 py-2 border border-[#674c1d] text-[#674c1d] hover:bg-amber-50/30 rounded-lg transition-all text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer text-center">
                                        Pilih File
                                    </label> -->
                                    <input type="file" name="foto" id="foto" accept="image/*" class="hidden"
                                        onchange="previewImage(this, 'fotoPreview')">
                                </div>
                                <div id="fotoPreview" class="mt-2 {{ !empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg' ? '' : 'hidden' }} relative inline-block">
                                    <img id="fotoPreviewImg" 
                                        src="{{ !empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg' ? asset('storage/' . $formData['foto']) : '' }}" 
                                        alt="Preview"
                                        class="h-20 w-20 rounded-lg object-cover">
                                    <button type="button" onclick="removeImage('foto', '', 'fotoPreview')" class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($subStep == 2)
                    <!-- Sub-step 2: Data KTP dengan OCR (Moved from Substep 5) -->
                    <div class="space-y-6 animate-fade-in">
                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Data KTP (dengan OCR)</h3>
                        
                        <!-- Notes untuk OCR KTP -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">📋 Petunjuk Foto KTP untuk OCR:</h4>
                            <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                                <li>Foto harus <strong>landscape</strong> (mendatar)</li>
                                <li>Foto harus <strong>jelas</strong> dan tidak gelap</li>
                                <li>Foto tidak boleh <strong>over pencahayaan</strong> (terlalu terang)</li>
                                <li><strong>Mohon di check ulang</strong> setelah OCR jika ada data yang tidak terprocess dengan benar</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ambil/Upload Foto KTP</label>

                                <!-- Camera/Upload Options -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <button type="button" onclick="openWebcam('file_ktp_upload', 'ktpPreview', 'Ambil Foto KTP (OCR)')"
                                        class="w-full px-2 py-3 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-all font-semibold flex items-center justify-center gap-2">
                                        <!-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg> -->
                                        Ambil dari Kamera
                                    </button>
                                    <label for="file_ktp_upload"
                                        class="w-full px-4 py-3 border-2 border-dashed border-gray-300 text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d] rounded-xl transition-all font-semibold cursor-pointer text-center flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Upload dari File
                                    </label>
                                </div>

                                <!-- Hidden file input for upload -->
                                <input type="file" name="file_ktp_upload" id="file_ktp_upload" accept="image/*"
                                    class="hidden" onchange="handleKtpUpload(this)">

                                <!-- Preview Area -->
                                <div class="mt-4">
                                    <div id="ktpPreview" class="hidden">
                                        <p class="text-sm text-gray-600 mb-2">Preview Foto KTP:</p>
                                        <div class="relative inline-block rounded-xl overflow-hidden border border-gray-200 p-1.5 bg-white">
                                            <img id="ktpPreviewImg" src="" alt="KTP Preview"
                                                class="max-w-full h-auto rounded-lg max-h-64 object-cover">
                                            <button type="button" onclick="removeKtpPhoto()"
                                                class="absolute top-3 right-3 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- OCR Button -->
                                    <div class="mt-4">
                                        <button type="button" onclick="processOcr()" id="btnOcr" disabled
                                            class="w-full px-6 py-3 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-all font-semibold disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg">
                                            <span id="ocrText">Proses OCR</span>
                                            <span id="ocrLoading" class="hidden flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Memproses OCR...
                                            </span>
                                        </button>
                                    </div>

                                    <div id="ocrResult" class="mt-4 hidden"></div>
                                </div>

                                <input type="hidden" name="file_ktp" id="file_ktp"
                                    value="{{ old('file_ktp', $formData['file_ktp'] ?? '') }}">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                    <input type="text" name="nik" id="nik"
                                        value="{{ old('nik', $formData['nik'] ?? '') }}" maxlength="16"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="16 digit NIK">
                                </div>

                                <div>
                                    <label for="nama_lengkap_ktp"
                                        class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap (Sesuai KTP)</label>
                                    <input type="text" name="nama_lengkap_ktp" id="nama_lengkap_ktp"
                                        value="{{ old('nama_lengkap_ktp', $formData['nama_lengkap_ktp'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Nama lengkap sesuai KTP">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tempat_lahir_ktp"
                                        class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir (Sesuai KTP)</label>
                                    <input type="text" name="tempat_lahir_ktp" id="tempat_lahir_ktp"
                                        value="{{ old('tempat_lahir_ktp', $formData['tempat_lahir_ktp'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Tempat lahir sesuai KTP">
                                </div>

                                <div>
                                    <label for="tanggal_lahir_ktp"
                                        class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir (Sesuai KTP)</label>
                                    <input type="date" name="tanggal_lahir_ktp" id="tanggal_lahir_ktp"
                                        value="{{ old('tanggal_lahir_ktp', $formData['tanggal_lahir_ktp'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="rt_rw" class="block text-sm font-medium text-gray-700 mb-2">RT / RW</label>
                                    <input type="text" name="rt_rw" id="rt_rw"
                                        value="{{ old('rt_rw', $formData['rt_rw'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Contoh: 001/002">
                                </div>

                                <div>
                                    <label for="kel_desa" class="block text-sm font-medium text-gray-700 mb-2">Kelurahan / Desa</label>
                                    <input type="text" name="kel_desa" id="kel_desa"
                                        value="{{ old('kel_desa', $formData['kel_desa'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Kelurahan atau Desa">
                                </div>

                                <div>
                                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="kecamatan"
                                        value="{{ old('kecamatan', $formData['kecamatan'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Kecamatan">
                                </div>
                            </div>

                            <div>
                                <label for="alamat_ktp" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Sesuai KTP)</label>
                                <textarea name="alamat_ktp" id="alamat_ktp" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                    placeholder="Alamat lengkap sesuai KTP">{{ old('alamat_ktp', $formData['alamat_ktp'] ?? '') }}</textarea>
                            </div>

                            <div>
                                <label for="jenis_kelamin_ktp" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin (Sesuai KTP)</label>
                                <select name="jenis_kelamin_ktp" id="jenis_kelamin_ktp"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki"
                                        {{ (old('jenis_kelamin_ktp', $formData['jenis_kelamin_ktp'] ?? '') == 'Laki-laki') ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ (old('jenis_kelamin_ktp', $formData['jenis_kelamin_ktp'] ?? '') == 'Perempuan') ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @elseif($subStep == 3)
                    <!-- Sub-step 3: Detail Nasabah (Moved from Substep 2, with foto_selfie) -->
                    <div class="space-y-6 animate-fade-in">
                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Detail Nasabah</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-2">Nomor KK</label>
                                <input type="text" name="no_kk" id="no_kk"
                                    value="{{ old('no_kk', $formData['no_kk'] ?? '') }}" maxlength="16"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                    placeholder="16 digit Nomor KK">
                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="tempat_lahir"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Tempat
                                                        Lahir</label>
                                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                                        value="{{ old('tempat_lahir', $formData['tempat_lahir'] ?? '') }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="Kota kelahiran">
                                                </div>

                                                <div>
                                                    <label for="tanggal_lahir"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                                        Lahir</label>
                                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                        value="{{ old('tanggal_lahir', $formData['tanggal_lahir'] ?? '') }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="jenis_kelamin"
                                                    class="block text-sm font-medium text-gray-700 mb-2">Jenis
                                                    Kelamin</label>
                                                <select name="jenis_kelamin" id="jenis_kelamin"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="L" {{ (old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="P" {{ (old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                                    Lengkap</label>
                                                <textarea name="alamat" id="alamat" rows="3"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                                    placeholder="Alamat lengkap tempat tinggal">{{ old('alamat', $formData['alamat'] ?? '') }}</textarea>
                                            </div>

                                            <div>
                                                <label for="alamat_domisili" class="block text-sm font-medium text-gray-700 mb-2">Alamat Domisili</label>
                                                <textarea name="alamat_domisili" id="alamat_domisili" rows="3"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                                    placeholder="Alamat domisili saat ini (isi sama dengan KTP jika sesuai KTP)">{{ old('alamat_domisili', $formData['alamat_domisili'] ?? '') }}</textarea>
                                            </div>

                                            <div>
                                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                                <input type="text" name="kode_pos" id="kode_pos"
                                                    value="{{ old('kode_pos', $formData['kode_pos'] ?? '') }}"
                                                    maxlength="5" pattern="[0-9]{5}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                    placeholder="5 digit Kode Pos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>

                            <!-- Upload section: 3 documents -->
                            <div class="space-y-6 pt-4 border-t border-gray-100">
                                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Unggah Dokumen Nasabah</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Foto KTP -->
                                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide min-h-[36px] flex items-center">1. Foto KTP <span class="text-red-500">*</span></label>
                                        <div class="flex flex-col gap-2">
                                            <button type="button" onclick="openWebcam('foto_ktp_upload', 'fotoKtpPreview', 'Ambil Foto KTP')"
                                                class="w-full px-3 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-md transition-all text-xs font-semibold flex items-center justify-center gap-1.5">
                                                <!-- <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg> -->
                                                Buka Kamera
                                            </button>
                                            <label for="foto_ktp_upload" class="w-full px-3 py-2 border border-[#674c1d] text-[#674c1d] hover:bg-amber-50/30 rounded-lg transition-all text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer text-center">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Pilih File
                                            </label>
                                            <input type="file" name="foto_ktp_upload" id="foto_ktp_upload" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKtpPreview')">
                                            <input type="hidden" name="foto_ktp" id="foto_ktp" value="{{ old('foto_ktp', $formData['foto_ktp'] ?? '') }}">
                                        </div>
                                        <div id="fotoKtpPreview" class="{{ !empty($formData['foto_ktp']) ? '' : 'hidden' }} relative mt-3 rounded-lg overflow-hidden border border-gray-200 p-1.5 bg-white">
                                            <img id="fotoKtpPreviewImg" src="{{ !empty($formData['foto_ktp']) ? asset('storage/' . $formData['foto_ktp']) : '' }}" alt="KTP Preview" class="h-24 w-full object-cover rounded">
                                            <button type="button" onclick="removeImage('foto_ktp_upload', 'foto_ktp', 'fotoKtpPreview')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Foto KK -->
                                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide min-h-[36px] flex items-center">2. Foto KK <span class="text-red-500">*</span></label>
                                        <div class="flex flex-col gap-2">
                                            <button type="button" onclick="openWebcam('foto_kk_upload', 'fotoKkPreview', 'Ambil Foto KK')"
                                                class="w-full px-3 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-md transition-all text-xs font-semibold flex items-center justify-center gap-1.5">
                                                <!-- <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg> -->
                                                Buka Kamera
                                            </button>
                                            <label for="foto_kk_upload" class="w-full px-3 py-2 border border-[#674c1d] text-[#674c1d] hover:bg-amber-50/30 rounded-lg transition-all text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer text-center">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Pilih File
                                            </label>
                                            <input type="file" name="foto_kk_upload" id="foto_kk_upload" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKkPreview')">
                                            <input type="hidden" name="foto_kk" id="foto_kk" value="{{ old('foto_kk', $formData['foto_kk'] ?? '') }}">
                                        </div>
                                        <div id="fotoKkPreview" class="{{ !empty($formData['foto_kk']) ? '' : 'hidden' }} relative mt-3 rounded-lg overflow-hidden border border-gray-200 p-1.5 bg-white">
                                            <img id="fotoKkPreviewImg" src="{{ !empty($formData['foto_kk']) ? asset('storage/' . $formData['foto_kk']) : '' }}" alt="KK Preview" class="h-24 w-full object-cover rounded">
                                            <button type="button" onclick="removeImage('foto_kk_upload', 'foto_kk', 'fotoKkPreview')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Foto Selfie Holding KTP -->
                                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide min-h-[36px] flex items-center">3. Selfie Pegang KTP <span class="text-red-500">*</span></label>
                                        <div class="flex flex-col gap-2">
                                            <button type="button" onclick="openWebcam('foto_selfie_upload', 'fotoSelfiePreview', 'Ambil Foto Selfie')"
                                                class="w-full px-3 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-md transition-all text-xs font-semibold flex items-center justify-center gap-1.5">
                                                <!-- <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg> -->
                                                Buka Kamera
                                            </button>
                                            <label for="foto_selfie_upload" class="w-full px-3 py-2 border border-[#674c1d] text-[#674c1d] hover:bg-amber-50/30 rounded-lg transition-all text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer text-center">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Pilih File
                                            </label>
                                            <input type="file" name="foto_selfie_upload" id="foto_selfie_upload" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoSelfiePreview')">
                                            <input type="hidden" name="foto_selfie" id="foto_selfie" value="{{ old('foto_selfie', $formData['foto_selfie'] ?? '') }}">
                                        </div>
                                        <div id="fotoSelfiePreview" class="{{ !empty($formData['foto_selfie']) ? '' : 'hidden' }} relative mt-3 rounded-lg overflow-hidden border border-gray-200 p-1.5 bg-white">
                                            <img id="fotoSelfiePreviewImg" src="{{ !empty($formData['foto_selfie']) ? asset('storage/' . $formData['foto_selfie']) : '' }}" alt="Selfie Preview" class="h-24 w-full object-cover rounded">
                                            <button type="button" onclick="removeImage('foto_selfie_upload', 'foto_selfie', 'fotoSelfiePreview')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif($subStep == 4)
                    <!-- Sub-step 4: Data Pekerjaan (Moved from Substep 3) -->
                    <div class="space-y-6 animate-fade-in">
                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Data Pekerjaan</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="pekerjaan"
                                    class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan"
                                    value="{{ old('pekerjaan', $formData['pekerjaan'] ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                    placeholder="Nama pekerjaan">
                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="penghasilan"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Penghasilan</label>
                                                    <select name="penghasilan" id="penghasilan"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                                        <option value="">Pilih Range Penghasilan</option>
                                                        <option value="< Rp1.000.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == '< Rp1.000.000') ? 'selected' : '' }}>
                                                            < Rp1.000.000</option>
                                                        <option value="Rp1.000.000 – Rp2.500.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == 'Rp1.000.000 – Rp2.500.000') ? 'selected' : '' }}>
                                                            Rp1.000.000 – Rp2.500.000</option>
                                                        <option value="Rp2.500.000 – Rp5.000.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == 'Rp2.500.000 – Rp5.000.000') ? 'selected' : '' }}>
                                                            Rp2.500.000 – Rp5.000.000</option>
                                                        <option value="Rp5.000.000 – Rp7.500.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == 'Rp5.000.000 – Rp7.500.000') ? 'selected' : '' }}>
                                                            Rp5.000.000 – Rp7.500.000</option>
                                                        <option value="Rp7.500.000 – Rp10.000.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == 'Rp7.500.000 – Rp10.000.000') ? 'selected' : '' }}>
                                                            Rp7.500.000 – Rp10.000.000</option>
                                                        <option value="Rp10.000.000 – Rp15.000.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == 'Rp10.000.000 – Rp15.000.000') ? 'selected' : '' }}>
                                                            Rp10.000.000 – Rp15.000.000</option>
                                                        <option value=">Rp15.000.000" {{ (old('penghasilan', $formData['penghasilan'] ?? '') == '>Rp15.000.000') ? 'selected' : '' }}>
                                                            >Rp15.000.000</option>
                                                    </select>
                                                </div>

                                <div>
                                    <label for="nama_perusahaan"
                                        class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                                    <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                                        value="{{ old('nama_perusahaan', $formData['nama_perusahaan'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Nama perusahaan">
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif($subStep == 5)
                    <!-- Sub-step 5: Data Rekening Bank (Moved from Substep 4) -->
                    <div class="space-y-6 animate-fade-in">
                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Data Rekening Bank </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="no_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                    Rekening</label>
                                <input type="text" name="no_rekening" id="no_rekening"
                                    value="{{ old('no_rekening', $formData['no_rekening'] ?? '') }}" maxlength="16"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                    placeholder="16 digit nomor rekening" pattern="[0-9]*" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_pemilik_rekening"
                                        class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                    <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening"
                                        value="{{ old('nama_pemilik_rekening', $formData['nama_pemilik_rekening'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Nama sesuai rekening">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bank</label>
                                    <input type="hidden" name="jenis_atm" id="jenis_atm" value="{{ old('jenis_atm', $formData['jenis_atm'] ?? '') }}">
                                    
                                    <div class="relative" id="bank_dropdown_wrapper">
                                        <button type="button" id="bank_dropdown_trigger" onclick="toggleBankDropdown()"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none flex justify-between items-center text-left">
                                            <span id="bank_dropdown_trigger_text" class="text-gray-500">Pilih Bank</span>
                                            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" id="bank_dropdown_arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Content -->
                                        <div id="bank_dropdown_content" 
                                            class="hidden absolute z-30 mt-1 w-full rounded-lg bg-white shadow-xl border border-gray-100 max-h-72 flex flex-col transition-all duration-200">
                                            <!-- Search Input -->
                                            <div class="p-2 border-b border-gray-100">
                                                <div class="relative">
                                                    <input type="text" id="bank_search" oninput="filterBanks()" placeholder="Cari bank..." 
                                                        class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d]">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Options List -->
                                            <div class="overflow-y-auto flex-1 max-h-56 divide-y divide-gray-50 py-1" id="bank_options_list">
                                                <button type="button" onclick="selectBank('Lainnya')"
                                                    class="bank-option w-full text-left px-4 py-2.5 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors duration-150 flex items-center justify-between border-b border-gray-100"
                                                    data-bank-name="lainnya">
                                                    <span>Lainnya</span>
                                                </button>
                                                @if(isset($banks) && count($banks) > 0)
                                                    @foreach($banks as $bank)
                                                        <button type="button" onclick="selectBank('{{ $bank->nama_bank }}')"
                                                            class="bank-option w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-[#674c1d] transition-colors duration-150 flex items-center justify-between"
                                                            data-bank-name="{{ strtolower($bank->nama_bank) }}">
                                                            <span>{{ $bank->nama_bank }}</span>
                                                        </button>
                                                    @endforeach
                                                @else
                                                    <!-- Fallback static banks if DB not populated -->
                                                    @foreach(['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI', 'BTN'] as $bankName)
                                                        <button type="button" onclick="selectBank('{{ $bankName }}')"
                                                            class="bank-option w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-[#674c1d] transition-colors duration-150 flex items-center justify-between"
                                                            data-bank-name="{{ strtolower($bankName) }}">
                                                            <span>{{ $bankName }}</span>
                                                        </button>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div id="custom_bank_container" class="hidden mt-3">
                                        <label for="jenis_atm_lainnya" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank (Tulis Sendiri)</label>
                                        <input type="text" id="jenis_atm_lainnya" oninput="updateCustomBank(this.value)"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Masukkan nama bank Anda">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($subStep == 6)
                    <!-- Sub-step 6: Kontak Darurat -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-[#674c1d] mb-4">Kontak Darurat (Optional)</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="darurat_nama_lengkap"
                                        class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="darurat_nama_lengkap" id="darurat_nama_lengkap"
                                        value="{{ old('darurat_nama_lengkap', $formData['darurat_nama_lengkap'] ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Nama lengkap kontak darurat">
                                </div>

                                                <div>
                                                    <label for="hubungan_peminjam"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Hubungan</label>
                                                    <select name="hubungan_peminjam" id="hubungan_peminjam"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                                        <option value="">Pilih Hubungan</option>
                                                        <option value="Suami/Istri" {{ (old('hubungan_peminjam', $formData['hubungan_peminjam'] ?? '') == 'Suami/Istri') ? 'selected' : '' }}>
                                                            Suami/Istri</option>
                                                        <option value="Orang Tua" {{ (old('hubungan_peminjam', $formData['hubungan_peminjam'] ?? '') == 'Orang Tua') ? 'selected' : '' }}>
                                                            Orang Tua</option>
                                                        <option value="Anak" {{ (old('hubungan_peminjam', $formData['hubungan_peminjam'] ?? '') == 'Anak') ? 'selected' : '' }}>
                                                            Anak</option>
                                                        <option value="Saudara" {{ (old('hubungan_peminjam', $formData['hubungan_peminjam'] ?? '') == 'Saudara') ? 'selected' : '' }}>
                                                            Saudara</option>
                                                        <option value="Lainnya" {{ (old('hubungan_peminjam', $formData['hubungan_peminjam'] ?? '') == 'Lainnya') ? 'selected' : '' }}>
                                                            Lainnya</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="darurat_no_telepon"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                                        Telepon</label>
                                                    <input type="text" name="darurat_no_telepon" id="darurat_no_telepon"
                                                        value="{{ old('darurat_no_telepon', $formData['darurat_no_telepon'] ?? '') }}"
                                                        maxlength="20"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="081234567890">
                                                </div>

                                                <div>
                                                    <label for="darurat_email"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                                    <input type="email" name="darurat_email" id="darurat_email"
                                                        value="{{ old('darurat_email', $formData['darurat_email'] ?? '') }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="email@example.com">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="darurat_alamat"
                                                    class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                                <textarea name="darurat_alamat" id="darurat_alamat" rows="3"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                                    placeholder="Alamat lengkap">{{ old('darurat_alamat', $formData['darurat_alamat'] ?? '') }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="darurat_pekerjaan"
                                                        class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                                    <input type="text" name="darurat_pekerjaan" id="darurat_pekerjaan"
                                                        value="{{ old('darurat_pekerjaan', $formData['darurat_pekerjaan'] ?? '') }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="Pekerjaan kontak darurat">
                                                </div>

                                                <div>
                                                    <label for="darurat_no_ktp"
                                                        class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                                    <input type="text" name="darurat_no_ktp" id="darurat_no_ktp"
                                                        value="{{ old('darurat_no_ktp', $formData['darurat_no_ktp'] ?? '') }}"
                                                        maxlength="16"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                        placeholder="16 digit NIK">
                                                </div>
                                            </div>

                            <div>
                                <label for="darurat_foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">Foto
                                    KTP </label>
                                <label for="darurat_foto_ktp" class="cursor-pointer">
                                    <div
                                        class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                        <span class="text-sm text-gray-600">Upload Foto KTP</span>
                                    </div>
                                    <input type="file" name="darurat_foto_ktp" id="darurat_foto_ktp" accept="image/*"
                                        class="hidden" onchange="previewImage(this, 'daruratKtpPreview')">
                                </label>
                                <div id="daruratKtpPreview" class="mt-2 {{ !empty($formData['darurat_foto_ktp']) ? '' : 'hidden' }}">
                                    <img id="daruratKtpPreviewImg" 
                                        src="{{ !empty($formData['darurat_foto_ktp']) ? asset('storage/' . $formData['darurat_foto_ktp']) : '' }}" 
                                        alt="Preview"
                                        class="h-20 w-20 rounded-lg object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @elseif($step == 2)
                    {{-- Step 2: OTP Verification --}}
                    <div class="space-y-6">
                        {{-- OTP step 2 alerts handled by SweetAlert2 --}}

                                    {{-- STATE 1: Belum kirim OTP - Tampilkan konfirmasi nomor --}}
                                    @if(!($otpSent ?? false))
                                        <div class="text-center space-y-6">
                                            {{-- WhatsApp Icon with Animation --}}
                                            <div class="flex justify-center">
                                                <div
                                                    class="w-24 h-24 bg-linear-to-br from-green-100 to-green-50 rounded-full flex items-center justify-center shadow-lg animate-bounce-slow">
                                                    <svg class="w-12 h-12 text-green-600" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Confirmation Text --}}
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                    Verifikasi Nomor WhatsApp
                                                </h3>
                                                <p class="text-gray-600 mb-4">
                                                    Kode OTP akan dikirim ke nomor WhatsApp:
                                                </p>
                                                <div
                                                    class="inline-block bg-linear-to-r from-green-50 to-green-100 border-2 border-green-500 rounded-xl px-8 py-4 shadow-md">
                                                    <p class="text-3xl font-bold text-green-700 tracking-wide">
                                                        {{ session('register_phone') ?? $phone ?? 'Nomor HP tidak tersimpan — kembali ke Langkah 1 (Data Diri) dan isi nomor HP' }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Info --}}
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                                                <p class="text-sm text-blue-800 font-semibold mb-2">
                                                    📱 Pastikan:
                                                </p>
                                                <ul class="text-sm text-blue-700 space-y-1.5 list-none">
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-blue-500 shrink-0">✓</span>
                                                        <span>Nomor WhatsApp Anda <strong>aktif</strong> dan dapat menerima
                                                            pesan</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-blue-500 shrink-0">✓</span>
                                                        <span>Aplikasi WhatsApp sudah terinstal di HP Anda</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-blue-500 shrink-0">✓</span>
                                                        <span>Nomor di atas sudah <strong>benar</strong></span>
                                                    </li>
                                                </ul>
                                            </div>

                                            {{-- Hidden input: tombol yang disabled tidak ikut dikirim, jadi kita set send_otp=1
                                            lewat input ini saat klik --}}
                                            <input type="hidden" name="send_otp" id="send_otp_input" value="0">
                                            {{-- Button Kirim OTP with Loading State --}}
                                            <div class="mt-6">
                                                <button type="button" name="send_otp_btn" value="1" id="btnSendOtp"
                                                    onclick="setSendOtpAndLoading(this); return false;"
                                                    class="w-full px-6 py-4 bg-linear-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all font-bold text-lg flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0">
                                                    <svg class="w-6 h-6" id="iconSend" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                    </svg>
                                                    <svg class="w-6 h-6 animate-spin hidden" id="iconLoading" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span id="textSendOtp">Kirim Kode OTP ke WhatsApp</span>
                                                </button>
                                            </div>

                                            {{-- Link Kembali --}}
                                            <div class="mt-4">
                                                <a href="{{ route('register', ['step' => 1, 'substep' => 1]) }}"
                                                    class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-[#674c1d] hover:underline transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                    Nomor salah? Kembali ke Step 1 untuk mengubah
                                                </a>
                                            </div>
                                        </div>

                                        {{-- STATE 2: OTP sudah dikirim - Tampilkan form input OTP --}}
                                    @else
                                        <div class="space-y-6">
                                            {{-- Info OTP Terkirim --}}
                                            <div
                                                class="bg-linear-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 shadow-sm">
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm text-green-900 font-bold">
                                                            Kode OTP Berhasil Dikirim! 🎉
                                                        </p>
                                                        <p class="text-xs text-green-800 mt-1">
                                                            Nomor WhatsApp: <strong
                                                                class="font-semibold">{{ session('register_phone') ?? $phone ?? 'Nomor HP tidak tersimpan — kembali ke Langkah 1 (Data Diri) dan isi nomor HP' }}</strong>
                                                        </p>
                                                        <p class="text-xs text-green-700 mt-2 flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Buka aplikasi WhatsApp Anda dan masukkan 6 digit kode OTP di bawah
                                                            ini
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Input OTP - 6 Boxes Style --}}
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                                                    Masukkan Kode OTP <span class="text-red-500">*</span>
                                                </label>

                                                {{-- Hidden input untuk form submission --}}
                                                <input type="hidden" name="otp_code" id="otp_code_hidden" required>

                                                {{-- 6 OTP Input Boxes --}}
                                                <div class="flex justify-center gap-2 sm:gap-3" id="otpBoxes">
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="0">
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="1">
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="2">
                                                    <span class="flex items-center text-2xl text-gray-400 font-bold">-</span>
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="3">
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="4">
                                                    <input type="text" maxlength="1"
                                                        class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-lg focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d] transition-all outline-none"
                                                        inputmode="numeric" pattern="[0-9]" autocomplete="off" data-index="5">
                                                </div>

                                                @error('otp_code')
                                                    <p class="mt-3 text-sm text-red-600 text-center font-medium">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Expiry Timer --}}
                                            <div class="text-center">
                                                <div
                                                    class="inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-sm text-yellow-800">
                                                        Kode berlaku: <strong id="expiryTimer"
                                                            class="font-bold text-yellow-900">1:00</strong>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Button Kirim Ulang --}}
                                            <div class="flex items-center justify-center">
                                                @if(($remainingCooldown ?? 0) > 0)
                                                    <button type="button" disabled
                                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed border border-gray-200">
                                                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        <span class="text-sm font-medium">
                                                            Tunggu <span id="cooldownTimer"
                                                                class="font-bold">{{ min(60, (int) ($remainingCooldown ?? 0)) }}</span>
                                                            detik
                                                        </span>
                                                    </button>
                                                @else
                                                    <button type="submit" name="send_otp" value="1"
                                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium border border-[#674c1d] shadow-sm hover:shadow-md">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        <span class="text-sm">Kirim Ulang Kode OTP</span>
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Info Bantuan --}}
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <div class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <div class="text-xs text-blue-700">
                                                        <p class="font-semibold mb-1">💡 Tips:</p>
                                                        <ul class="space-y-1 list-disc list-inside">
                                                            <li>Pastikan koneksi internet Anda stabil</li>
                                                            <li>Cek folder <strong>Spam/Archive</strong> di WhatsApp</li>
                                                            <li>Tunggu 1 menit untuk kirim ulang</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                    @elseif($step == 3)
                    <!-- Step 3: Create PIN -->
                    <div class="space-y-6">
                        {{-- PIN alerts handled by SweetAlert2 --}}

                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Buat PIN 6 digit</strong><br>
                                            PIN ini akan digunakan untuk keamanan transaksi Anda. Pastikan PIN mudah diingat
                                            namun
                                            sulit ditebak.
                                        </p>
                                    </div>

                                    <div>
                                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN (6
                                            digit)</label>
                                        <input type="password" name="pin" id="pin" maxlength="6" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                                            placeholder="000000" autocomplete="off" inputmode="numeric">
                                        @error('pin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="pin_confirmation"
                                            class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi PIN</label>
                                        <input type="password" name="pin_confirmation" id="pin_confirmation" maxlength="6"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                                            placeholder="000000" autocomplete="off" inputmode="numeric">
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-xs text-gray-600">
                                            <strong>Catatan:</strong> Setelah PIN dibuat, semua data Anda akan dipindahkan
                                            ke sistem
                                            dan akun Anda akan aktif. Pastikan semua data sudah benar sebelum melanjutkan.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="mt-8 flex justify-between">
                                @if($step == 1)
                                    @if($subStep > 1)
                                        <a href="{{ route('register', ['step' => 1, 'substep' => $subStep - 1]) }}"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                            Kembali
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                            Kembali ke Login
                                        </a>
                                    @endif
                                @elseif($step > 1)
                                    <a href="{{ route('register', ['step' => $step - 1]) }}"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                        Kembali
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                                        Kembali ke Login
                                    </a>
                                @endif

                                @if($step == 1)
                                    <button type="submit"
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        @if($subStep < 6) Simpan & Lanjutkan @else Simpan & Lanjutkan ke OTP @endif
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

                    <!-- Right Side - Illustration -->
                    <div
                        class="hidden lg:flex gradient-animate p-12 items-center justify-center relative overflow-hidden">
                        <!-- Decorative circles -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl">
                        </div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24 blur-3xl">
                        </div>

                        <div class="relative z-10 text-center text-white">
                            <!-- Logo with animation -->
                            <div class="mb-8 float-animation">
                                <div
                                    class="w-64 h-64 mx-auto bg-white/10 rounded-3xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-2xl">
                                    <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara"
                                        class="w-48 h-auto">
                                </div>
                            </div>

                            <h3 class="text-3xl font-bold mb-3 font-display">Bergabung Bersama Kami</h3>
                            <p class="text-white/90 text-lg mb-8">Mulai perjalanan keuangan Anda</p>

                            <!-- Step Indicators -->
                            @if($step == 1)
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20 mb-6">
                                    <h4 class="font-semibold mb-4">Proses Registrasi:</h4>
                                    <div class="space-y-3 text-left">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                                <span class="font-bold">1</span>
                                            </div>
                                            <div class="text-sm">
                                                <p class="font-semibold">Lengkapi Data</p>
                                                <p class="text-white/70 text-xs">6 langkah data pribadi</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                                <span class="font-bold">2</span>
                                            </div>
                                            <div class="text-sm">
                                                <p class="font-semibold">Verifikasi OTP</p>
                                                <p class="text-white/70 text-xs">Via WhatsApp</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                                <span class="font-bold">3</span>
                                            </div>
                                            <div class="text-sm">
                                                <p class="font-semibold">Buat PIN</p>
                                                <p class="text-white/70 text-xs">Keamanan transaksi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($step == 2)
                                <!-- OTP Step Illustration -->
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                    <svg class="w-24 h-24 mx-auto mb-4 text-white animate-bounce-slow" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Periksa WhatsApp Anda untuk mendapatkan kode verifikasi 6 digit</p>
                                </div>
                            @else
                                <!-- PIN Step Illustration -->
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                    <svg class="w-24 h-24 mx-auto mb-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">PIN akan digunakan untuk verifikasi setiap transaksi Anda</p>
                                </div>
                            @endif

                            <!-- Benefits -->
                            <div class="mt-6 space-y-3 text-left">
                                <div
                                    class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium">Data Anda Aman & Terenkripsi</p>
                                </div>
                                <div
                                    class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Proses Cepat & Mudah</p>
                                </div>
                                <div
                                    class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium">Gratis Tanpa Biaya Pendaftaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-8 fade-in-up">
                <div class="relative inline-block">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-linear-to-br from-gray-50 via-amber-50/30 to-gray-50 text-gray-500">Sudah
                            punya akun?</span>
                    </div>
                </div>
                <a href="{{ route('login') }}"
                    class="mt-4 inline-flex items-center gap-2 text-[#674c1d] font-semibold hover:text-[#8b6f2f] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Masuk ke Akun Saya
                </a>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-6 text-gray-600 text-sm">
                <p>© 2026 Koperasi Majakara. Semua data Anda dilindungi.</p>
            </div>
        </div>
    </div>

    <!-- Camera Preview Modal -->
    <div id="cameraModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 max-w-md w-full border border-white/20 shadow-2xl relative">
            <div class="flex justify-between items-center mb-4">
                <h3 id="cameraModalTitle" class="text-lg font-bold text-[#674c1d]">Ambil Foto</h3>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="switchCamera()" id="btnSwitchCamera"
                        class="text-gray-500 hover:text-[#674c1d] transition-colors p-1.5 rounded-full hover:bg-gray-100/50 hidden"
                        title="Ganti Kamera">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="closeCamera()"
                        class="text-gray-500 hover:text-[#674c1d] transition-colors p-1.5 rounded-full hover:bg-gray-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="relative rounded-xl overflow-hidden bg-black/5 aspect-video mb-4 flex items-center justify-center">
                <video id="cameraVideo" autoplay playsinline
                    class="w-full h-full object-cover rounded-xl hidden"></video>
                <canvas id="cameraCanvas" class="hidden"></canvas>

                <!-- KTP Grid Overlay -->
                <div id="cameraKtpOverlay" class="absolute inset-0 pointer-events-none hidden flex items-center justify-center">
                    <!-- Semi-transparent overlay with a clear cutout in the middle for KTP card format (~85.6mm x 53.98mm, ratio 1.58) -->
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="relative z-10 w-[85%] aspect-[1.58/1] border-2 border-dashed border-white rounded-lg shadow-[0_0_0_9999px_rgba(0,0,0,0.5)] flex p-3 box-border justify-between items-stretch">
                        <!-- Corner markers -->
                        <div class="absolute -top-1.5 -left-1.5 w-4 h-4 border-t-4 border-l-4 border-yellow-400"></div>
                        <div class="absolute -top-1.5 -right-1.5 w-4 h-4 border-t-4 border-r-4 border-yellow-400"></div>
                        <div class="absolute -bottom-1.5 -left-1.5 w-4 h-4 border-b-4 border-l-4 border-yellow-400"></div>
                        <div class="absolute -bottom-1.5 -right-1.5 w-4 h-4 border-b-4 border-r-4 border-yellow-400"></div>
                        
                        <!-- Left Side: Text Guidelines -->
                        <div class="w-[60%] flex flex-col justify-center gap-2.5 opacity-40">
                            <!-- NIK line -->
                            <div class="h-3 bg-white/60 rounded-xs w-[85%]"></div>
                            <!-- Detail lines -->
                            <div class="h-2 bg-white/40 rounded-xs w-[65%]"></div>
                            <div class="h-2 bg-white/40 rounded-xs w-[75%]"></div>
                            <div class="h-2 bg-white/40 rounded-xs w-[70%]"></div>
                            <div class="h-2 bg-white/40 rounded-xs w-[50%]"></div>
                        </div>

                        <!-- Right Side: Photo Guideline -->
                        <div class="w-[30%] flex items-center justify-center">
                            <div class="w-full aspect-[3/4] border border-dashed border-white/60 rounded flex flex-col items-center justify-center gap-1 bg-white/5 opacity-55">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-[8px] text-white font-semibold">FOTO KTP</span>
                            </div>
                        </div>

                        <!-- Instruction text inside the box -->
                        <div class="absolute inset-0 flex items-end justify-center pb-2">
                            <span class="text-[10px] text-white bg-black/75 px-2.5 py-1 rounded-full font-medium text-center shadow-md">Posisikan KTP di dalam bingkai</span>
                        </div>
                    </div>
                </div>

                <div id="cameraPreview" class="w-full h-full hidden">
                    <img id="cameraPreviewImg" src="" alt="Preview"
                        class="w-full h-full object-cover rounded-xl border border-gray-200">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="capturePhoto()" id="btnCapture"
                    class="flex-1 px-4 py-3 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-all font-semibold flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ambil Foto
                </button>
                <button type="button" onclick="retakePhoto()" id="btnRetake"
                    class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold hidden flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z"></path>
                    </svg>
                    Ambil Ulang
                </button>
                <button type="button" onclick="usePhoto()" id="btnUsePhoto"
                    class="flex-1 px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-semibold hidden flex items-center justify-center gap-2 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Gunakan Foto
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function validatePasswordSubmit(e) {
            // Check if we are currently on Step 1, Substep 1 (where password inputs are visible)
            const passwordInput = document.getElementById('password');
            if (!passwordInput) {
                return true; // Let form submit if password field doesn't exist on this substep
            }

            const password = passwordInput.value;
            const errors = [];

            // 1. Minimal 8 karakter
            if (password.length < 8) {
                errors.push('Minimal 8 karakter');
            }
            // 2. Harus ada huruf besar
            if (!/[A-Z]/.test(password)) {
                errors.push('Harus mengandung minimal 1 huruf kapital (A-Z)');
            }
            // 3. Harus ada karakter spesial
            if (!/[!@#$%^&*(),.?":{}|<>_]/.test(password)) {
                errors.push('Harus mengandung minimal 1 karakter spesial (contoh: @, ., #, $, %, dll)');
            }

            if (errors.length > 0) {
                e.preventDefault();
                
                // Construct HTML list for SweetAlert
                let htmlErrors = '<ul class="text-left list-disc list-inside text-red-600 space-y-1 mt-2">';
                errors.forEach(function(error) {
                    htmlErrors += '<li>' + error + '</li>';
                });
                htmlErrors += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Keamanan Password Lemah',
                    html: '<div class="text-gray-700 text-sm">Password Anda harus memenuhi kriteria berikut:' + htmlErrors + '</div>',
                    confirmButtonColor: '#674c1d',
                    confirmButtonText: 'Perbaiki'
                });

                return false;
            }

            return true;
        }

        function goToStep(step, substep = null) {
            // Convert step to number
            step = parseInt(step);

            // Validate step (1-3)
            if (step < 1 || step > 3) {
                console.error('Invalid step:', step);
                return;
            }

            // Build URL
            let url = '{{ route("register") }}?step=' + step;
            if (step == 1 && substep) {
                url += '&substep=' + substep;
            }

            // Redirect ke step
            window.location.href = url;
        }

    // Camera variables
    let cameraStream = null;
    let capturedPhotoBlob = null;
    let activeInputId = null;
    let activePreviewId = null;
    let currentFacingMode = 'environment'; // Default to back camera for document capture

    // Open Camera Webcam
    function openWebcam(inputId, previewId, title) {
        // Check if browser supports camera
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showErrorAlert(
                'Browser Anda tidak mendukung akses kamera. Silakan gunakan opsi Upload dari File atau gunakan browser modern seperti Chrome, Firefox, atau Safari.'
            );
            return;
        }

        activeInputId = inputId;
        activePreviewId = previewId;

        // Set default facing mode based on capture type: selfie vs document
        if (inputId === 'foto' || inputId === 'foto_selfie_upload') {
            currentFacingMode = 'user'; // Camera selfie
        } else {
            currentFacingMode = 'environment'; // Camera belakang untuk KTP/KK
        }

        const modal = document.getElementById('cameraModal');
        const video = document.getElementById('cameraVideo');
        const btnCapture = document.getElementById('btnCapture');
        const btnRetake = document.getElementById('btnRetake');
        const btnUsePhoto = document.getElementById('btnUsePhoto');
        const preview = document.getElementById('cameraPreview');
        const titleEl = document.getElementById('cameraModalTitle');
        const btnSwitchCamera = document.getElementById('btnSwitchCamera');
        const ktpOverlay = document.getElementById('cameraKtpOverlay');

        if (titleEl) {
            titleEl.textContent = title;
        }

        modal.classList.remove('hidden');
        preview.classList.add('hidden');
        btnRetake.classList.add('hidden');
        btnUsePhoto.classList.add('hidden');
        btnCapture.classList.remove('hidden');

        // Show KTP Overlay only for KTP uploads
        if (ktpOverlay) {
            if (inputId === 'file_ktp_upload' || inputId === 'foto_ktp_upload') {
                ktpOverlay.classList.remove('hidden');
            } else {
                ktpOverlay.classList.add('hidden');
            }
        }

        // Check if device has multiple cameras to show switch button
        if (btnSwitchCamera) {
            btnSwitchCamera.classList.add('hidden'); // Hide by default
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    if (videoDevices.length > 1) {
                        btnSwitchCamera.classList.remove('hidden');
                    }
                })
                .catch(function(err) {
                    console.warn('Error listing media devices:', err);
                });
        }

        startCameraStream();
    }

    function startCameraStream() {
        const video = document.getElementById('cameraVideo');

        // Stop current stream if running
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
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
            showErrorAlert(errorMsg);
            closeCamera();
        });
    }

    function switchCamera() {
        // Toggle facing mode
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCameraStream();
    }

    // Close Camera
    function closeCamera() {
        const modal = document.getElementById('cameraModal');
        const video = document.getElementById('cameraVideo');
        const preview = document.getElementById('cameraPreview');
        const btnCapture = document.getElementById('btnCapture');
        const btnRetake = document.getElementById('btnRetake');
        const btnUsePhoto = document.getElementById('btnUsePhoto');
        const btnSwitchCamera = document.getElementById('btnSwitchCamera');

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
        if (btnSwitchCamera) {
            btnSwitchCamera.classList.add('hidden');
        }
        modal.classList.add('hidden');
        capturedPhotoBlob = null;
        activeInputId = null;
        activePreviewId = null;
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
            canvas.toBlob(function (blob) {
                capturedPhotoBlob = blob;
                const url = URL.createObjectURL(blob);
                previewImg.src = url;
                preview.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                btnUsePhoto.classList.remove('hidden');

                // Hide KTP Overlay during preview
                const ktpOverlay = document.getElementById('cameraKtpOverlay');
                if (ktpOverlay) {
                    ktpOverlay.classList.add('hidden');
                }

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

            // Show KTP overlay again if it is KTP input
            const ktpOverlay = document.getElementById('cameraKtpOverlay');
            if (ktpOverlay && (activeInputId === 'file_ktp_upload' || activeInputId === 'foto_ktp_upload')) {
                ktpOverlay.classList.remove('hidden');
            }

            // Restart camera
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                })
                .then(function(stream) {
                    cameraStream = stream;
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                })
                .catch(function(err) {
                    console.error('Error accessing camera:', err);
                    showErrorAlert('Tidak dapat mengakses kamera. Silakan tutup modal dan coba lagi.');
                });
        }
    }

    // Use Photo
    function usePhoto() {
        if (!capturedPhotoBlob) {
            showErrorAlert('Tidak ada foto yang diambil');
            return;
        }

        try {
            // Create a File object from blob
            const filename = (activeInputId || 'photo') + '-' + Date.now() + '.jpg';
            const file = new File([capturedPhotoBlob], filename, {
                type: 'image/jpeg'
            });

                // Create a DataTransfer object to simulate file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

            // Set the file to the upload input
            const fileInput = document.getElementById(activeInputId);
            if (fileInput) {
                fileInput.files = dataTransfer.files;

                // Handle preview and related logic depending on the input
                if (activeInputId === 'file_ktp_upload') {
                    // For OCR in Step 2, call handleKtpUpload
                    handleKtpUpload(fileInput);
                } else {
                    // For general inputs in Step 3, call previewImage
                    previewImage(fileInput, activePreviewId);
                }
            }

            // Close camera modal
            closeCamera();
        } catch (error) {
            console.error('Error using photo:', error);
            showErrorAlert('Terjadi kesalahan saat menggunakan foto. Silakan coba lagi.');
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
        if (fileInputCamera) fileInputCamera.value = '';
        if (hiddenInput) hiddenInput.value = '';
        btnOcr.disabled = true;
        capturedPhotoBlob = null;
    }

    // Remove General Document Image helper
    function removeImage(uploadInputId, hiddenInputId, previewId) {
        const uploadInput = document.getElementById(uploadInputId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const preview = document.getElementById(previewId);

        if (uploadInput) uploadInput.value = '';
        if (hiddenInput) hiddenInput.value = '';
        if (preview) {
            preview.classList.add('hidden');
            const previewImg = document.getElementById(previewId + 'Img');
            if (previewImg) previewImg.src = '';
        }
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

                reader.onload = function (e) {
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
            showErrorAlert('Silakan ambil atau upload foto KTP terlebih dahulu');
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
                        if (data.data.nama_lengkap) document.getElementById('nama_lengkap_ktp').value = data.data
                            .nama_lengkap;
                        if (data.data.tempat_lahir) document.getElementById('tempat_lahir_ktp').value = data.data
                            .tempat_lahir;
                        if (data.data.tanggal_lahir) document.getElementById('tanggal_lahir_ktp').value = data.data
                            .tanggal_lahir;
                        if (data.data.alamat) document.getElementById('alamat_ktp').value = data.data.alamat;
                        if (data.data.jenis_kelamin) document.getElementById('jenis_kelamin_ktp').value = data.data
                            .jenis_kelamin;
                        if (data.file_path) {
                            document.getElementById('file_ktp').value = data.file_path;
                            // Update file input with the uploaded file path
                            const fileInput = document.getElementById('file_ktp_upload');
                            if (fileInput.files && fileInput.files[0]) {
                                // File already in input, no need to update
                            }
                        }

                        // Show success message
                        ocrResult.innerHTML =
                            '<div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">Data KTP berhasil diekstrak! Silakan periksa dan lengkapi jika ada yang kurang.</div>';
                        ocrResult.classList.remove('hidden');
                    } else {
                        ocrResult.innerHTML =
                            '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm">OCR gagal: ' +
                            (data.message || 'Unknown error') + '. Silakan isi manual.</div>';
                        ocrResult.classList.remove('hidden');
                        if (data.file_path) document.getElementById('file_ktp').value = data.file_path;
                    }
                })
                .catch(error => {
                    ocrText.classList.remove('hidden');
                    ocrLoading.classList.add('hidden');
                    btnOcr.disabled = false;
                    ocrResult.innerHTML =
                        '<div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">Error: ' +
                        error.message + '</div>';
                    ocrResult.classList.remove('hidden');
                });
        }

        // Set send_otp=1 dan kirim form sekali saja (cegah double submit yang bikin OTP ke-invalidate)
        function setSendOtpAndLoading(button) {
            const sendOtpInput = document.getElementById('send_otp_input');
            if (!sendOtpInput) return;
            if (button.disabled) return; // Sudah diklik, jangan proses lagi
            sendOtpInput.value = '1';

        // Loading state
        const iconSend = document.getElementById('iconSend');
        const iconLoading = document.getElementById('iconLoading');
        const textSendOtp = document.getElementById('textSendOtp');
        if (iconSend && iconLoading && textSendOtp) {
            iconSend.classList.add('hidden');
            iconLoading.classList.remove('hidden');
            textSendOtp.textContent = 'Mengirim OTP...';
        }
        button.disabled = true;

        // Show SweetAlert2 loading popup
        Swal.fire({
            title: 'Mengirim OTP...',
            html: 'Sedang mengirim kode OTP ke WhatsApp Anda, mohon tunggu.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'text-lg font-bold text-gray-900 font-display'
            }
        });

            // Submit form sekali (programmatic submit = hanya satu request, tidak double)
            const form = button.form;
            if (form) form.submit();
        }

        // OTP Boxes Handler
        function initOtpBoxes() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp_code_hidden');

            if (otpInputs.length === 0) return;

            // Focus first box
            otpInputs[0].focus();

            otpInputs.forEach((input, index) => {
                // Handle input
                input.addEventListener('input', function (e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length === 1) {
                        // Move to next box
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    }

                    // Update hidden input
                    updateHiddenOtp();

                    // Auto-submit if all boxes filled
                    checkAutoSubmit();
                });

                // Handle backspace
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                // Handle paste
                input.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');

                    // Fill boxes with pasted data
                    for (let i = 0; i < pasteData.length && (index + i) < otpInputs.length; i++) {
                        otpInputs[index + i].value = pasteData[i];
                    }

                    // Focus last filled box or last box
                    const lastFilledIndex = Math.min(index + pasteData.length - 1, otpInputs.length - 1);
                    otpInputs[lastFilledIndex].focus();

                    // Update hidden input
                    updateHiddenOtp();

                    // Auto-submit if all boxes filled
                    checkAutoSubmit();
                });
            });

            function updateHiddenOtp() {
                if (!hiddenInput) return;
                let otpValue = '';
                otpInputs.forEach(input => {
                    otpValue += input.value;
                });
                hiddenInput.value = otpValue;
            }

            function checkAutoSubmit() {
                let allFilled = true;
                otpInputs.forEach(input => {
                    if (input.value === '') {
                        allFilled = false;
                    }
                });

                // Auto-submit when all 6 boxes filled
                if (allFilled) {
                    setTimeout(() => {
                        const form = document.getElementById('registerForm');
                        if (form) {
                            form.submit();
                        }
                    }, 300); // Small delay for better UX
                }
            }
        }

        // OTP Cooldown Timer
        function startCooldownTimer() {
            const cooldownElement = document.getElementById('cooldownTimer');
            if (!cooldownElement) return;

            let remainingSeconds = parseInt(cooldownElement.textContent) || 0;

            if (remainingSeconds <= 0) return;

            const countdown = setInterval(function () {
                remainingSeconds--;

                if (cooldownElement) {
                    cooldownElement.textContent = remainingSeconds;
                }

                if (remainingSeconds <= 0) {
                    clearInterval(countdown);
                    // Refresh page untuk enable resend button
                    window.location.reload();
                }
            }, 1000);
        }

        // OTP Expiry Timer: pakai sisa detik dari server (hindari salah timezone)
        function startExpiryTimer() {
            const expiryElement = document.getElementById('expiryTimer');
            if (!expiryElement) return;
            let totalSeconds = parseInt('{{ $otpExpiresAtRemainingSeconds ?? 0 }}', 10) || 0;
            const defaultSeconds = {{ (int) config('services.otp.expiry_minutes', 1) }} * 60;
            if (totalSeconds <= 0) totalSeconds = defaultSeconds;

            function updateDisplay() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                expiryElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                if (totalSeconds < 60) {
                    expiryElement.classList.add('text-red-600');
                    expiryElement.classList.remove('text-yellow-900');
                }
            }

            updateDisplay();

            const countdown = setInterval(function () {
                totalSeconds--;
                if (totalSeconds < 0) totalSeconds = 0;
                updateDisplay();

                if (totalSeconds <= 0) {
                    clearInterval(countdown);
                    expiryElement.textContent = 'Kadaluarsa';
                    expiryElement.classList.add('text-red-600', 'font-bold');
                    const otpBoxes = document.getElementById('otpBoxes');
                    if (otpBoxes) {
                        otpBoxes.innerHTML = `
                        <div class="col-span-6 text-center py-4">
                            <p class="text-red-600 font-medium">Kode OTP sudah kadaluarsa.</p>
                            <p class="text-sm text-gray-600 mt-2">Silakan klik "Kirim Ulang" untuk mendapatkan kode baru.</p>
                        </div>
                    `;
                    }
                    // Reload agar tombol Kirim Ulang bisa muncul (cooldown = 0 setelah OTP kadaluarsa)
                    setTimeout(function () { window.location.reload(); }, 800);
                }
            }, 1000);
        }

        // Auto-start timer jika di step 2 dan ada countdown
        var currentStep = parseInt('{{ $step }}');
        if (currentStep === 2) {
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize OTP boxes
                initOtpBoxes();

                // Start cooldown timer
                startCooldownTimer();

                // Start expiry timer (only if OTP sent)
                const otpSent = {{ ($otpSent ?? false) ? 'true' : 'false' }};
                if (otpSent) {
                    startExpiryTimer();
                }
            });
        }

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
        
        // Ensure nomor rekening only accepts numbers
        const noRekeningInput = document.getElementById('no_rekening');
        if (noRekeningInput) {
            // Prevent non-numeric input
            noRekeningInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            });
            
            // Also filter on paste
            noRekeningInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const numbersOnly = paste.replace(/[^0-9]/g, '');
                this.value = numbersOnly;
            });
        }

        // Form Client-Side Validation
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', async function(e) {
                // Prevent form submission to allow checks first
                e.preventDefault();

                const stepInput = registerForm.querySelector('input[name="step"]');
                const substepInput = registerForm.querySelector('input[name="substep"]');
                const step = stepInput ? parseInt(stepInput.value) : 1;
                const substep = substepInput ? parseInt(substepInput.value) : 1;
                
                const sendOtpInput = document.getElementById('send_otp_input');
                if (sendOtpInput && sendOtpInput.value === '1') {
                    registerForm.submit();
                    return; // Skip client-side check if sending OTP
                }

                let isValid = true;
                let errorMsg = '';
                let focusEl = null;

                if (step === 1) {
                    if (substep === 1) {
                        const namaInput = document.getElementById('nama');
                        const emailInput = document.getElementById('email');
                        const nomorHpInput = document.getElementById('nomor_hp');
                        const passwordInput = document.getElementById('password');
                        const passwordConfirmInput = document.getElementById('password_confirmation');
                        const fotoInput = document.getElementById('foto');

                        const nama = namaInput ? namaInput.value.trim() : '';
                        const email = emailInput ? emailInput.value.trim() : '';
                        const nomor_hp = nomorHpInput ? nomorHpInput.value.trim() : '';
                        const password = passwordInput ? passwordInput.value.trim() : '';
                        const password_confirmation = passwordConfirmInput ? passwordConfirmInput.value.trim() : '';
                        
                        if (!nama) {
                            errorMsg = 'Nama Lengkap wajib diisi.';
                            focusEl = namaInput;
                            isValid = false;
                        } else if (nama.length > 255) {
                            errorMsg = 'Nama Lengkap maksimal 255 karakter.';
                            focusEl = namaInput;
                            isValid = false;
                        } else if (!email) {
                            errorMsg = 'Email wajib diisi.';
                            focusEl = emailInput;
                            isValid = false;
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                            errorMsg = 'Format email tidak valid.';
                            focusEl = emailInput;
                            isValid = false;
                        } else if (email.length > 255) {
                            errorMsg = 'Email maksimal 255 karakter.';
                            focusEl = emailInput;
                            isValid = false;
                        } else if (!nomor_hp) {
                            errorMsg = 'Nomor HP wajib diisi.';
                            focusEl = nomorHpInput;
                            isValid = false;
                        } else {
                            const normalizedHp = nomor_hp.replace(/[^0-9]/g, '');
                            if (normalizedHp.length < 8 || normalizedHp.length > 15) {
                                errorMsg = 'Nomor HP tidak valid (harus 8-15 digit angka).';
                                focusEl = nomorHpInput;
                                isValid = false;
                            } else {
                                const hasExistingFoto = document.getElementById('fotoPreview') && !document.getElementById('fotoPreview').classList.contains('hidden');
                                if (!hasExistingFoto && !password) {
                                    errorMsg = 'Password wajib diisi.';
                                    focusEl = passwordInput;
                                    isValid = false;
                                } else if (password && password.length < 8) {
                                    errorMsg = 'Password minimal harus 8 karakter.';
                                    focusEl = passwordInput;
                                    isValid = false;
                                } else if (password && password !== password_confirmation) {
                                    errorMsg = 'Konfirmasi password tidak cocok.';
                                    focusEl = passwordConfirmInput;
                                    isValid = false;
                                }
                            }
                        }

                        if (isValid && fotoInput) {
                            const hasExistingFoto = document.getElementById('fotoPreview') && !document.getElementById('fotoPreview').classList.contains('hidden');
                            if (!hasExistingFoto && fotoInput.files.length === 0) {
                                errorMsg = 'Foto Profil wajib diunggah.';
                                focusEl = fotoInput;
                                isValid = false;
                            } else if (fotoInput.files.length > 0 && fotoInput.files[0].size > 2 * 1024 * 1024) {
                                errorMsg = 'File Foto Profil tidak boleh melebihi 2MB.';
                                focusEl = fotoInput;
                                isValid = false;
                            }
                        }
                    } else if (substep === 2) {
                        const nikInput = document.getElementById('nik');
                        const namaLengkapKtpInput = document.getElementById('nama_lengkap_ktp');
                        const tempatLahirKtpInput = document.getElementById('tempat_lahir_ktp');
                        const tanggalLahirKtpInput = document.getElementById('tanggal_lahir_ktp');
                        const rtRwInput = document.getElementById('rt_rw');
                        const kelDesaInput = document.getElementById('kel_desa');
                        const kecamatanInput = document.getElementById('kecamatan');
                        const alamatKtpInput = document.getElementById('alamat_ktp');
                        const jenisKelaminKtpInput = document.getElementById('jenis_kelamin_ktp');

                        const nik = nikInput ? nikInput.value.trim() : '';
                        const nama_ktp = namaLengkapKtpInput ? namaLengkapKtpInput.value.trim() : '';
                        const tempat_ktp = tempatLahirKtpInput ? tempatLahirKtpInput.value.trim() : '';
                        const tanggal_ktp = tanggalLahirKtpInput ? tanggalLahirKtpInput.value.trim() : '';
                        const rt_rw = rtRwInput ? rtRwInput.value.trim() : '';
                        const kel_desa = kelDesaInput ? kelDesaInput.value.trim() : '';
                        const kecamatan = kecamatanInput ? kecamatanInput.value.trim() : '';
                        const alamat_ktp = alamatKtpInput ? alamatKtpInput.value.trim() : '';
                        const jenis_kelamin_ktp = jenisKelaminKtpInput ? jenisKelaminKtpInput.value : '';

                        if (!nik) {
                            errorMsg = 'NIK wajib diisi.';
                            focusEl = nikInput;
                            isValid = false;
                        } else if (nik.length !== 16 || !/^[0-9]+$/.test(nik)) {
                            errorMsg = 'NIK harus 16 digit angka.';
                            focusEl = nikInput;
                            isValid = false;
                        } else if (!nama_ktp) {
                            errorMsg = 'Nama Lengkap KTP wajib diisi.';
                            focusEl = namaLengkapKtpInput;
                            isValid = false;
                        } else if (!tempat_ktp) {
                            errorMsg = 'Tempat Lahir KTP wajib diisi.';
                            focusEl = tempatLahirKtpInput;
                            isValid = false;
                        } else if (!tanggal_ktp) {
                            errorMsg = 'Tanggal Lahir KTP wajib diisi.';
                            focusEl = tanggalLahirKtpInput;
                            isValid = false;
                        } else if (!rt_rw) {
                            errorMsg = 'RT/RW wajib diisi.';
                            focusEl = rtRwInput;
                            isValid = false;
                        } else if (!kel_desa) {
                            errorMsg = 'Kelurahan/Desa wajib diisi.';
                            focusEl = kelDesaInput;
                            isValid = false;
                        } else if (!kecamatan) {
                            errorMsg = 'Kecamatan wajib diisi.';
                            focusEl = kecamatanInput;
                            isValid = false;
                        } else if (!jenis_kelamin_ktp) {
                            errorMsg = 'Jenis Kelamin KTP wajib diisi.';
                            focusEl = jenisKelaminKtpInput;
                            isValid = false;
                        }

                        if (isValid) {
                            const fileKtpInput = document.getElementById('file_ktp');
                            const fileKtpUploadInput = document.getElementById('file_ktp_upload');
                            const fileKtpVal = fileKtpInput ? fileKtpInput.value.trim() : '';
                            const hasUploadedFile = fileKtpUploadInput && fileKtpUploadInput.files.length > 0;
                            const hasExistingKtpFile = document.getElementById('ktpPreview') && !document.getElementById('ktpPreview').classList.contains('hidden');

                            if (!hasExistingKtpFile && !fileKtpVal && !hasUploadedFile) {
                                errorMsg = 'Foto KTP wajib diunggah atau diambil menggunakan kamera.';
                                focusEl = fileKtpUploadInput;
                                isValid = false;
                            } else if (hasUploadedFile && fileKtpUploadInput.files[0].size > 5 * 1024 * 1024) {
                                errorMsg = 'File Foto KTP tidak boleh melebihi 5MB.';
                                focusEl = fileKtpUploadInput;
                                isValid = false;
                            }
                        }
                    } else if (substep === 3) {
                        const noKkInput = document.getElementById('no_kk');
                        const tempatLahirInput = document.getElementById('tempat_lahir');
                        const tanggalLahirInput = document.getElementById('tanggal_lahir');
                        const jenisKelaminInput = document.getElementById('jenis_kelamin');
                        const alamatInput = document.getElementById('alamat');
                        const fotoKtpInput = document.getElementById('foto_ktp');
                        const fotoKtpUploadInput = document.getElementById('foto_ktp_upload');
                        const fotoKkInput = document.getElementById('foto_kk');
                        const fotoKkUploadInput = document.getElementById('foto_kk_upload');
                        const fotoSelfieInput = document.getElementById('foto_selfie');
                        const fotoSelfieUploadInput = document.getElementById('foto_selfie_upload');

                        const no_kk = noKkInput ? noKkInput.value.trim() : '';
                        const tempat_lahir = tempatLahirInput ? tempatLahirInput.value.trim() : '';
                        const tanggal_lahir = tanggalLahirInput ? tanggalLahirInput.value.trim() : '';
                        const jenis_kelamin = jenisKelaminInput ? jenisKelaminInput.value : '';
                        const alamat = alamatInput ? alamatInput.value.trim() : '';

                        if (!no_kk) {
                            errorMsg = 'Nomor KK wajib diisi.';
                            focusEl = noKkInput;
                            isValid = false;
                        } else if (no_kk.length !== 16 || !/^[0-9]+$/.test(no_kk)) {
                            errorMsg = 'Nomor KK harus 16 digit angka.';
                            focusEl = noKkInput;
                            isValid = false;
                        } else if (!tempat_lahir) {
                            errorMsg = 'Tempat Lahir wajib diisi.';
                            focusEl = tempatLahirInput;
                            isValid = false;
                        } else if (!tanggal_lahir) {
                            errorMsg = 'Tanggal Lahir wajib diisi.';
                            focusEl = tanggalLahirInput;
                            isValid = false;
                        } else if (!jenis_kelamin) {
                            errorMsg = 'Jenis Kelamin wajib dipilih.';
                            focusEl = jenisKelaminInput;
                            isValid = false;
                        } else if (!alamat) {
                            errorMsg = 'Alamat wajib diisi.';
                            focusEl = alamatInput;
                            isValid = false;
                        }

                        // Validate Foto KTP in Detail Nasabah
                        if (isValid && (fotoKtpInput || fotoKtpUploadInput)) {
                            const fotoKtpVal = fotoKtpInput ? fotoKtpInput.value.trim() : '';
                            const hasUploadedFile = fotoKtpUploadInput && fotoKtpUploadInput.files.length > 0;
                            const hasExistingKtp = document.getElementById('fotoKtpPreview') && !document.getElementById('fotoKtpPreview').classList.contains('hidden');

                            if (!hasExistingKtp && !fotoKtpVal && !hasUploadedFile) {
                                errorMsg = 'Foto KTP wajib diunggah.';
                                focusEl = fotoKtpUploadInput;
                                isValid = false;
                            } else if (hasUploadedFile && fotoKtpUploadInput.files[0].size > 5 * 1024 * 1024) {
                                errorMsg = 'File Foto KTP tidak boleh melebihi 5MB.';
                                focusEl = fotoKtpUploadInput;
                                isValid = false;
                            }
                        }

                        // Validate Foto KK
                        if (isValid && (fotoKkInput || fotoKkUploadInput)) {
                            const fotoKkVal = fotoKkInput ? fotoKkInput.value.trim() : '';
                            const hasUploadedFile = fotoKkUploadInput && fotoKkUploadInput.files.length > 0;
                            const hasExistingKk = document.getElementById('fotoKkPreview') && !document.getElementById('fotoKkPreview').classList.contains('hidden');

                            if (!hasExistingKk && !fotoKkVal && !hasUploadedFile) {
                                errorMsg = 'Foto KK wajib diunggah.';
                                focusEl = fotoKkUploadInput;
                                isValid = false;
                            } else if (hasUploadedFile && fotoKkUploadInput.files[0].size > 5 * 1024 * 1024) {
                                errorMsg = 'File Foto KK tidak boleh melebihi 5MB.';
                                focusEl = fotoKkUploadInput;
                                isValid = false;
                            }
                        }

                        // Validate Foto Selfie Holding KTP
                        if (isValid && (fotoSelfieInput || fotoSelfieUploadInput)) {
                            const fotoSelfieVal = fotoSelfieInput ? fotoSelfieInput.value.trim() : '';
                            const hasUploadedFile = fotoSelfieUploadInput && fotoSelfieUploadInput.files.length > 0;
                            const hasExistingSelfie = document.getElementById('fotoSelfiePreview') && !document.getElementById('fotoSelfiePreview').classList.contains('hidden');

                            if (!hasExistingSelfie && !fotoSelfieVal && !hasUploadedFile) {
                                errorMsg = 'Foto Selfie holding KTP wajib diunggah.';
                                focusEl = fotoSelfieUploadInput;
                                isValid = false;
                            } else if (hasUploadedFile && fotoSelfieUploadInput.files[0].size > 5 * 1024 * 1024) {
                                errorMsg = 'File Foto Selfie tidak boleh melebihi 5MB.';
                                focusEl = fotoSelfieUploadInput;
                                isValid = false;
                            }
                        }
                    } else if (substep === 4) {
                        const pekerjaanInput = document.getElementById('pekerjaan');
                        const penghasilanInput = document.getElementById('penghasilan');
                        const namaPerusahaanInput = document.getElementById('nama_perusahaan');

                        const pekerjaan = pekerjaanInput ? pekerjaanInput.value.trim() : '';
                        const penghasilan = penghasilanInput ? penghasilanInput.value.trim() : '';
                        const nama_perusahaan = namaPerusahaanInput ? namaPerusahaanInput.value.trim() : '';

                        if (!pekerjaan) {
                            errorMsg = 'Pekerjaan wajib diisi.';
                            focusEl = pekerjaanInput;
                            isValid = false;
                        } else if (!penghasilan) {
                            errorMsg = 'Penghasilan wajib diisi.';
                            focusEl = penghasilanInput;
                            isValid = false;
                        } else if (!nama_perusahaan) {
                            errorMsg = 'Nama Perusahaan wajib diisi.';
                            focusEl = namaPerusahaanInput;
                            isValid = false;
                        }
                    } else if (substep === 5) {
                        const noRekeningInput = document.getElementById('no_rekening');
                        const namaPemilikInput = document.getElementById('nama_pemilik_rekening');
                        const jenisAtmInput = document.getElementById('jenis_atm');

                        const no_rekening = noRekeningInput ? noRekeningInput.value.trim() : '';
                        const nama_pemilik = namaPemilikInput ? namaPemilikInput.value.trim() : '';
                        const jenis_atm = jenisAtmInput ? jenisAtmInput.value.trim() : '';

                        if (!no_rekening) {
                            errorMsg = 'Nomor Rekening wajib diisi.';
                            focusEl = noRekeningInput;
                            isValid = false;
                        } else if (no_rekening.length < 10 || no_rekening.length > 16 || !/^[0-9]+$/.test(no_rekening)) {
                            errorMsg = 'Nomor Rekening tidak valid (harus 10-16 digit angka).';
                            focusEl = noRekeningInput;
                            isValid = false;
                        } else if (!nama_pemilik) {
                            errorMsg = 'Nama Pemilik Rekening wajib diisi.';
                            focusEl = namaPemilikInput;
                            isValid = false;
                        } else if (!jenis_atm) {
                            errorMsg = 'Jenis ATM wajib diisi.';
                            const customContainer = document.getElementById('custom_bank_container');
                            if (customContainer && !customContainer.classList.contains('hidden')) {
                                focusEl = document.getElementById('jenis_atm_lainnya');
                            } else {
                                focusEl = document.getElementById('bank_dropdown_trigger');
                            }
                            isValid = false;
                        }
                    }
                } else if (step === 3) {
                    const pinInput = document.getElementById('pin');
                    const pinConfirmInput = document.getElementById('pin_confirmation');
                    const pin = pinInput ? pinInput.value.trim() : '';
                    const pin_confirmation = pinConfirmInput ? pinConfirmInput.value.trim() : '';
                    
                    if (pin.length !== 6 || !/^[0-9]+$/.test(pin)) {
                        errorMsg = 'PIN harus 6 digit angka.';
                        focusEl = pinInput;
                        isValid = false;
                    } else if (pin !== pin_confirmation) {
                        errorMsg = 'Konfirmasi PIN tidak cocok.';
                        focusEl = pinConfirmInput;
                        isValid = false;
                    }
                }

                if (!isValid) {
                    showErrorAlert(errorMsg);
                    if (focusEl) focusEl.focus();
                    return;
                }

                // Show verification/loading screen popup
                Swal.fire({
                    title: 'Memproses...',
                    html: 'Sedang memverifikasi data Anda, mohon tunggu.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl',
                        title: 'text-lg font-bold text-gray-900 font-display'
                    }
                });

                try {
                    let uniqueChecks = [];
                    if (step === 1) {
                        if (substep === 1) {
                            const emailEl = document.getElementById('email');
                            const hpEl = document.getElementById('nomor_hp');
                            uniqueChecks.push(checkFieldUniqueness('email', emailEl.value.trim(), emailEl));
                            uniqueChecks.push(checkFieldUniqueness('nomor_hp', hpEl.value.trim(), hpEl));
                        } else if (substep === 2) {
                            const nikEl = document.getElementById('nik');
                            if (nikEl) {
                                uniqueChecks.push(checkFieldUniqueness('nik', nikEl.value.trim(), nikEl));
                            }
                        } else if (substep === 3) {
                            const kkEl = document.getElementById('no_kk');
                            if (kkEl) {
                                uniqueChecks.push(checkFieldUniqueness('no_kk', kkEl.value.trim(), kkEl));
                            }
                        }
                    }

                    // Run uniqueness checks concurrently
                    const results = await Promise.all(uniqueChecks);
                    
                    // If any uniqueness check failed, stop submission
                    const failedCheck = results.find(res => res.unique === false);
                    if (failedCheck) {
                        Swal.close();
                        showErrorAlert(failedCheck.message);
                        if (failedCheck.element) {
                            failedCheck.element.focus();
                        }
                        return;
                    }
                    
                    // Update loader text to saving
                    const popupEl = Swal.getHtmlContainer();
                    if (popupEl) {
                        popupEl.textContent = 'Sedang menyimpan data Anda, mohon tunggu.';
                    }
                    
                    // Submit form programmatically bypassing the event listener
                    registerForm.submit();

                } catch (error) {
                    Swal.close();
                    console.error('Error in uniqueness validation:', error);
                    showErrorAlert('Terjadi kesalahan koneksi saat memverifikasi data. Silakan coba lagi.');
                }
            });
        }
    });

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
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

        // Load existing photos on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Load foto profil if exists
            @if(!empty($formData['foto']) && $formData['foto'] !== 'default-profile.jpg')
                const fotoPreview = document.getElementById('fotoPreview');
                const fotoPreviewImg = document.getElementById('fotoPreviewImg');
                if (fotoPreview && fotoPreviewImg) {
                    fotoPreviewImg.src = '{{ asset("storage/" . $formData["foto"]) }}';
                    fotoPreview.classList.remove('hidden');
                }
            @endif

                // Load foto KTP if exists
                @if(!empty($formData['foto_ktp']))
                    const fotoKtpPreview = document.getElementById('fotoKtpPreview');
                    const fotoKtpPreviewImg = document.getElementById('fotoKtpPreviewImg');
                    if (fotoKtpPreview && fotoKtpPreviewImg) {
                        fotoKtpPreviewImg.src = '{{ asset("storage/" . $formData["foto_ktp"]) }}';
                        fotoKtpPreview.classList.remove('hidden');
                    }
                @endif

        // Load foto KK if exists
        @if(!empty($formData['foto_kk']))
            const fotoKkPreview = document.getElementById('fotoKkPreview');
            const fotoKkPreviewImg = document.getElementById('fotoKkPreviewImg');
            if (fotoKkPreview && fotoKkPreviewImg) {
                fotoKkPreviewImg.src = '{{ asset("storage/" . $formData["foto_kk"]) }}';
                fotoKkPreview.classList.remove('hidden');
            }
        @endif

        // Load foto Selfie if exists
        @if(!empty($formData['foto_selfie']))
            const fotoSelfiePreview = document.getElementById('fotoSelfiePreview');
            const fotoSelfiePreviewImg = document.getElementById('fotoSelfiePreviewImg');
            if (fotoSelfiePreview && fotoSelfiePreviewImg) {
                fotoSelfiePreviewImg.src = '{{ asset("storage/" . $formData["foto_selfie"]) }}';
                fotoSelfiePreview.classList.remove('hidden');
            }
        @endif

        // Load darurat foto KTP if exists
        @if(!empty($formData['darurat_foto_ktp']))
            const daruratKtpPreview = document.getElementById('daruratKtpPreview');
            const daruratKtpPreviewImg = document.getElementById('daruratKtpPreviewImg');
            if (daruratKtpPreview && daruratKtpPreviewImg) {
                daruratKtpPreviewImg.src = '{{ asset("storage/" . $formData["darurat_foto_ktp"]) }}';
                daruratKtpPreview.classList.remove('hidden');
            }
        @endif

        // Load cropped KTP if exists (Substep 5)
        @if(!empty($formData['file_ktp']))
            const ktpPreview = document.getElementById('ktpPreview');
            const ktpPreviewImg = document.getElementById('ktpPreviewImg');
            if (ktpPreview && ktpPreviewImg) {
                ktpPreviewImg.src = '{{ asset("storage/" . $formData["file_ktp"]) }}';
                ktpPreview.classList.remove('hidden');
                
                // Enable OCR button if there is a preview
                const btnOcr = document.getElementById('btnOcr');
                if (btnOcr) {
                    btnOcr.disabled = false;
                }
            }
        @endif
    });

        // Load saved form data
        document.addEventListener('DOMContentLoaded', function () {
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

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.135M15 12a3 3 0 00-4.243-2.829M3 3l18 18" />
        `;
            } else {
                input.type = "password";
                icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
            </path>
        `;
        }
    }

    // AJAX verification function for uniqueness checking during submit
    async function checkFieldUniqueness(type, value, element) {
        if (!value || value.trim() === '') {
            return { unique: true };
        }
        try {
            const response = await fetch('{{ route("register.check-unique") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type, value })
            });
            
            const data = await response.json();
            if (data.unique === false) {
                setInputError(element, data.message);
                return { unique: false, message: data.message, element: element };
            } else {
                clearInputError(element);
                return { unique: true };
            }
        } catch (error) {
            console.error('Error checking unique field ' + type + ':', error);
            return { unique: true }; // Fallback
        }
    }

    function setInputError(inputEl, message) {
        inputEl.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        inputEl.classList.remove('border-gray-300', 'focus:ring-[#674c1d]', 'focus:border-[#674c1d]');
        
        let errorEl = inputEl.parentNode.querySelector('.ajax-error-msg');
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.className = 'mt-1 text-sm text-red-600 ajax-error-msg';
            inputEl.parentNode.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }

    function clearInputError(inputEl) {
        inputEl.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        inputEl.classList.add('border-gray-300', 'focus:ring-[#674c1d]', 'focus:border-[#674c1d]');
        const errorEl = inputEl.parentNode.querySelector('.ajax-error-msg');
        if (errorEl) {
            errorEl.remove();
        }
    }

    // Custom Searchable Dropdown Logic
    function toggleBankDropdown(forceState = null) {
        const dropdownContent = document.getElementById('bank_dropdown_content');
        const arrow = document.getElementById('bank_dropdown_arrow');
        if (!dropdownContent) return;

        const isHidden = dropdownContent.classList.contains('hidden');
        const shouldShow = forceState !== null ? forceState : isHidden;

        if (shouldShow) {
            dropdownContent.classList.remove('hidden');
            if (arrow) arrow.classList.add('rotate-180');
            // Focus the search input when opening
            const searchInput = document.getElementById('bank_search');
            if (searchInput) {
                searchInput.value = '';
                filterBanks();
                setTimeout(() => searchInput.focus(), 50);
            }
        } else {
            dropdownContent.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
        }
    }

    function filterBanks() {
        const searchInput = document.getElementById('bank_search');
        const filter = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const options = document.querySelectorAll('.bank-option');
        
        options.forEach(opt => {
            const name = opt.getAttribute('data-bank-name');
            // Always show the "Lainnya" option
            if (name === 'lainnya') {
                opt.style.display = '';
                return;
            }
            
            if (name.includes(filter)) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
    }

    function selectBank(bankName, closeDropdown = true) {
        const hiddenInput = document.getElementById('jenis_atm');
        const triggerText = document.getElementById('bank_dropdown_trigger_text');
        const customContainer = document.getElementById('custom_bank_container');
        const customInput = document.getElementById('jenis_atm_lainnya');
        
        if (!hiddenInput || !triggerText) return;

        if (bankName === 'Lainnya') {
            triggerText.innerText = 'Lainnya';
            triggerText.classList.remove('text-gray-500');
            triggerText.classList.add('text-gray-900');
            if (customContainer) customContainer.classList.remove('hidden');
            if (customInput) {
                hiddenInput.value = customInput.value.trim();
            } else {
                hiddenInput.value = '';
            }
        } else {
            triggerText.innerText = bankName;
            triggerText.classList.remove('text-gray-500');
            triggerText.classList.add('text-gray-900');
            if (customContainer) customContainer.classList.add('hidden');
            hiddenInput.value = bankName;
            if (customInput) {
                customInput.value = '';
            }
        }
        
        // Highlight the selected option in the list
        const options = document.querySelectorAll('.bank-option');
        options.forEach(opt => {
            const textEl = opt.querySelector('span');
            if (textEl) {
                const text = textEl.innerText;
                if (text === bankName || (bankName === 'Lainnya' && text.includes('Lainnya'))) {
                    opt.classList.add('bg-amber-50', 'text-[#674c1d]', 'font-medium');
                } else {
                    opt.classList.remove('bg-amber-50', 'text-[#674c1d]', 'font-medium');
                }
            }
        });

        if (closeDropdown) {
            toggleBankDropdown(false);
        }
    }

    function updateCustomBank(val) {
        const hiddenInput = document.getElementById('jenis_atm');
        if (hiddenInput) {
            hiddenInput.value = val.trim();
        }
    }

    // Close dropdown on click outside
    document.addEventListener('click', function(event) {
        const wrapper = document.getElementById('bank_dropdown_wrapper');
        if (wrapper && !wrapper.contains(event.target)) {
            toggleBankDropdown(false);
        }
    });

    // Initialize Bank Selection on page load
    document.addEventListener('DOMContentLoaded', function() {
        const jenisAtmInput = document.getElementById('jenis_atm');
        if (jenisAtmInput) {
            const savedValue = jenisAtmInput.value.trim();
            if (savedValue) {
                const standardBanks = @json(isset($banks) && $banks ? $banks->pluck('nama_bank')->toArray() : []);
                if (standardBanks.includes(savedValue)) {
                    // It's a standard bank
                    selectBank(savedValue, false);
                } else {
                    // It's a custom bank
                    selectBank('Lainnya', false);
                    const customInput = document.getElementById('jenis_atm_lainnya');
                    if (customInput) {
                        customInput.value = savedValue;
                        jenisAtmInput.value = savedValue;
                    }
                }
            }
        }
    });
    </script>
@endpush

@push('styles')
    <style>
    :root {
        --primary: #674c1d;
        --primary-light: #8b6f2f;
        --primary-dark: #4a3514;
        --gold: #d4af37;
    }

    body {
        font-family: 'Inter', sans-serif;
    }

    .font-display {
        font-family: 'Playfair Display', serif;
    }

    /* Gradient animation */
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .gradient-animate {
        background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 25%, #d4af37 50%, #8b6f2f 75%, #674c1d 100%);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
    }

    /* Animations for OTP page */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes pulse-border {
        0%, 100% { border-color: rgb(209 213 219); }
        50% { border-color: rgb(103 76 29); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }

    .float-animation {
        animation: float 4s ease-in-out infinite;
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .otp-input:focus {
        animation: pulse-border 1s ease-in-out;
    }

    /* Smooth transitions */
    .otp-input {
        transition: all 0.2s ease;
    }

    .otp-input:focus {
        transform: scale(1.05);
    }

    /* Background pattern */
    .pattern-bg {
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(103, 76, 29, 0.1) 0%, transparent 50%);
    }

    /* Progress step animation */
    .progress-step {
        transition: all 0.3s ease;
    }

    .progress-step.active {
        transform: scale(1.1);
    }
    </style>
@endpush