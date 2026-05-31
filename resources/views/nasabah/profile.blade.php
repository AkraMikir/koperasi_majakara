@extends('layouts.nasabah')

@section('title', 'Profile')

@section('content')
<div class="w-full pb-6">
    <!-- Alerts -->
    <div class="mx-4 mt-4">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-sm mb-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm mb-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm mb-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Terdapat kesalahan pada input Anda:</p>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Profile Header -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 flex-1">
                        <!-- Foto Profil -->
                        <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center overflow-hidden shadow-2xl ring-4 ring-white/30 shrink-0">
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
                                Saldo: Rp {{ number_format((float)$saldoTabungan, 0, ',', '.') }}
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
                    
                    <!-- Edit Button for User Profile -->
                    <button onclick="openEditModal('data_user')" class="px-4 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-xl transition-all font-semibold text-sm flex items-center gap-2 border border-white/30 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Notification -->
    @if($pendingRequests->count() > 0)
    <div class="mx-4 mb-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-xl p-4 shadow-md">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="font-bold text-yellow-800 mb-2">Pengajuan Perubahan Data Sedang Diproses</h3>
                    <p class="text-sm text-yellow-700 mb-3">Anda memiliki {{ $pendingRequests->count() }} pengajuan perubahan data yang menunggu persetujuan admin.</p>
                    <div class="space-y-2">
                        @foreach($pendingRequests as $request)
                        <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-yellow-200">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                    {{ $request->jenis_data_label }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    Diajukan {{ $request->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <form action="{{ route('nasabah.profile.cancel-request', $request->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Pribadi -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Pribadi</h2>
                </div>
                <button onclick="openEditModal('data_pribadi')" class="px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-lg transition-all font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
            </div>

            <!-- Info Note -->
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs text-blue-800">
                        <strong>Catatan:</strong> Untuk mengedit <strong>Nama</strong> dan <strong>Email</strong> yang tampil di header profil, gunakan tombol <strong>"Edit Profil"</strong> di bagian atas. Tombol "Edit" di sini untuk mengedit data pribadi lengkap (KTP, tempat lahir, alamat, dll).
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataKtp->nama_lengkap ?? $nasabah->user->nama ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nomor HP</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->user->nomor_hp ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">NIK</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->dataKtp->nik ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">No. KK</p>
                    <p class="font-semibold text-gray-900">{{ $nasabah->no_kk ?? 'N/A' }}</p>
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
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Pekerjaan</h2>
                </div>
                <button onclick="openEditModal('pekerjaan')" class="px-4 py-2 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-lg hover:shadow-lg transition-all font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
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
                    <p class="font-semibold text-[#674c1d] text-xl">{{ $nasabah->pekerjaan->penghasilan ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Rekening Bank -->
    @if($nasabah->dataRek)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Data Rekening Bank</h2>
                </div>
                <button onclick="openEditModal('rekening')" class="px-4 py-2 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-lg hover:shadow-lg transition-all font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
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
                <div class="w-10 h-10 bg-linear-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center">
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
                <div class="w-10 h-10 bg-linear-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Statistik Tabungan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-linear-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <p class="text-sm text-green-700 mb-1">Saldo Tabungan</p>
                    <p class="text-2xl font-bold text-green-800">Rp {{ number_format((float)$saldoTabungan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-linear-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <p class="text-sm text-blue-700 mb-1">Total Setoran</p>
                    <p class="text-2xl font-bold text-blue-800">Rp {{ number_format((float)($nasabah->transTabungan->where('jenis', 'setoran')->sum('nominal') ?? 0), 0, ',', '.') }}</p>
                </div>
                <div class="bg-linear-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                    <p class="text-sm text-red-700 mb-1">Total Penarikan</p>
                    <p class="text-2xl font-bold text-red-800">Rp {{ number_format((float)($nasabah->transTabungan->where('jenis', 'penarikan')->sum('nominal') ?? 0), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Keamanan & Privasi - Link ke Setting -->
    <div class="mx-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-2xl shadow-lg p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white font-display">Keamanan & Privasi</h2>
                        <p class="text-white/90 text-sm">Kelola password dan PIN transaksi</p>
                    </div>
                    </div>
                <a href="{{ route('nasabah.setting.index') }}" 
                    class="px-5 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-xl transition-all font-semibold text-sm flex items-center gap-2 border border-white/30">
                    Buka Setting
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data Profil & PIN Verification -->
<div id="modalEditProfile" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] p-6 rounded-t-2xl z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white" id="editModalTitle">Edit Data</h3>
                </div>
                <button onclick="closeEditModal()" type="button" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="editProfileForm" action="{{ route('nasabah.profile.update-request') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" id="edit_jenis_data" name="jenis_data" value="">
            
            <!-- Step 1: Data Input -->
            <div id="step1">
                <!-- Form fields will be injected here by JavaScript -->
                <div id="editFormFields"></div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-blue-800">
                            <strong>Catatan:</strong> Perubahan data akan masuk ke dalam antrian persetujuan dan memerlukan verifikasi PIN. Data akan diperbarui setelah admin menyetujui perubahan Anda.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="goToStep2()" 
                            class="flex-1 px-6 py-3 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all shadow-md hover:shadow-lg">
                        Lanjutkan
                    </button>
                </div>
            </div>

            <!-- Step 2: PIN Verification -->
            <div id="step2" class="hidden">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi PIN</h3>
                    <p class="text-gray-600">Masukkan PIN Anda untuk mengajukan perubahan data</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 text-center">
                            PIN <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="pin" id="pin_verification" maxlength="6" 
                               class="w-full max-w-xs mx-auto block px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] text-center text-2xl font-bold tracking-widest"
                               placeholder="••••••">
                        <p class="text-xs text-gray-500 mt-2 text-center">Masukkan PIN 6 digit Anda</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="goToStep1()" 
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                        Kembali
                    </button>
                    <button type="submit" id="submitBtn"
                            class="flex-1 px-6 py-3 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#8b6f2f] hover:to-[#674c1d] transition-all shadow-md hover:shadow-lg">
                        Ajukan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT PROFILE FUNCTIONS ====================-->
<script>
// Data template untuk setiap jenis data
const formTemplates = {
    'data_user': {
        title: 'Edit Data Akun User',
        fields: [
            { name: 'foto', label: 'Foto Profil', type: 'file', accept: 'image/*', note: 'Biarkan kosong jika tidak ingin mengubah' },
            { name: 'nama', label: 'Nama (Display Name)', type: 'text', value: '{{ $nasabah->user->nama ?? "" }}' },
            { name: 'email', label: 'Email', type: 'email', value: '{{ $nasabah->user->email ?? "" }}' },
            { name: 'nomor_hp', label: 'Nomor HP', type: 'text', value: '{{ $nasabah->user->nomor_hp ?? "" }}' }
        ]
    },
    'data_pribadi': {
        title: 'Edit Data Pribadi',
        fields: [
            { name: 'nama', label: 'Nama Lengkap', type: 'text', value: '{{ $nasabah->dataKtp->nama_lengkap ?? $nasabah->user->nama ?? "" }}' },
            { name: 'email', label: 'Email', type: 'email', value: '{{ $nasabah->user->email ?? "" }}' },
            { name: 'nomor_hp', label: 'Nomor HP', type: 'text', value: '{{ $nasabah->user->nomor_hp ?? "" }}' },
            { name: 'no_kk', label: 'No. KK', type: 'text', value: '{{ $nasabah->no_kk ?? "" }}' },
            { name: 'tempat_lahir', label: 'Tempat Lahir', type: 'text', value: '{{ $nasabah->tempat_lahir ?? ($nasabah->dataKtp->tempat_lahir ?? "") }}' },
            { name: 'tanggal_lahir', label: 'Tanggal Lahir', type: 'date', value: '{{ $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format("Y-m-d") : ($nasabah->dataKtp && $nasabah->dataKtp->tanggal_lahir ? $nasabah->dataKtp->tanggal_lahir->format("Y-m-d") : "") }}' },
            { name: 'jenis_kelamin', label: 'Jenis Kelamin', type: 'select', value: '{{ $nasabah->jenis_kelamin ?? "" }}', options: [{value: 'L', label: 'Laki-laki'}, {value: 'P', label: 'Perempuan'}] },
            { name: 'alamat', label: 'Alamat', type: 'textarea', value: '{{ $nasabah->alamat ?? ($nasabah->dataKtp->alamat ?? "") }}' }
        ]
    },
    'pekerjaan': {
        title: 'Edit Data Pekerjaan',
        fields: [
            { name: 'pekerjaan', label: 'Pekerjaan', type: 'text', value: '{{ $nasabah->pekerjaan->pekerjaan ?? "" }}' },
            { name: 'nama_perusahaan', label: 'Nama Perusahaan', type: 'text', value: '{{ $nasabah->pekerjaan->nama_perusahaan ?? "" }}' },
            { name: 'penghasilan', label: 'Penghasilan', type: 'text', value: '{{ $nasabah->pekerjaan->penghasilan ?? "" }}' }
        ]
    },
    'rekening': {
        title: 'Edit Data Rekening Bank',
        fields: [
            { name: 'nama_bank', label: 'Nama Bank', type: 'text', value: '{{ $nasabah->dataRek->nama_bank ?? "" }}' },
            { name: 'no_rekening', label: 'No. Rekening', type: 'text', value: '{{ $nasabah->dataRek->no_rekening ?? "" }}' },
            { name: 'nama_pemilik_rekening', label: 'Nama Pemilik Rekening', type: 'text', value: '{{ $nasabah->dataRek->nama_pemilik_rekening ?? "" }}' }
        ]
    }
};

// Open edit modal
function openEditModal(jenisData) {
    const modal = document.getElementById('modalEditProfile');
    const title = document.getElementById('editModalTitle');
    const formFields = document.getElementById('editFormFields');
    const jenisDataInput = document.getElementById('edit_jenis_data');
    
    // Reset form to step 1
    goToStep1();
    
    // Reset form inputs (including file)
    document.getElementById('editProfileForm').reset();
    
    const template = formTemplates[jenisData];
    if (!template) {
        alert('Template tidak ditemukan');
        return;
    }
    
    // Set title dan jenis_data
    title.textContent = template.title;
    jenisDataInput.value = jenisData;
    
    // Generate form fields
    let html = '<div class="space-y-4">';
    template.fields.forEach(field => {
        html += '<div>';
        html += `<label class="block text-sm font-semibold text-gray-700 mb-2">${field.label} ${field.type !== 'file' ? '<span class="text-red-500">*</span>' : ''}</label>`;
        
        if (field.note) {
             html += `<p class="text-xs text-gray-500 mb-2">${field.note}</p>`;
        }

        if (field.type === 'textarea') {
            html += `<textarea name="${field.name}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]" rows="3" required>${field.value}</textarea>`;
        } else if (field.type === 'select') {
            html += `<select name="${field.name}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]" required>`;
            html += `<option value="">Pilih ${field.label}</option>`;
            field.options.forEach(opt => {
                const selected = opt.value === field.value ? 'selected' : '';
                html += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
            });
            html += '</select>';
        } else if (field.type === 'file') {
            html += `
            <div class="mt-2 flex justify-center rounded-xl border border-dashed border-gray-300 px-6 py-8 hover:border-[#8b6f2f] transition-colors relative bg-gray-50 cursor-pointer" onclick="document.getElementById('${field.name}_input').click()">
                <div class="text-center">
                    <img id="${field.name}_preview" class="mx-auto h-24 w-24 rounded-full object-cover mb-4 hidden border-4 border-white shadow-lg" src="#" alt="Preview" />
                    <div id="${field.name}_icon">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                        <span class="relative cursor-pointer rounded-md font-semibold text-[#8b6f2f] hover:text-[#674c1d]">
                            <span>Upload foto</span>
                            <input id="${field.name}_input" name="${field.name}" type="file" class="sr-only" accept="${field.accept}" onchange="previewImage(this, '${field.name}_preview', '${field.name}_icon')">
                        </span>
                    </div>
                    <p class="text-xs leading-5 text-gray-500 mt-1">PNG, JPG, JPEG up to 2MB</p>
                </div>
            </div>`;
        } else {
            html += `<input type="${field.type}" name="${field.name}" value="${field.value}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f]" required>`;
        }
        
        html += '</div>';
    });
    html += '</div>';
    
    formFields.innerHTML = html;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function previewImage(input, previewId, iconId) {
    const preview = document.getElementById(previewId);
    const icon = document.getElementById(iconId);
    
    if (input.files && input.files[0]) {
        // Validate file size (max 2MB)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
    }
}

// Close edit modal
function closeEditModal() {
    const modal = document.getElementById('modalEditProfile');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function goToStep2() {
    // Validate required fields in step 1
    const form = document.getElementById('editProfileForm');
    let isValid = true;
    
    // Simple validation for required fields in Step 1
    const step1Inputs = document.getElementById('step1').querySelectorAll('input[required], select[required], textarea[required]');
    step1Inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi semua kolom yang wajib diisi.');
        return;
    }

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    document.getElementById('editModalTitle').textContent = 'Verifikasi PIN';
    document.getElementById('pin_verification').focus();
    document.getElementById('pin_verification').setAttribute('required', 'required');
}

function goToStep1() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
    
    const jenisData = document.getElementById('edit_jenis_data').value;
    const template = formTemplates[jenisData];
    document.getElementById('editModalTitle').textContent = template ? template.title : 'Edit Data';
    document.getElementById('pin_verification').removeAttribute('required');
}

// PIN input: only numbers
document.addEventListener('DOMContentLoaded', function() {
    const pinInput = document.getElementById('pin_verification');
    if (pinInput) {
        pinInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }

    // Submit button handling to prevent double submission
    const form = document.getElementById('editProfileForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            }
        });
    }
});
</script>

@endsection
