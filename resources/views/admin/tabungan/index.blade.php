@extends('layouts.admin')

@section('title', 'Dashboard Tabungan')

@section('content')
<div class="space-y-6 p-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Dashboard Tabungan</h1>
            <p class="text-gray-500 text-sm mt-0.5">Kelola pengajuan dan transaksi tabungan nasabah</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tabungan.saldo-nasabah') }}"
               class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-[#674c1d] hover:text-[#674c1d] transition-colors text-sm font-medium">
                Saldo Nasabah
            </a>
            <a href="{{ route('admin.tabungan.transaksi') }}"
               class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-semibold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Semua Transaksi
            </a>
        </div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Pengajuan Setor Pending --}}
        <a href="{{ route('admin.tabungan.pengajuan-setor') }}"
           class="group bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:border-[#674c1d]/30 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-[#674c1d]/10 rounded-xl flex items-center justify-center group-hover:bg-[#674c1d]/15 transition-colors">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                @if(($stats['total_pengajuan_setor'] ?? 0) > 0)
                <span class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $stats['total_pengajuan_setor'] ?? 0 }}</span>
                </span>
                @endif
            </div>
            <p class="text-xs font-medium text-gray-500 mb-1">Pengajuan Setor</p>
            <p class="text-2xl font-bold text-[#674c1d]">{{ number_format($stats['total_pengajuan_setor'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu persetujuan</p>
        </a>

        {{-- Pengajuan Tarik Pending --}}
        <a href="{{ route('admin.tabungan.pengajuan-tarik') }}"
           class="group bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:border-[#8b6f2f]/30 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-[#8b6f2f]/10 rounded-xl flex items-center justify-center group-hover:bg-[#8b6f2f]/15 transition-colors">
                    <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
                @if(($stats['total_pengajuan_tarik'] ?? 0) > 0)
                <span class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $stats['total_pengajuan_tarik'] ?? 0 }}</span>
                </span>
                @endif
            </div>
            <p class="text-xs font-medium text-gray-500 mb-1">Pengajuan Tarik</p>
            <p class="text-2xl font-bold text-[#8b6f2f]">{{ number_format($stats['total_pengajuan_tarik'] ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu persetujuan</p>
        </a>

        {{-- Setoran Hari Ini --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-[#4a3514]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">+{{ $stats['count_setoran_hari_ini'] ?? 0 }}</span>
            </div>
            <p class="text-xs font-medium text-gray-500 mb-1">Setoran Hari Ini</p>
            <p class="text-2xl font-bold text-[#4a3514]">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($stats['total_setoran_hari_ini'] ?? 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['count_setoran_hari_ini'] ?? 0 }} transaksi</p>
        </div>

        {{-- Penarikan Hari Ini --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-[#d4af37]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">-{{ $stats['count_penarikan_hari_ini'] ?? 0 }}</span>
            </div>
            <p class="text-xs font-medium text-gray-500 mb-1">Penarikan Hari Ini</p>
            <p class="text-2xl font-bold text-[#d4af37]">Rp {{ \App\Helpers\CurrencyHelper::formatBerbilang($stats['total_penarikan_hari_ini'] ?? 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['count_penarikan_hari_ini'] ?? 0 }} transaksi</p>
        </div>
    </div>

    {{-- ===== PENDING CARDS: SETOR & TARIK ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Pengajuan Setor Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800 font-display">Pengajuan Setor Terbaru</h2>
                </div>
                <a href="{{ route('admin.tabungan.pengajuan-setor') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">
                    Lihat Semua →
                </a>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($pengajuan_setor_terbaru ?? [] as $pengajuan)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-[#674c1d]/5 transition-colors">
                    <div class="w-9 h-9 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                        <a href="{{ route('admin.tabungan.detail-pengajuan-setor', $pengajuan->id) }}"
                           class="p-1.5 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-10 flex flex-col items-center text-center px-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/20 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-[#d4af37]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-500">Tidak ada pengajuan setor</p>
                    <p class="text-xs text-gray-400 mt-1">Semua pengajuan telah diproses</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pengajuan Tarik Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-800 font-display">Pengajuan Tarik Terbaru</h2>
                </div>
                <a href="{{ route('admin.tabungan.pengajuan-tarik') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">
                    Lihat Semua →
                </a>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($pengajuan_tarik_terbaru ?? [] as $pengajuan)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-[#674c1d]/5 transition-colors">
                    <div class="w-9 h-9 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }} · {{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                        <a href="{{ route('admin.tabungan.detail-pengajuan-tarik', $pengajuan->id) }}"
                           class="p-1.5 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-10 flex flex-col items-center text-center px-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/20 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-[#d4af37]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-500">Tidak ada pengajuan tarik</p>
                    <p class="text-xs text-gray-400 mt-1">Semua pengajuan telah diproses</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== TRANSAKSI TERBARU ===== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-gray-800 font-display">Transaksi Terbaru</h2>
            </div>
            <a href="{{ route('admin.tabungan.transaksi') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#674c1d]/5 border-b border-[#674c1d]/10">
                        <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Nasabah</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Jenis</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Nominal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Via</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transaksi_terbaru ?? [] as $transaksi)
                    <tr class="hover:bg-[#674c1d]/5 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $transaksi->tgl_transaksi->format('d M Y') }}<br><span class="text-xs text-gray-400">{{ $transaksi->tgl_transaksi->format('H:i') }}</span></td>
                        <td class="px-5 py-3 text-sm font-semibold text-gray-800">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                {{ $transaksi->jenis === 'setoran' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $transaksi->jenis === 'setoran' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ ucfirst($transaksi->jenis) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm font-bold {{ $transaksi->jenis === 'setoran' ? 'text-green-700' : 'text-red-600' }}">
                            {{ $transaksi->jenis === 'setoran' ? '+' : '-' }}Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ ucfirst($transaksi->via) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/20 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7 text-[#d4af37]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
