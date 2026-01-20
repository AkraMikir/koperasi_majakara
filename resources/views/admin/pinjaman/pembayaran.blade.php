@extends('layouts.admin')

@section('title', 'Pengajuan Pembayaran Pinjaman')

@section('content')
<div class="space-y-6">
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
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Pinjaman</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">#{{ str_pad($item->pinjaman_id, 6, '0', STR_PAD_LEFT) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
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
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
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
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pinjaman.detail-pembayaran', $item->id) }}" 
                                class="px-3 py-1 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-xs font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <p class="text-gray-500">Tidak ada pengajuan pembayaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
