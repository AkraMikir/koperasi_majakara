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
                        <p class="font-semibold text-gray-900">
                            {{ $janjiTemu->tanggal_janji_temu->format('d M Y') }}
                            @if(!empty($janjiTemu->waktu_janji_temu))
                                , {{ \Carbon\Carbon::parse($janjiTemu->waktu_janji_temu)->format('H:i') }}
                            @endif
                        </p>
                        @if(!$isPast)
                        <p class="text-xs text-gray-500 mt-1">{{ $janjiTemu->tanggal_janji_temu->diffForHumans() }}</p>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm text-gray-600">Nominal</p>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $janjiTemu->jenis ?? 'setoran' }}
                            </span>
                        </div>
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

        <!-- Status Janji Temu (setoran tunai diproses terpisah dari pengajuan transfer) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Status</h2>
            @php
                // Combine date and time for accurate isPast check
                $dateTime = \Carbon\Carbon::parse($janjiTemu->tanggal_janji_temu);
                if (!empty($janjiTemu->waktu_janji_temu)) {
                    $time = \Carbon\Carbon::parse($janjiTemu->waktu_janji_temu);
                    $dateTime->setTime($time->hour, $time->minute, $time->second);
                }
                $isPast = $dateTime->isPast();

                // Status Logic based on DB Status first, then Time
                if ($janjiTemu->status == '2') {
                    $statusLabel = 'Selesai';
                    $statusClass = 'bg-green-100 text-green-700';
                } elseif ($janjiTemu->status == '3') {
                    $statusLabel = 'Dibatalkan';
                    $statusClass = 'bg-red-100 text-red-700';
                } elseif ($isPast) {
                    $statusLabel = 'Terlewat';
                    $statusClass = 'bg-gray-100 text-gray-600';
                } else {
                    $statusLabel = 'Akan Datang';
                    $statusClass = 'bg-amber-100 text-amber-700';
                }
            @endphp
            <span class="inline-block px-4 py-2 {{ $statusClass }} rounded-full text-sm font-semibold">
                {{ $statusLabel }}
            </span>
            @if($janjiTemu->keterangan)
            <p class="text-sm text-gray-600 mt-3">{{ $janjiTemu->keterangan }}</p>
            @endif
        </div>

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
