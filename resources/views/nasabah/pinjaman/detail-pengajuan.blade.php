@extends('layouts.nasabah')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pengajuan</h1>
                        <p class="text-white/90 text-sm">ID: #{{ str_pad($pengajuan->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="px-4 py-2 {{ $pengajuan->pinjaman ? 'bg-green-500' : 'bg-yellow-500' }} text-white rounded-full text-sm font-semibold">
                        {{ $pengajuan->pinjaman ? 'Disetujui' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Pengajuan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->tgl_pengajuan->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nominal Pinjaman</p>
                    <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis Angsuran</p>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($pengajuan->jenis) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Durasi</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->durasi }} bulan</p>
                </div>
                @if($pengajuan->keterangan)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                    <p class="font-medium text-gray-900">{{ $pengajuan->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Info -->
    @if($pengajuan->pinjaman)
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-green-800">Pengajuan Disetujui</h3>
            </div>
            <p class="text-sm text-green-700 mb-4">Pengajuan Anda telah disetujui. Pinjaman Anda sudah aktif.</p>
            <a href="{{ route('nasabah.pinjaman.detail-pinjaman', ['id' => $pengajuan->pinjaman->id]) }}" 
                class="inline-block bg-green-600 text-white font-semibold px-6 py-2 rounded-xl hover:bg-green-700 transition-all">
                Lihat Detail Pinjaman
            </a>
        </div>
    </div>
    @else
    <div class="mx-4 mb-6">
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-yellow-800">Menunggu Persetujuan</h3>
            </div>
            <p class="text-sm text-yellow-700">Pengajuan Anda sedang dalam proses review oleh admin. Kami akan menginformasikan hasilnya segera.</p>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mx-4 mb-6">
        <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" 
            class="inline-flex items-center gap-2 text-[#8b6f2f] font-semibold hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Status Pengajuan
        </a>
    </div>
</div>
@endsection
