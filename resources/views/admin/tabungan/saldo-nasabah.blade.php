@extends('layouts.admin')

@section('title', 'Saldo Nasabah')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-display">Saldo Nasabah</h1>
            <p class="text-gray-500 mt-1">Daftar saldo tabungan per nasabah</p>
        </div>
        <a href="{{ route('admin.tabungan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-gray-700 hover:border-[#674c1d]/40 hover:bg-[#674c1d]/5 transition-colors text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Cari</p>
        <form method="GET" action="{{ route('admin.tabungan.saldo-nasabah') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search-saldo" class="block text-sm font-medium text-gray-600 mb-1.5">Nama atau email nasabah</label>
                <input id="search-saldo" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/30 focus:border-[#674c1d] outline-none transition-colors placeholder:text-gray-400">
            </div>
            <div class="flex gap-2 pt-7 sm:pt-8">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </button>
                <a href="{{ route('admin.tabungan.saldo-nasabah') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Setoran</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Penarikan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Saldo Tertahan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Saldo Tersedia</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabah as $item)
                    <tr class="border-b border-gray-100 hover:bg-amber-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-block font-semibold text-emerald-700">Rp {{ number_format($item->total_setoran ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-block font-semibold text-rose-700">Rp {{ number_format($item->total_penarikan ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(($item->saldo_hold ?? 0) > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-700">Rp {{ number_format($item->saldo_hold, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg font-bold text-[#674c1d] bg-[#674c1d]/10">Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.tabungan.transaksi') }}?search={{ urlencode($item->user->nama ?? '') }}" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-sm hover:shadow font-semibold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat Transaksi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada data nasabah</p>
                                <p class="text-sm text-gray-400">Gunakan filter di atas untuk mencari</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($nasabah->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $nasabah->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
