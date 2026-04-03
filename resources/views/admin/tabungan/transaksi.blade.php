@extends('layouts.admin')

@section('title', 'Transaksi Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">{{ $title ?? 'Transaksi Tabungan' }}</h1>
            <p class="text-gray-600 mt-1">Riwayat semua transaksi tabungan nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            @canCrudTabungan
            <a href="{{ route('admin.tabungan.create-transaksi') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all text-sm font-medium shadow-md">
                + Buat Transaksi Manual
            </a>
            @endcanCrudTabungan
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
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[#674c1d] transition-all shadow-md font-medium">
                    Filter Waktu/Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Tab Switch (Riwayat / Petty Cash) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex flex-wrap gap-2">
        <a href="{{ route('admin.tabungan.transaksi') }}" 
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('filter') ? 'bg-[#674c1d] text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
            Semua Transaksi
        </a>
        <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'saya']) }}" 
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 {{ request('filter') == 'saya' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Riwayat Saya
            <span class="px-2 py-0.5 rounded-full text-xs {{ request('filter') == 'saya' ? 'bg-white/20 text-white' : 'bg-blue-200 text-blue-800' }}">{{ $myCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'petty']) }}" 
           class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 {{ request('filter') == 'petty' ? 'bg-amber-500 text-white shadow-md' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Petty Cash
            <span class="px-2 py-0.5 rounded-full text-xs {{ request('filter') == 'petty' ? 'bg-white/20 text-white' : 'bg-amber-200 text-amber-800' }}">{{ $pettyCount ?? 0 }}</span>
        </a>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Admin</th>
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
                            @if($item->adminPengelola)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-100">
                                    {{ $item->adminPengelola->nama ?? 'Admin' }}
                                    @if($item->is_petty_cash)
                                        <svg class="w-3.5 h-3.5 text-amber-500 ml-1" title="Petty Cash" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    @endif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium border border-gray-200">
                                    Sistem
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tabungan.detail-transaksi', $item->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[#674c1d] transition-all text-xs font-medium shadow-sm">
                                    Detail
                                </a>
                                @if(!$item->id_pengajuan_setor && !$item->id_pengajuan_tarik)
                                    @canCrudTabungan
                                    <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $item->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all text-xs font-medium border border-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                    @endcanCrudTabungan
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


