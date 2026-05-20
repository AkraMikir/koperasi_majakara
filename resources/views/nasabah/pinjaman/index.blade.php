@extends('layouts.nasabah')

@section('title', 'Dashboard Pinjaman')

@section('content')
<div class="w-full pb-6 space-y-6">
    <!-- Hero Section -->
    <div class="mx-4 mt-4">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        
                        <h1 class="text-3xl font-bold text-white font-display mb-1">Pantau Pinjaman Anda</h1>
                        
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <p class="text-white/80 text-sm mb-1">Total Pinjaman Aktif</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white font-display">
                            Rp {{ number_format($totalPinjamanAktif ?? 0, 0, ',', '.') }}
                        </h2>
                        <p class="text-white/90 text-sm mt-1">Sisa Pembayaran: <span class="font-semibold">Rp {{ number_format($sisaPinjaman ?? 0, 0, ',', '.') }}</span></p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Quick Menu -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Ajukan<br>Pinjaman</p>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.pembayaran') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Bayar<br>Pinjaman</p>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Status<br>Pengajuan</p>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Riwayat<br>Pembayaran</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert / Tindakan Diperlukan -->
    @if(($totalAngsuranTelat ?? 0) > 0 || (isset($angsuranTerdekat) && $angsuranTerdekat->count() > 0))
    <div class="mx-4">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 {{ ($totalAngsuranTelat ?? 0) > 0 ? 'border-red-500' : 'border-orange-500' }}">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 {{ ($totalAngsuranTelat ?? 0) > 0 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }} rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold font-display {{ ($totalAngsuranTelat ?? 0) > 0 ? 'text-red-700' : 'text-orange-700' }}">Tindakan Diperlukan</h2>
            </div>
            
            <div class="space-y-3">
                @if(($totalAngsuranTelat ?? 0) > 0)
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                    <div>
                        <p class="text-sm font-bold text-red-800">Anda memiliki {{ $totalAngsuranTelat }} angsuran telat.</p>
                        <p class="text-xs text-red-600 mt-1">Mohon segera lakukan pembayaran untuk menghindari denda tambahan.</p>
                    </div>
                    <a href="{{ route('nasabah.pinjaman.pembayaran') }}" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors whitespace-nowrap shadow-sm">Bayar Sekarang</a>
                </div>
                @endif
                
                @forelse($angsuranTerdekat ?? [] as $angsuran)
                @php
                    $isTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas';
                    $jenisAngsuran = $angsuran->pinjaman->jenis ?? 'bulanan';
                    $urlDetail = route('nasabah.pinjaman.detail-angsuran', ['id' => $angsuran->id, 'jenis' => $jenisAngsuran]);
                @endphp
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-xl border border-orange-100 hover:bg-orange-100 transition-colors cursor-pointer" onclick="window.location.href='{{ $urlDetail }}'">
                    <div>
                        <p class="text-sm font-bold text-orange-800">Angsuran Rp {{ number_format($angsuran->jumlah_tagihan, 0, ',', '.') }}</p>
                        <p class="text-xs text-orange-600 mt-1">Jatuh tempo: {{ $angsuran->tgl_jatuh_tempo->format('d M Y') }} ({{ $angsuran->tgl_jatuh_tempo->diffForHumans() }})</p>
                    </div>
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <!-- Pinjaman Aktif Cards -->
    <div class="mx-4">
        <div class="flex items-center justify-between mb-4 px-2">
            <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Pinjaman Aktif Anda</h2>
            <a href="{{ route('nasabah.pinjaman.pinjaman-aktif') }}" class="text-sm text-[#8b6f2f] font-medium hover:underline flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($pinjamanAktif ?? [] as $pinjaman)
            @php
                // Parse payment progress string like "3 / 12"
                $progressParts = explode('/', $pinjaman->payment_progress);
                $terbayar = (int) trim($progressParts[0]);
                $totalTenor = isset($progressParts[1]) ? (int) trim($progressParts[1]) : 1;
                $persentase = $totalTenor > 0 ? min(100, round(($terbayar / $totalTenor) * 100)) : 0;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-[#8b6f2f]/30 transition-all cursor-pointer group" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="px-2.5 py-1 {{ $pinjaman->status_display['class'] }} rounded-full text-[10px] font-bold uppercase tracking-wider">
                            {{ $pinjaman->status_display['label'] }}
                        </span>
                        <p class="text-xs text-gray-500 mt-2 font-mono">{{ $pinjaman->id }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-[#8b6f2f]/10 flex items-center justify-center group-hover:bg-[#8b6f2f] transition-colors">
                        <svg class="w-4 h-4 text-[#8b6f2f] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Plafon Pinjaman</p>
                    <h3 class="text-xl font-bold text-gray-900">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</h3>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Progress Pembayaran</span>
                        <span class="font-bold text-[#8b6f2f]">{{ $pinjaman->payment_progress }} Angsuran</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-[#a0824d] to-[#d4af37] h-2 rounded-full transition-all duration-500" style="width: {{ $persentase }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-[#8b6f2f]/10 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Pinjaman Aktif</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-md">Anda belum memiliki pinjaman yang sedang berjalan. Ajukan pinjaman sekarang untuk memenuhi kebutuhan finansial Anda.</p>
                    <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="px-6 py-2.5 bg-[#8b6f2f] text-white font-semibold rounded-xl hover:bg-[#674c1d] transition-colors shadow-md shadow-[#8b6f2f]/30">
                        Ajukan Pinjaman
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pinjaman Lunas -->
    @if(isset($pinjamanLunas) && count($pinjamanLunas) > 0)
    <div class="mx-4 mb-6">
        <div class="flex items-center justify-between mb-4 px-2 mt-4">
            <h2 class="text-lg font-bold text-gray-700 font-display">Pinjaman Lunas</h2>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pinjaman</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Terbayar</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjamanLunas as $pinjaman)
                        <tr class="border-b border-gray-50 hover:bg-[#8b6f2f]/5 transition-colors cursor-pointer" onclick="window.location.href='{{ route('nasabah.pinjaman.detail-pinjaman', $pinjaman->id) }}'">
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $pinjaman->id }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-green-600">Rp {{ number_format($pinjaman->total_terbayar ?? 0, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Lunas
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
