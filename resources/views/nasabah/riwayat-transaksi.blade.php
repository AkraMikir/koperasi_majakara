@extends('layouts.nasabah')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button & Header -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Riwayat Seluruh Transaksi</h1>
                        <p class="text-white/90 text-sm">Mutasi dan aktivitas dari seluruh layanan keuangan Anda</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-white hover:bg-white/30 transition-all border border-white/30 flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <form method="GET" action="{{ route('nasabah.riwayat-transaksi') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Fitur</label>
                    <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Semua Fitur</option>
                        <option value="tabungan" {{ request('type') == 'tabungan' ? 'selected' : '' }}>Tabungan</option>
                        <option value="deposito" {{ request('type') == 'deposito' ? 'selected' : '' }}>Deposito</option>
                        <option value="pinjaman" {{ request('type') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                        <option value="gadai" {{ request('type') == 'gadai' ? 'selected' : '' }}>Gadai</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Aliran Dana</label>
                    <select name="flow" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Semua Aliran</option>
                        <option value="masuk" {{ request('flow') == 'masuk' ? 'selected' : '' }}>Uang Masuk (Setoran/Pencairan)</option>
                        <option value="keluar" {{ request('flow') == 'keluar' ? 'selected' : '' }}>Uang Keluar (Penarikan/Bayar Angsuran)</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-lg hover:shadow-xl">
                        Filter
                    </button>
                    @if(request('type') || request('flow'))
                        <a href="{{ route('nasabah.riwayat-transaksi') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Transaction List Table -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            @if(count($transaksi) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-linear-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">ID / Referensi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wider">Jenis Transaksi</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($transaksi as $item)
                                @php
                                    $iconColor = '';
                                    $iconBg = '';
                                    $svgPath = '';
                                    
                                    switch($item->icon_type) {
                                        case 'tabungan':
                                            $iconColor = 'text-[#674c1d]';
                                            $iconBg = 'bg-[#674c1d]/10';
                                            $svgPath = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                            break;
                                        case 'deposito':
                                            $iconColor = 'text-[#d4af37]';
                                            $iconBg = 'bg-[#d4af37]/10';
                                            $svgPath = 'M2 19l10-10 4 4 6-6m0 0v6m0-6h6';
                                            break;
                                        case 'pinjaman':
                                            $iconColor = 'text-[#8b6f2f]';
                                            $iconBg = 'bg-[#8b6f2f]/10';
                                            $svgPath = 'M2 10a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zm0 0V6a2 2 0 012-2h14a2 2 0 012 2v4M6 12h.01M10 12h.01M14 12h.01M18 12h.01';
                                            break;
                                        case 'gadai':
                                            $iconColor = 'text-[#a67c52]';
                                            $iconBg = 'bg-[#a67c52]/10';
                                            $svgPath = 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z';
                                            break;
                                        default:
                                            $iconColor = 'text-gray-500';
                                            $iconBg = 'bg-gray-100';
                                            $svgPath = 'M9 5l7 7-7 7';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('H:i') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                        {{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border border-gray-100 {{ $iconBg }} {{ $iconColor }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svgPath }}"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $item->jenis }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-black font-display {{ $item->is_inflow ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $item->is_inflow ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        @if($item->url !== '#')
                                            <a href="{{ $item->url }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-linear-to-r from-[#674c1d]/10 to-[#8b6f2f]/10 text-[#674c1d] rounded-lg hover:from-[#674c1d] hover:to-[#8b6f2f] hover:text-white transition-all font-semibold">
                                                Detail
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No Link</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($transaksi->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transaksi->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Belum Ada Transaksi</h3>
                        <p class="text-sm text-gray-400 max-w-sm">Mulai bertransaksi untuk melihat riwayat aktivitas mutasi rekening Anda di sini.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
