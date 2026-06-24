@extends('layouts.nasabah')

@section('title', 'Status Pembayaran Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.pinjaman.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Status Pembayaran Pinjaman</h1>
                <p class="text-white/90 text-sm">Lihat status pengajuan pembayaran Anda</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Filter -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <form method="GET" action="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="flex flex-col sm:flex-row gap-3">
                <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ditolak</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Disetujui</option>
                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Terlaksana</option>
                </select>
                <button type="submit" class="px-6 py-3 bg-[#8b6f2f] text-white rounded-lg hover:bg-[#a0824d] transition-colors">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- List Pembayaran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Riwayat Pembayaran</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Pinjaman</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Metode</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan ?? [] as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" 
                            onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pembayaran', $item->id) }}'">
                            <td class="px-4 py-4 text-sm">
                                <p class="font-medium text-gray-900">{{ $item->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">Pinjaman #{{ str_pad($item->pinjaman_id, 6, '0', STR_PAD_LEFT) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @if($item->rekening_tujuan)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    Transfer
                                </span>
                                @else
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                    Cash
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusConfig = [
                                        '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        '2' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                        '3' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Disetujui'],
                                        '4' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Terlaksana'],
                                    ];
                                    $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                                @endphp
                                <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada riwayat pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($pengajuan) && $pengajuan->hasPages())
            <div class="mt-6">
                {{ $pengajuan->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
