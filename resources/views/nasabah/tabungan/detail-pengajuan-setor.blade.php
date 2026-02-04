@extends('layouts.nasabah')

@section('title', 'Detail Pengajuan Setoran')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Pengajuan Setoran</h1>
                        <p class="text-white/90 text-sm">ID: #{{ $pengajuan->id }}</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors">
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
                    <p class="text-sm text-gray-600 mb-2">Total Nominal</p>
                    <p class="text-3xl font-bold text-[#674c1d]">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                </div>

                @if($pengajuan->keterangan)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-900">{{ $pengajuan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Bukti Foto -->
        @if($pengajuan->buktiFoto->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Bukti Transfer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pengajuan->buktiFoto as $bukti)
                <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" 
                    onclick="showPhotoPreview('{{ asset('storage/' . $bukti->file_path) }}', 'Bukti Transfer #{{ $loop->iteration }}')">
                    <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Transfer" class="w-full h-48 object-cover">
                    <div class="p-4 bg-gray-50">
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                            Klik untuk memperbesar
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Note: Janji temu removed - sekarang independent, hanya untuk setoran tunai -->
    </div>
</div>
@endsection
