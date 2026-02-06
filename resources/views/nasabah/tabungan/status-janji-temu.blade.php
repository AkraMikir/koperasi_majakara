@extends('layouts.nasabah')

@section('title', 'Status Janji Temu')

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
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Status Janji Temu</h1>
                        <p class="text-white/90 text-sm">Jadwal setoran tunai di kantor</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" 
                    class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-4 mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800">
        {{ session('success') }}
    </div>
    @endif

    <!-- List Janji Temu -->
    <div class="mx-4 mb-6">
        <div class="space-y-4">
            @forelse($janjiTemu ?? [] as $item)
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600">ID Janji Temu</p>
                        <p class="text-lg font-bold text-[#674c1d]">#{{ $item->id }}</p>
                    </div>
                    @php
                        // Combine date and time for accurate isPast check
                        $dateTime = \Carbon\Carbon::parse($item->tanggal_janji_temu);
                        if (isset($item->waktu_janji_temu)) {
                            $time = \Carbon\Carbon::parse($item->waktu_janji_temu);
                            $dateTime->setTime($time->hour, $time->minute, $time->second);
                        }
                        $isPast = $dateTime->isPast();
                        $statusColor = $isPast ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700';
                        $statusLabel = $isPast ? 'Sudah Lewat' : 'Akan Datang';
                    @endphp
                    <span class="px-4 py-2 {{ $statusColor }} rounded-full text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $item->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-lg">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $item->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->lokasi->kota ?? '' }}, {{ $item->lokasi->provinsi ?? '' }}</p>
                    </div>
                </div>

                @if($item->keterangan)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Keterangan</p>
                    <p class="text-sm text-gray-900">{{ $item->keterangan }}</p>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <a href="{{ route('nasabah.tabungan.detail-janji-temu', $item->id) }}" 
                        class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-sm font-medium">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">Belum ada janji temu</p>
                <a href="{{ route('nasabah.tabungan.janji-temu') }}" 
                    class="inline-block px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Buat Janji Temu
                </a>
            </div>
            @endforelse
        </div>

        @if($janjiTemu && $janjiTemu->hasPages())
        <div class="mt-6">
            {{ $janjiTemu->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
