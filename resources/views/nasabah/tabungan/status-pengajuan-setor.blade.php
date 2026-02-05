@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Setoran')

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
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Status Pengajuan Setoran</h1>
                        <p class="text-white/90 text-sm">Lihat status pengajuan setoran Anda</p>
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

    <!-- List Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="space-y-4">
            @forelse($pengajuan ?? [] as $item)
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <p class="text-sm text-gray-600">ID Pengajuan</p>
                            <p class="text-lg font-bold text-[#674c1d]">#{{ $item->id }}</p>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Via Transfer
                            </span>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusConfig = [
                                '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu'],
                                '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                                '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                            ];
                            $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Nominal</p>
                        <p class="font-semibold text-[#674c1d] text-lg">Rp {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($item->keterangan)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Keterangan Anda</p>
                    <p class="text-sm text-gray-900">{{ $item->keterangan }}</p>
                </div>
                @endif

                @if($item->keterangan_admin)
                <div class="mb-4 p-3 {{ $item->status == '2' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }} rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 {{ $item->status == '2' ? 'text-green-600' : 'text-red-600' }} mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold {{ $item->status == '2' ? 'text-green-700' : 'text-red-700' }} mb-1">
                                Catatan dari Admin
                            </p>
                            <p class="text-sm {{ $item->status == '2' ? 'text-green-900' : 'text-red-900' }}">{{ $item->keterangan_admin }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <a href="{{ route('nasabah.tabungan.detail-pengajuan-setor', $item->id) }}" 
                        class="px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-sm font-medium">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">Belum ada pengajuan setoran</p>
                <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" 
                    class="inline-block px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                    Buat Pengajuan Setoran
                </a>
            </div>
            @endforelse
        </div>

        @if($pengajuan && $pengajuan->hasPages())
        <div class="mt-6">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
