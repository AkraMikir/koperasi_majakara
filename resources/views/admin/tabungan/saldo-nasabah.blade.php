@extends('layouts.admin')

@section('title', 'Saldo Nasabah')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Saldo Nasabah</h1>
            <p class="text-gray-600 mt-1">Daftar saldo tabungan per nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.tabungan.saldo-nasabah') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[[#674c1d]] focus:border-[[#674c1d]] outline-none">
            </div>
            <button type="submit" class="px-6 py-2 bg-linear-to-r from-[[#674c1d]] to-[[#8b6f2f]] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all shadow-md font-medium">
                Cari
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[[#674c1d]]/20 bg-linear-to-r from-[[#674c1d]]/5 to-[[#8b6f2f]]/5">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Total Setoran</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Total Penarikan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Saldo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nasabah as $item)
                    <tr class="border-b border-gray-100 hover:bg-linear-to-r hover:from-[[#674c1d]]/5 hover:to-[[#8b6f2f]]/5 transition-all">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-green-600">Rp {{ number_format($item->total_setoran ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-red-600">Rp {{ number_format($item->total_penarikan ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[[#674c1d]] text-lg">Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tabungan.transaksi') }}?search={{ $item->user->nama ?? '' }}" 
                                class="inline-flex items-center px-4 py-2 bg-linear-to-r from-[[#674c1d]] to-[[#8b6f2f]] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all text-sm font-medium">
                                Lihat Transaksi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada data nasabah</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($nasabah->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $nasabah->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
