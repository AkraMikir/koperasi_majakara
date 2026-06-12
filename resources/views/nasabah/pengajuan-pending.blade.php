@extends('layouts.nasabah')

@section('title', 'Pengajuan Pending')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Semua Pengajuan Pending</h1>
                        <p class="text-white/90 text-sm">Lihat semua pengajuan yang sedang menunggu persetujuan</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-white hover:bg-white/30 transition-all border border-white/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <form method="GET" action="{{ route('nasabah.pengajuan-pending') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Jenis</label>
                    <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Semua Jenis</option>
                        <option value="tabungan_setor" {{ request('type') == 'tabungan_setor' ? 'selected' : '' }}>Setoran Tabungan</option>
                        <option value="tabungan_tarik" {{ request('type') == 'tabungan_tarik' ? 'selected' : '' }}>Penarikan Tabungan</option>
                        <option value="pinjaman" {{ request('type') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                        <option value="deposito" {{ request('type') == 'deposito' ? 'selected' : '' }}>Deposito</option>
                        <option value="gadai" {{ request('type') == 'gadai' ? 'selected' : '' }}>Gadai</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl">
                        Filter
                    </button>
                </div>
                @if(request('type') || request('status'))
                <div class="flex items-end">
                    <a href="{{ route('nasabah.pengajuan-pending') }}" class="w-full md:w-auto px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                        Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Pengajuan List -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            @if(count($pengajuan) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-linear-to-r from-[#674c1d]/10 to-[#8b6f2f]/10">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengajuan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item['tanggal']->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $item['tanggal']->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($item['type'] == 'tabungan_setor')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            Setoran Tabungan
                                        </span>
                                    @elseif($item['type'] == 'tabungan_tarik')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            Penarikan Tabungan
                                        </span>
                                    @elseif($item['type'] == 'pinjaman')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Pinjaman
                                        </span>
                                    @elseif($item['type'] == 'pinjaman_bayar')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                            Pembayaran Pinjaman
                                        </span>
                                    @elseif($item['type'] == 'deposito')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Deposito
                                        </span>
                                    @elseif($item['type'] == 'gadai')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Gadai
                                        </span>
                                    @elseif($item['type'] == 'gadai_repay')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                            Gadai - Tebus/Perpanjang
                                        </span>
                                    @endif
                                </div>
                                @if(isset($item['durasi']))
                                <div class="text-xs text-gray-500 mt-1">Durasi: {{ $item['durasi'] }}</div>
                                @endif
                                @if(isset($item['tenor']))
                                <div class="text-xs text-gray-500 mt-1">Tenor: {{ $item['tenor'] }}</div>
                                @endif
                                @if(isset($item['item']))
                                <div class="text-xs text-gray-500 mt-1">Item: {{ $item['item'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item['metode'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($item['detail_url'] != '#')
                                <a href="{{ $item['detail_url'] }}" class="text-[#674c1d] hover:text-[#8b6f2f] font-semibold inline-flex items-center gap-1">
                                    Detail
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                @else
                                <span class="text-gray-400">Coming Soon</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pengajuan Pending</p>
                <p class="text-gray-500 mb-6">Semua pengajuan Anda sudah diproses atau belum ada pengajuan</p>
                <a href="{{ route('nasabah.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl">
                    Kembali ke Dashboard
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
