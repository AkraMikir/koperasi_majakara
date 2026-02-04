@extends('layouts.nasabah')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#d4af37] via-[#8b6f2f] to-[#674c1d] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Janji Temu</h1>
                        <p class="text-white/90 text-sm">ID: #{{ $janjiTemu->id }}</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="mx-4 mb-6 space-y-6">

        <!-- Informasi Janji Temu -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Janji Temu</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                        @if(!$isPast)
                        <p class="text-xs text-gray-500 mt-1">{{ $janjiTemu->tanggal_janji_temu->diffForHumans() }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nominal</p>
                        <p class="text-2xl font-bold text-[#674c1d]">Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Lokasi</p>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="font-semibold text-gray-900 mb-1">{{ $janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $janjiTemu->lokasi->kota ?? '' }}, {{ $janjiTemu->lokasi->provinsi ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        @if($janjiTemu->pengajuan)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Pengajuan</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">ID Pengajuan</p>
                    <p class="font-semibold text-gray-900">#{{ $janjiTemu->pengajuan->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status Pengajuan</p>
                    @php
                        $statusConfig = [
                            '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu'],
                            '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                            '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                        ];
                        $status = $statusConfig[$janjiTemu->pengajuan->status] ?? $statusConfig['1'];
                    @endphp
                    <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                        {{ $status['label'] }}
                    </span>
                </div>
                @if($janjiTemu->pengajuan->keterangan)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-900">{{ $janjiTemu->pengajuan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Map/Location Info -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Peta Lokasi</h2>
            <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center border border-gray-200">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-gray-500">Peta lokasi akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
