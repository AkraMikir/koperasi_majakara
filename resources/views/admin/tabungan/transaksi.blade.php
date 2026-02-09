@extends('layouts.admin')

@section('title', 'Transaksi Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Transaksi Tabungan</h1>
            <p class="text-gray-600 mt-1">Riwayat semua transaksi tabungan nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.create-transaksi') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all text-sm font-medium shadow-md">
                + Buat Transaksi Manual
            </a>
            <a href="{{ route('admin.tabungan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.tabungan.transaksi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="setoran" {{ request('jenis') == 'setoran' ? 'selected' : '' }}>Setoran</option>
                    <option value="penarikan" {{ request('jenis') == 'penarikan' ? 'selected' : '' }}>Penarikan</option>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Via</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Keterangan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $item)
                    <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium font-mono">{{ $item->id_transaksi ?? str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm">{{ $item->tgl_transaksi->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $item->jenis === 'setoran' ? 'bg-[#674c1d]/10 text-[#674c1d]' : 'bg-[#8b6f2f]/10 text-[#8b6f2f]' }} rounded-lg text-xs font-medium">
                                {{ ucfirst($item->jenis) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold {{ $item->jenis === 'setoran' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->jenis === 'setoran' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst($item->via) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tabungan.detail-transaksi', $item->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-xs font-medium">
                                    Detail
                                </a>
                                @if(!$item->id_pengajuan_setor && !$item->id_pengajuan_tarik)
                                <a href="{{ route('admin.tabungan.edit-transaksi', $item->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $item->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all text-xs font-medium">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


