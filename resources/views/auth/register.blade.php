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
                    @for($i = 1; $i <= 6; $i++)
                        <div class="flex items-center {{ $i < 6 ? 'flex-1' : '' }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= $i ? 'bg-[#674c1d] text-white' : 'bg-gray-200 text-gray-500' }} transition-all duration-300">
                                <span class="text-sm font-semibold">{{ $i }}</span>
                            </div>
                            @if($i < 6)
                                <div class="flex-1 h-1 mx-2 {{ $step > $i ? 'bg-[#674c1d]' : 'bg-gray-200' }} transition-all duration-300"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-gray-600">
                    @php
                        $labels = [
                            1 => 'Data Diri',
                            2 => 'Detail Nasabah',
                            3 => 'Pekerjaan',
                            4 => 'Rekening',
                            5 => 'Data KTP',
                            6 => 'Kontak Darurat (Optional)'
                        ];
                    @endphp
                    @for($i = 1; $i <= 6; $i++)
                        <div class="flex items-center {{ $i < 6 ? 'flex-1' : '' }}">
                            <span class="{{ $step >= $i ? 'text-[#674c1d] font-medium' : '' }} text-center">{{ $labels[$i] }}</span>
                            @if($i < 6)
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
                                    Daftar Akun
                                @elseif($step == 2)
                                    Detail Nasabah
                                @elseif($step == 3)
                                    Data Pekerjaan
                                @elseif($step == 4)
                                    Data Rekening
                                @elseif($step == 5)
                                    Data KTP
                                @else
                                    Kontak Darurat (Optional)
                                @endif
                            </h2>
                            <p class="text-gray-600">
                                @if($step == 1)
                                    Lengkapi data diri Anda untuk memulai
                                @elseif($step == 2)
                                    Lengkapi detail informasi nasabah
                                @elseif($step == 3)
                                    Masukkan informasi pekerjaan Anda
                                @elseif($step == 4)
                                    Masukkan data rekening bank Anda
                                @elseif($step == 5)
                                    Masukkan data dari KTP Anda
                                @else
                                    Informasi kontak darurat untuk keperluan penting (Optional)
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
                                <!-- Step 1: Data Diri -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Masukkan nama lengkap">
                                        @error('nama')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="nama@email.com">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                                        <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" required maxlength="12"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="081234567890">
                                        @error('nomor_hp')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <input type="password" name="password" id="password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Minimal 8 karakter">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Ulangi password">
                                    </div>

                                    <div>
                                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                        <div class="mt-1 flex items-center space-x-4">
                                            <label for="foto" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Pilih Foto</span>
                                                </div>
                                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoPreview')">
                                            </label>
                                        </div>
                                        <div id="fotoPreview" class="mt-2 hidden">
                                            <img id="fotoPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                        </div>
                                        @error('foto')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            @elseif($step == 2)
                                <!-- Step 2: Detail Nasabah -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-2">Nomor KK</label>
                                        <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" required maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit Nomor KK">
                                        @error('no_kk')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="Kota kelahiran">
                                            @error('tempat_lahir')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            @error('tanggal_lahir')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" rows="4" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                            placeholder="Alamat lengkap tempat tinggal">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">Foto KTP</label>
                                            <label for="foto_ktp" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KTP</span>
                                                </div>
                                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKtpPreview')">
                                            </label>
                                            <div id="fotoKtpPreview" class="mt-2 hidden">
                                                <img id="fotoKtpPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                            @error('foto_ktp')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="foto_kk" class="block text-sm font-medium text-gray-700 mb-2">Foto KK</label>
                                            <label for="foto_kk" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KK</span>
                                                </div>
                                                <input type="file" name="foto_kk" id="foto_kk" accept="image/*" class="hidden" onchange="previewImage(this, 'fotoKkPreview')">
                                            </label>
                                            <div id="fotoKkPreview" class="mt-2 hidden">
                                                <img id="fotoKkPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                            @error('foto_kk')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            @elseif($step == 3)
                                <!-- Step 3: Data Pekerjaan -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama pekerjaan">
                                        @error('pekerjaan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="penghasilan" class="block text-sm font-medium text-gray-700 mb-2">Penghasilan (Rp)</label>
                                        <input type="number" name="penghasilan" id="penghasilan" value="{{ old('penghasilan') }}" step="0.01" min="0"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="0.00">
                                        @error('penghasilan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama perusahaan tempat bekerja">
                                        @error('nama_perusahaan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                        <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama bank">
                                        @error('nama_bank')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            @elseif($step == 4)
                                <!-- Step 4: Data Rekening -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="no_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                                        <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}" required maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit nomor rekening">
                                        @error('no_rekening')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nama_pemilik_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                        <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama sesuai rekening">
                                        @error('nama_pemilik_rekening')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jenis_atm" class="block text-sm font-medium text-gray-700 mb-2">Jenis ATM</label>
                                        <select name="jenis_atm" id="jenis_atm" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Jenis ATM</option>
                                            <option value="Visa" {{ old('jenis_atm') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                            <option value="Mastercard" {{ old('jenis_atm') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                            <option value="BCA" {{ old('jenis_atm') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                            <option value="Mandiri" {{ old('jenis_atm') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                            <option value="BNI" {{ old('jenis_atm') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                            <option value="BRI" {{ old('jenis_atm') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        </select>
                                        @error('jenis_atm')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            @elseif($step == 5)
                                <!-- Step 5: Data KTP -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required maxlength="16"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="16 digit NIK">
                                        @error('nik')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap (Sesuai KTP)</label>
                                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama sesuai KTP">
                                        @error('nama_lengkap')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="ktp_tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" id="ktp_tempat_lahir" value="{{ old('tempat_lahir') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="Kota kelahiran">
                                            @error('tempat_lahir')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="ktp_tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="ktp_tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            @error('tanggal_lahir')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="ktp_jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="ktp_jenis_kelamin" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" rows="4" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                            placeholder="Alamat lengkap sesuai KTP">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="file_ktp" class="block text-sm font-medium text-gray-700 mb-2">Upload Foto KTP</label>
                                        <div class="mt-1">
                                            <label for="file_ktp" class="cursor-pointer">
                                                <div class="px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-600">Klik untuk upload foto KTP</p>
                                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG maksimal 5MB</p>
                                                </div>
                                                <input type="file" name="file_ktp" id="file_ktp" accept="image/*" class="hidden" onchange="previewImage(this, 'ktpPreview')">
                                            </label>
                                        </div>
                                        <div id="ktpPreview" class="mt-4 hidden">
                                            <img id="ktpPreviewImg" src="" alt="KTP Preview" class="max-w-full h-auto rounded-lg border border-gray-300">
                                        </div>
                                        @error('file_ktp')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            @elseif($step == 6)
                                <!-- Step 6: Kontak Darurat -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="darurat_nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="darurat_nama_lengkap" id="darurat_nama_lengkap" value="{{ old('darurat_nama_lengkap') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Nama lengkap kontak darurat">
                                        @error('darurat_nama_lengkap')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="hubungan_peminjam" class="block text-sm font-medium text-gray-700 mb-2">Hubungan</label>
                                        <select name="hubungan_peminjam" id="hubungan_peminjam" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none">
                                            <option value="">Pilih Hubungan</option>
                                            <option value="Suami/Istri" {{ old('hubungan_peminjam') == 'Suami/Istri' ? 'selected' : '' }}>Suami/Istri</option>
                                            <option value="Orang Tua" {{ old('hubungan_peminjam') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                            <option value="Anak" {{ old('hubungan_peminjam') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                            <option value="Saudara" {{ old('hubungan_peminjam') == 'Saudara' ? 'selected' : '' }}>Saudara</option>
                                            <option value="Lainnya" {{ old('hubungan_peminjam') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('hubungan_peminjam')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="darurat_no_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                            <input type="text" name="darurat_no_telepon" id="darurat_no_telepon" value="{{ old('darurat_no_telepon') }}" required maxlength="12"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="081234567890">
                                            @error('darurat_no_telepon')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="darurat_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                            <input type="email" name="darurat_email" id="darurat_email" value="{{ old('darurat_email') }}" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="email@example.com">
                                            @error('darurat_email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="darurat_alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                        <textarea name="darurat_alamat" id="darurat_alamat" rows="3" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none resize-none"
                                            placeholder="Alamat lengkap">{{ old('darurat_alamat') }}</textarea>
                                        @error('darurat_alamat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="darurat_pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                        <input type="text" name="darurat_pekerjaan" id="darurat_pekerjaan" value="{{ old('darurat_pekerjaan') }}" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                            placeholder="Pekerjaan kontak darurat">
                                        @error('darurat_pekerjaan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="darurat_no_ktp" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                            <input type="text" name="darurat_no_ktp" id="darurat_no_ktp" value="{{ old('darurat_no_ktp') }}" required maxlength="16"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                                placeholder="16 digit NIK">
                                            @error('darurat_no_ktp')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="darurat_foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">Foto KTP</label>
                                            <label for="darurat_foto_ktp" class="cursor-pointer">
                                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#674c1d] transition-all text-center">
                                                    <span class="text-sm text-gray-600">Upload Foto KTP</span>
                                                </div>
                                                <input type="file" name="darurat_foto_ktp" id="darurat_foto_ktp" accept="image/*" class="hidden" onchange="previewImage(this, 'daruratKtpPreview')">
                                            </label>
                                            <div id="daruratKtpPreview" class="mt-2 hidden">
                                                <img id="daruratKtpPreviewImg" src="" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                                            </div>
                                            @error('darurat_foto_ktp')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
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

                                @if($step < 6)
                                    <button type="button" data-step="{{ $step + 1 }}" onclick="goToStep(this.dataset.step)" 
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        Lanjutkan
                                    </button>
                                @else
                                    <button type="submit" 
                                        class="px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                        Daftar
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
            
            // Validate step
            if (step < 1 || step > 6) {
                console.error('Invalid step:', step);
                return;
            }
            
            // Simpan data form ke sessionStorage
            const form = document.getElementById('registerForm');
            if (form) {
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== 'step' && key !== '_token' && key !== 'foto' && key !== 'foto_ktp' && key !== 'foto_kk' && key !== 'file_ktp' && key !== 'darurat_foto_ktp') {
                        data[key] = value;
                    }
                }
                sessionStorage.setItem('registerData', JSON.stringify(data));
            }
            
            // Redirect ke step berikutnya
            window.location.href = '{{ route("register") }}?step=' + step;
        }

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

