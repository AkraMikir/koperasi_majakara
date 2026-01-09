@extends('layouts.admin')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Janji Temu</h1>
            <p class="text-gray-600 mt-1">ID Janji Temu: #{{ $janjiTemu->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.janji-temu') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->pengajuan->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Janji Temu -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Janji Temu</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                        <p class="text-xs text-gray-500">{{ $janjiTemu->lokasi->kota ?? '' }}, {{ $janjiTemu->lokasi->provinsi ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan</p>
                        <p class="font-semibold text-gray-900">#{{ $janjiTemu->pengajuan->id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Status</h3>
                <div class="space-y-3">
                    @php
                        $isPast = $janjiTemu->tanggal_janji_temu < now();
                        $statusColor = $isPast ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                        $statusLabel = $isPast ? 'Selesai' : 'Akan Datang';
                    @endphp
                    <span class="inline-block px-4 py-2 {{ $statusColor }} rounded-full text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Tersisa</p>
                        <p class="font-semibold text-gray-900">
                            @if($isPast)
                                Sudah lewat
                            @else
                                {{ $janjiTemu->tanggal_janji_temu->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
