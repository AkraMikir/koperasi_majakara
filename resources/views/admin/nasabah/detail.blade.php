@extends('layouts.admin')

@section('title', 'Detail Nasabah - ' . $nasabah->user->nama)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.nasabah.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-[#674c1d] mb-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Nasabah</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap nasabah</p>
        </div>
        @if($pendingChanges->count() > 0)
            <a href="{{ route('admin.nasabah.pending-changes') }}" class="px-5 py-3 bg-yellow-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                {{ $pendingChanges->count() }} Pengajuan Pending
            </a>
        @endif
    </div>

    <!-- Profile Header -->
    <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-2xl shadow-2xl p-8 border-2 border-[#d4af37]/30">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center overflow-hidden shadow-2xl ring-4 ring-white/30">
                @if($nasabah->user->foto && $nasabah->user->foto !== 'default-avatar.jpg')
                    <img src="{{ asset('storage/' . $nasabah->user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    <span class="text-5xl font-bold text-white">{{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-white mb-2 font-display">{{ $nasabah->user->nama }}</h2>
                <div class="flex flex-wrap gap-4 text-white/90">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $nasabah->user->email }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $nasabah->user->nomor_hp }}
                    </div>
                    @if($nasabah->dataKtp)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        NIK: {{ $nasabah->dataKtp->nik }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Pribadi -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-linear-to-r from-[#674c1d] to-[#8b6f2f] px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Data Pribadi
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Nama</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->user->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Email</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">No. HP</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->user->nomor_hp ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">No. KK</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->no_kk ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Tempat Lahir</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->tempat_lahir ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Tanggal Lahir</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Jenis Kelamin</span>
                        <span class="text-sm text-gray-900">
                            @if($nasabah->jenis_kelamin == 'L')
                                Laki-laki
                            @elseif($nasabah->jenis_kelamin == 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600 font-semibold">Alamat</span>
                        <span class="text-sm text-gray-900 text-right max-w-xs">{{ $nasabah->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data KTP -->
        @if($nasabah->dataKtp)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-linear-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    Data KTP
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">NIK</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->dataKtp->nik ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Nama Lengkap</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataKtp->nama_lengkap ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Tempat Lahir</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataKtp->tempat_lahir ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Tanggal Lahir</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataKtp->tanggal_lahir ? $nasabah->dataKtp->tanggal_lahir->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Jenis Kelamin</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataKtp->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600 font-semibold">Alamat</span>
                        <span class="text-sm text-gray-900 text-right max-w-xs">{{ $nasabah->dataKtp->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pekerjaan -->
        @if($nasabah->pekerjaan)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-linear-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Data Pekerjaan
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Pekerjaan</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->pekerjaan->pekerjaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Jabatan</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->pekerjaan->jabatan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Nama Perusahaan</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->pekerjaan->nama_perusahaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Bidang Usaha</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->pekerjaan->bidang_usaha ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600 font-semibold">Alamat Perusahaan</span>
                        <span class="text-sm text-gray-900 text-right max-w-xs">{{ $nasabah->pekerjaan->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Data Rekening -->
        @if($nasabah->dataRek)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-linear-to-r from-green-600 to-green-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Data Rekening
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Nama Bank</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataRek->nama_bank ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">No. Rekening</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->dataRek->no_rekening ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600 font-semibold">Atas Nama</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->dataRek->atas_nama ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Darurat -->
        @if($nasabah->darurat)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-linear-to-r from-red-600 to-red-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Kontak Darurat
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Nama Lengkap</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->darurat->nama_lengkap ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Hubungan</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->darurat->hubungan_peminjam ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">No. Telepon</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->darurat->no_telepon ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Email</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->darurat->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">Pekerjaan</span>
                        <span class="text-sm text-gray-900">{{ $nasabah->darurat->pekerjaan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600 font-semibold">NIK</span>
                        <span class="text-sm text-gray-900 font-mono">{{ $nasabah->darurat->no_ktp ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600 font-semibold">Alamat</span>
                        <span class="text-sm text-gray-900 text-right max-w-xs">{{ $nasabah->darurat->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Reset PIN Nasabah - Only for Admin Utama -->
    @canManageNasabah
    <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-red-100">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-linear-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 font-display">Reset PIN Nasabah</h2>
                <p class="text-sm text-gray-600 mt-1">Untuk nasabah yang lupa PIN transaksi</p>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-xl">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-sm">
                    <p class="font-semibold text-yellow-800 mb-1">Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-yellow-700">
                        <li>Pastikan nasabah benar-benar meminta reset PIN</li>
                        <li>Verifikasi identitas nasabah via WhatsApp/Telepon</li>
                        <li>Kirimkan PIN baru ke nasabah via WhatsApp dengan aman</li>
                        <li>Jangan share PIN di channel publik</li>
                    </ul>
                </div>
            </div>
        </div>

        <form id="form-reset-pin" action="{{ route('admin.nasabah.reset-pin', $nasabah->id) }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">PIN Baru (6 Digit) *</label>
                <div class="flex gap-3">
                    <input type="text" name="pin_baru" id="pin_baru" maxlength="6" required
                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-red-600 focus:ring-2 focus:ring-red-600/20 outline-none text-center text-2xl font-bold tracking-widest"
                        placeholder="••••••"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button type="button" onclick="generateRandomPinAdmin()" class="px-6 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Generate Random
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Klik "Generate Random" untuk membuat PIN acak 6 digit</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Langkah Selanjutnya:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Klik "Reset PIN" untuk menyimpan PIN baru</li>
                            <li>Copy PIN yang sudah dibuat</li>
                            <li>Kirim PIN ke nasabah via WhatsApp: <strong class="font-mono">{{ $nasabah->user->nomor_hp }}</strong></li>
                            <li>Pastikan nasabah menerima dan mencatat PIN baru</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" onclick="return confirm('Yakin reset PIN nasabah ini? PIN lama akan diganti dengan PIN baru.')" 
                    class="flex-1 px-6 py-4 bg-linear-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Reset PIN Nasabah
                </button>
                <a href="https://wa.me/62{{ ltrim($nasabah->user->nomor_hp, '0') }}" target="_blank"
                    class="px-6 py-4 bg-linear-to-r from-green-600 to-green-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Kirim via WhatsApp
                </a>
            </div>
        </form>
    </div>
    @endcanManageNasabah

    <!-- Pending Changes Alert (jika ada) -->
    @if($pendingChanges->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-xl p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-yellow-900 mb-2">Pengajuan Perubahan Data Pending</h3>
                <p class="text-yellow-800 mb-4">Nasabah ini memiliki <strong>{{ $pendingChanges->count() }}</strong> pengajuan perubahan data yang menunggu persetujuan Anda.</p>
                <a href="{{ route('admin.nasabah.pending-changes') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-yellow-500 text-white rounded-xl font-semibold hover:bg-yellow-600 transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lihat Semua Pengajuan Pending
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
async function generateRandomPinAdmin() {
    try {
        const response = await fetch('{{ route("admin.nasabah.generate-pin") }}');
        const data = await response.json();
        
        if (data.pin) {
            document.getElementById('pin_baru').value = data.pin;
            
            // Copy to clipboard
            navigator.clipboard.writeText(data.pin).then(() => {
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50';
                toast.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-semibold">PIN disalin ke clipboard!</span>
                    </div>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => toast.remove(), 3000);
            }).catch(() => {
                alert('PIN: ' + data.pin + '\n\nSilakan copy manual.');
            });
        }
    } catch (error) {
        alert('Gagal generate PIN. Silakan coba lagi.');
    }
}
</script>
@endpush

@endsection
