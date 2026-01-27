@extends('layouts.nasabah')

@section('title', 'Dashboard Tabungan')

@section('content')
<div class="w-full pb-6">
    <!-- Hero Section - Saldo Utama -->
        <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-white/90 text-sm font-medium mb-2">Saldo Tabungan Anda</p>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-2 font-display">
                            Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}
                        </h1>
                        <p class="text-white/80 text-sm">Status: <span class="font-semibold">{{ $tabunganInfo->status ?? 'Aktif' }}</span></p>
                    </div>
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                        </div>
                    </div>
                    
                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            <div>
                                <p class="text-white text-sm font-medium">Nabung</p>
                                <p class="text-white/80 text-xs">Tambah Tabungan</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.tabungan.penarikan') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl p-4 transition-all border border-white/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </div>
                            <div>
                                <p class="text-white text-sm font-medium">Tarik</p>
                                <p class="text-white/80 text-xs">Penarikan Tabungan</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Status Pengajuan Links -->
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Setoran</p>
                    </a>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-lg p-3 transition-all border border-white/20 text-center">
                        <p class="text-white text-xs font-medium">Status Penarikan</p>
                    </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Riwayat Transaksi -->
        <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Riwayat Transaksi</h2>
                </div>
                </div>
                
                <!-- Filter Section -->
            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                    <p class="text-xs font-semibold text-gray-700">Filter Transaksi</p>
                    </div>
                    <x-nasabah.tabungan.filter-tabungan 
                    placeholder-tanggal="Pilih tanggal"
                    placeholder-jumlah="Jumlah"
                    placeholder-id-transaksi="ID Transaksi" />
                </div>
                
                <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#674c1d]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Via</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">ID Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTabungan ?? [] as $transaksi)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.tabungan.detail-transaksi', $transaksi->id) }}'">
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $transaksi->tgl_transaksi->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 {{ $transaksi->jenis === 'setoran' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($transaksi->jenis) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold {{ $transaksi->jenis === 'setoran' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaksi->jenis === 'setoran' ? '+' : '-' }}Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($transaksi->via) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 font-mono">{{ $transaksi->id_transaksi ?? 'TRX-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada transaksi</p>
                    </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    <!-- Riwayat Janji Temu -->
        <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Riwayat Janji Temu</h2>
                </div>
                </div>
                
                <!-- Filter Section -->
            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                    <p class="text-xs font-semibold text-gray-700">Filter Janji Temu</p>
                    </div>
                    <x-nasabah.tabungan.filter-tabungan 
                    placeholder-tanggal="Pilih tanggal"
                    placeholder-jumlah="Nominal"
                    placeholder-id-transaksi="ID Janji Temu" />
                </div>
                
                <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#674c1d]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatJanjiTemu ?? [] as $janji)
                        @php
                            $hasTanggalJanjiTemu = property_exists($janji, 'tanggal_janji_temu') && $janji->tanggal_janji_temu !== null;
                            $tanggalJanji = $janji->tanggal ?? ($hasTanggalJanjiTemu ? $janji->tanggal_janji_temu : ($janji->created_at ?? now()));
                            $waktuJanji = $janji->waktu ?? ($hasTanggalJanjiTemu ? \Carbon\Carbon::parse($janji->tanggal_janji_temu)->format('H:i') : \Carbon\Carbon::parse($tanggalJanji)->format('H:i'));
                            $namaLokasi = 'N/A';
                            if (is_string($janji->lokasi ?? null)) {
                                $namaLokasi = $janji->lokasi;
                            } elseif (isset($janji->lokasi) && is_object($janji->lokasi) && property_exists($janji->lokasi, 'nama_lokasi')) {
                                $namaLokasi = $janji->lokasi->nama_lokasi ?? 'N/A';
                            }
                            $isPast = $hasTanggalJanjiTemu ? \Carbon\Carbon::parse($janji->tanggal_janji_temu)->isPast() : false;
                            $status = $janji->status ?? ($isPast ? 'Selesai' : 'Menunggu');
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.tabungan.detail-janji-temu', $janji->id) }}'">
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($tanggalJanji)->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $waktuJanji }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $namaLokasi }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-[#674c1d]">Rp {{ number_format($janji->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 {{ ($status === 'Selesai' || $isPast) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-semibold">
                                        {{ $status }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada janji temu</p>
                    </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
