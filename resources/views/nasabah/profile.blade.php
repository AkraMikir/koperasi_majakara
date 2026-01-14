@extends('layouts.nasabah')

@section('title', 'Profile')

@section('content')
<div class="w-full pb-6">
    <!-- Profile Header -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <!-- Foto Profil -->
                    <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center overflow-hidden shadow-2xl ring-4 ring-white/30 flex-shrink-0">
                        @if($nasabah->user->foto && $nasabah->user->foto !== 'default-avatar.jpg')
                            <img src="{{ asset('storage/' . $nasabah->user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <span class="text-5xl font-bold text-white">{{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}</span>
                        @endif
                    </div>
                    
                    <!-- Info Profil -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 font-display">{{ $nasabah->user->nama ?? 'N/A' }}</h1>
                        <p class="text-white/90 text-sm mb-4">{{ $nasabah->user->email ?? 'N/A' }}</p>
                        
                        <!-- Status Badge -->
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-4">
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-semibold border border-white/30">
                                Nasabah Aktif
                            </span>
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-semibold border border-white/30">
                                Saldo: Rp {{ number_format($saldoTabungan, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <!-- Quick Info -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                <p class="text-white/80 text-xs mb-1">No. HP</p>
                                <p class="text-white font-semibold text-sm">{{ $nasabah->user->nomor_hp ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                <p class="text-white/80 text-xs mb-1">NIK</p>
                                <p class="text-white font-semibold text-sm">{{ $nasabah->dataKtp->nik ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                <p class="text-white/80 text-xs mb-1">No. KK</p>
                                <p class="text-white font-semibold text-sm">{{ $nasabah->no_kk ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pribadi -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Pribadi</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataKtp->nama_lengkap ?? $nasabah->user->nama ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">NIK</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataKtp->nik ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tempat, Tanggal Lahir</p>
                    <p class="font-semibold text-gray-900">
                        {{ $nasabah->tempat_lahir ?? ($nasabah->dataKtp->tempat_lahir ?? 'N/A') }}, 
                        {{ $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format('d M Y') : ($nasabah->dataKtp->tanggal_lahir ? $nasabah->dataKtp->tanggal_lahir->format('d M Y') : 'N/A') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Jenis Kelamin</p>
                    <p class="font-semibold text-gray-900">
                        @if($nasabah->jenis_kelamin)
                            {{ $nasabah->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        @elseif($nasabah->dataKtp)
                            {{ $nasabah->dataKtp->jenis_kelamin ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Alamat</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->alamat ?? ($nasabah->dataKtp->alamat ?? 'N/A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pekerjaan -->
    @if($nasabah->pekerjaan)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Pekerjaan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pekerjaan</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->pekerjaan->pekerjaan ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Perusahaan</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->pekerjaan->nama_perusahaan ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Penghasilan</p>
                    <p class="font-semibold text-[#674c1d] text-xl">Rp {{ number_format($nasabah->pekerjaan->penghasilan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Rekening Bank -->
    @if($nasabah->dataRek)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Rekening Bank</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Bank</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataRek->nama_bank ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">No. Rekening</p>
                    <p class="font-semibold text-gray-900 font-mono">{{ $nasabah->dataRek->no_rekening ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Nama Pemilik Rekening</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataRek->nama_pemilik_rekening ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Kontak Darurat -->
    @if($nasabah->darurat)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Kontak Darurat</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->nama_lengkap ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Hubungan</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->hubungan_peminjam ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">No. Telepon</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->no_telepon ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pekerjaan</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->pekerjaan ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">NIK</p>
                    <p class="font-semibold text-gray-900 font-mono">{{ $nasabah->darurat->no_ktp ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Alamat</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->darurat->alamat ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistik Tabungan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Statistik Tabungan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <p class="text-sm text-green-700 mb-1">Saldo Tabungan</p>
                    <p class="text-2xl font-bold text-green-800">Rp {{ number_format($saldoTabungan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <p class="text-sm text-blue-700 mb-1">Total Setoran</p>
                    <p class="text-2xl font-bold text-blue-800">Rp {{ number_format($nasabah->transTabungan->where('jenis', 'setoran')->sum('nominal') ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                    <p class="text-sm text-red-700 mb-1">Total Penarikan</p>
                    <p class="text-2xl font-bold text-red-800">Rp {{ number_format($nasabah->transTabungan->where('jenis', 'penarikan')->sum('nominal') ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
