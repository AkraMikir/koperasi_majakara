@extends('layouts.nasabah')

@section('title', 'Detail Pengajuan Penarikan')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#d4af37] to-[#674c1d] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Pengajuan Penarikan</h1>
                        <p class="text-white/90 text-sm">ID: #{{ $pengajuan->id }}</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="mx-4 mb-6 space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status Pengajuan</p>
                    @php
                        $statusConfig = [
                            '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu Persetujuan'],
                            '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                            '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                        ];
                        $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                    @endphp
                    <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                        {{ $status['label'] }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Pengajuan</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Nominal Penarikan</p>
                    <p class="text-3xl font-bold text-[#8b6f2f]">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                </div>

                @if($pengajuan->keterangan)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-900">{{ $pengajuan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
