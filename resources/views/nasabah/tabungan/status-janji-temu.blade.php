@extends('layouts.nasabah')

@section('title', 'Status Janji Temu')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#d4af37] via-[#8b6f2f] to-[#674c1d] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
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
                <a href="{{ route('nasabah.tabungan.index') }}" 
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
    <div class="mx-4 mb-6">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-emerald-100 p-5 flex items-start gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 to-teal-50/50 pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#d4af37]/10 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="relative flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200/50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative flex-1 min-w-0">
                <p class="font-bold text-emerald-800 text-lg mb-1">Berhasil!</p>
                <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p>
                <p class="text-gray-500 text-xs mt-2.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Janji temu Anda tercatat. Silakan datang di waktu yang telah dipilih.
                </p>
            </div>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 mb-6">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-red-100 p-5 flex items-start gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50/60 to-rose-50/40 pointer-events-none"></div>
            <div class="relative flex-shrink-0 w-14 h-14 bg-gradient-to-br from-red-400 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200/40">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative flex-1 min-w-0">
                <p class="font-bold text-red-800 text-lg mb-1">Perhatian</p>
                <p class="text-gray-700 text-sm">{{ session('error') }}</p>
            </div>
        </div>
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
                        if (!empty($item->waktu_janji_temu)) {
                            $time = \Carbon\Carbon::parse($item->waktu_janji_temu);
                            $dateTime->setTime($time->hour, $time->minute, $time->second);
                        }
                        $isPast = $dateTime->isPast();
                        
                        // Status Logic based on DB Status first, then Time
                        if ($item->status == '2') {
                            $statusLabel = 'Selesai';
                            $statusClass = 'bg-green-100 text-green-700';
                        } elseif ($item->status == '3') {
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
                    <span class="px-4 py-2 {{ $statusClass }} rounded-full text-sm font-semibold">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">
                            {{ $item->tanggal_janji_temu->format('d M Y') }}
                            @if(!empty($item->waktu_janji_temu))
                                , {{ \Carbon\Carbon::parse($item->waktu_janji_temu)->format('H:i') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-lg">Rp {{ number_format($item->transTabungan?->nominal ?? $item->nominal, 0, ',', '.') }}</p>
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
                        class="px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-sm font-medium">
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
                    class="inline-block px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
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
