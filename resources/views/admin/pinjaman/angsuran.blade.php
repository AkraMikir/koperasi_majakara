@extends('layouts.admin')

@section('title', 'Angsuran Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Angsuran Pinjaman</h1>
            <p class="text-gray-600 mt-1">Kelola jadwal dan pembayaran angsuran</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.pinjaman.angsuran') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="bulanan" {{ request('jenis', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="mingguan" {{ request('jenis') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Status</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </div>
            <div>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium">
                    Filter
                </button>
            </div>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">No. Urut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah Tagihan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Terbayar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($angsuran as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Pinjaman #{{ $item->pinjaman_id }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->no_urut }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $item->tgl_jatuh_tempo->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->jumlah_terbayar, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'belum' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Belum Lunas'],
                                    'lunas' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Lunas'],
                                    'telat' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Telat'],
                                ];
                                $status = $statusConfig[$item->status_bayar] ?? $statusConfig['belum'];
                            @endphp
                            <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pinjaman.detail-angsuran', $item->id) }}?jenis={{ $jenis }}" 
                                class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-xs font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada angsuran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($angsuran->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $angsuran->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
