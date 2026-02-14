@extends('layouts.admin')

@section('title', 'Pengajuan Pembayaran Pinjaman')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <p class="text-red-800 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Pengajuan Pembayaran Pinjaman</h1>
            <p class="text-gray-600 mt-1">Kelola pengajuan pembayaran dari nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.pinjaman.pembayaran') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Ditolak</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Disetujui</option>
                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Terlaksana</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium">
                Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px]">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/8 to-[#8b6f2f]/8">
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide w-40">ID</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Nasabah</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide w-32">Pinjaman</th>
                        <th class="px-5 py-4 text-right text-xs font-bold text-[#674c1d] uppercase tracking-wide w-36">Nominal</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase tracking-wide w-28">Tanggal</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase tracking-wide w-28">Status</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase tracking-wide w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $item)
                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-amber-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="text-sm font-mono font-medium text-gray-700">#{{ $item->id }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 truncate max-w-[200px]">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-mono text-sm font-medium text-gray-800">#{{ $item->pinjaman_id }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="font-semibold text-[#674c1d]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-center text-sm text-gray-600">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @php
                                $statusConfig = [
                                    '1' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'Pending'],
                                    '2' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    '3' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'label' => 'Disetujui'],
                                    '4' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'Terlaksana'],
                                ];
                                $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                            @endphp
                            <span class="inline-block px-3 py-1.5 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg text-xs font-semibold border border-current/10">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.pinjaman.detail-pembayaran', $item->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#5a4018] transition-colors text-xs font-medium shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-gray-500 font-medium">Tidak ada pengajuan pembayaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
