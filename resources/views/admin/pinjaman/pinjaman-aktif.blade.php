@extends('layouts.admin')

@section('title', 'Pinjaman Aktif')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Pinjaman Aktif</h1>
            <p class="text-gray-600 mt-1">Daftar semua pinjaman yang sedang aktif</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <select name="jenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="bulanan" {{ request('jenis') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="mingguan" {{ request('jenis') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                </select>
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Status</option>
                    <option value="pencairan" {{ request('status') == 'pencairan' ? 'selected' : '' }}>Pencairan</option>
                    <option value="telaksana" {{ request('status') == 'telaksana' ? 'selected' : '' }}>Telaksana</option>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Lama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Bunga</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->jumlah_pinjam, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 {{ $item->jenis === 'bulanan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} rounded-full text-xs font-semibold">
                                {{ ucfirst($item->jenis) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $item->lama_pinjam }} {{ $item->jenis === 'bulanan' ? 'bulan' : 'minggu' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <p class="font-medium">{{ number_format($item->bunga * 100, 2) }}%</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($item->bunga_rp, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 {{ $item->status === 'telaksana' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-semibold">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pinjaman.detail-pinjaman', $item->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada pinjaman aktif</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pinjaman->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pinjaman->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
