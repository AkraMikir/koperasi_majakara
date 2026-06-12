@extends('layouts.nasabah')

@section('title', 'Deposito')

@push('styles')
<style>
    .tenor-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .tenor-card:hover {
        transform: translateY(-2px);
    }
    .deposito-hero {
        background: linear-gradient(135deg, #4a3514 0%, #674c1d 40%, #8b6f2f 70%, #d4af37 100%);
        position: relative;
        overflow: hidden;
    }
    .deposito-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(212, 175, 55, 0.15);
        border-radius: 50%;
    }
    .deposito-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255, 0.05);
        border-radius: 50%;
    }
    .rate-badge {
        background: linear-gradient(135deg, #d4af37, #f0d060);
        color: #3a2800;
        font-weight: 800;
    }
    .simulasi-result {
        transition: all 0.3s ease;
    }
    .deposito-aktif-card {
        background: linear-gradient(135deg, #fff9ee, #fffdf7);
        border: 1px solid #d4af37/30;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .shimmer-gold {
        background: linear-gradient(90deg, #d4af37 25%, #f0d060 50%, #d4af37 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
</style>
@endpush

@section('content')
<div class="w-full pb-6">


    {{-- ===== HERO CARD: Pantau Deposito Anda ===== --}}
    <div class="mx-4 mt-4">
        <div class="bg-linear-to-br from-[#4a3514] via-[#674c1d] to-[#8b6f2f] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            {{-- Background Pattern --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>

            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white font-display mb-1">Pantau Deposito Anda</h1>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    @php
                        $totalNominalDeposito = $depositoAktif->sum('nominal_awal');
                        $jumlahDepositoAktif = $depositoAktif->count();
                    @endphp
                    <div>
                        <p class="text-white/80 text-sm mb-1">Total Nilai Deposito Aktif</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-white font-display">
                            Rp {{ number_format($totalNominalDeposito, 0, ',', '.') }}
                        </h2>
                        <p class="text-white/90 text-sm mt-1">
                            @if($jumlahDepositoAktif > 0)
                                <span class="font-semibold">{{ $jumlahDepositoAktif }} deposito</span> sedang berjalan
                            @else
                                Belum ada deposito aktif
                            @endif
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Quick Menu --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('nasabah.deposito.pengajuan') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Buka<br>Deposito</p>
                        </div>
                    </a>
                    <a href="{{ route('nasabah.deposito.riwayat') }}" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Riwayat<br>Deposito</p>
                        </div>
                    </a>
                    <a href="#riwayat-pengajuan" class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Status<br>Pengajuan</p>
                        </div>
                    </a>
                    @php
                        $cairkanUrl = $depositoAktif->count() === 1
                            ? route('nasabah.deposito.detail', $depositoAktif->first()->id)
                            : '#deposito-aktif';
                    @endphp
                    <a href="{{ $cairkanUrl }}" class="group {{ $depositoAktif->isEmpty() ? 'opacity-40 pointer-events-none' : '' }} bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all border border-white/20 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs font-medium leading-tight">Cairkan<br>Deposito</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DEPOSITO AKTIF SECTION ===== --}}
    <div class="mx-4 mt-5" id="deposito-aktif">
        <div class="flex items-center justify-between mb-4 px-2">
            <h2 class="text-lg font-bold text-[#674c1d] font-display">Deposito Aktif Anda</h2>
            <span class="text-sm text-[#674c1d] font-medium">{{ $depositoAktif->count() }} berjalan</span>
        </div>

        @if($depositoAktif->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($depositoAktif as $dep)
            @php
                $persen = 0;
                $now = now();
                if ($dep->tgl_mulai && $dep->tgl_jatuh_tempo) {
                    $total = $dep->tgl_mulai->diffInDays($dep->tgl_jatuh_tempo);
                    $lewat = $dep->tgl_mulai->diffInDays($now);
                    $persen = $total > 0 ? min(100, round(($lewat / $total) * 100)) : 0;
                }
                $diff = $now->diff($dep->tgl_jatuh_tempo);
                $bulanSisa = $diff->y * 12 + $diff->m;
                $hariSisaCount = $diff->d;
                $isPast = $now->gt($dep->tgl_jatuh_tempo);
            @endphp
            <a href="{{ route('nasabah.deposito.detail', $dep->id) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-[#d4af37]/50 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-mono">{{ $dep->nomor_deposito }}</p>
                        <p class="font-bold text-gray-800 text-sm mt-0.5">Deposito {{ $dep->tenor?->tenor_bulan ?? '-' }} Bulan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Aktif</span>
                        <div class="w-7 h-7 rounded-full bg-[#674c1d]/10 flex items-center justify-center group-hover:bg-[#674c1d] transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#674c1d] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Nominal</p>
                        <p class="font-bold text-gray-900 text-lg">Rp {{ number_format($dep->nominal_awal, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 mb-0.5">Suku Bunga</p>
                        <p class="text-[#674c1d] font-black text-2xl leading-none">{{ number_format($dep->bunga * 100, 2) }}%</p>
                        <p class="text-xs text-gray-400">p.a.</p>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                        <span>Progress Tenor</span>
                        <span class="{{ ($isPast || ($bulanSisa == 0 && $hariSisaCount <= 7)) ? 'text-red-500 font-bold' : 'text-[#674c1d] font-semibold' }}">
                            @if($isPast)
                                ⚠️ Jatuh Tempo
                            @else
                                {{ $bulanSisa > 0 ? $bulanSisa . ' bln ' : '' }}{{ $hariSisaCount > 0 || $bulanSisa == 0 ? $hariSisaCount . ' hari ' : '' }}lagi
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full bg-gradient-to-r from-[#a0824d] to-[#d4af37] transition-all duration-500" style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1.5">
                        <span>{{ $dep->tgl_mulai?->format('d M Y') }}</span>
                        <span>{{ $dep->tgl_jatuh_tempo?->format('d M Y') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-2">Belum Ada Deposito Aktif</h3>
            <p class="text-gray-500 text-sm mb-5 max-w-xs mx-auto">Mulai investasikan dana Anda dan nikmati imbal hasil hingga 12% p.a.</p>
            <a href="{{ route('nasabah.deposito.pengajuan') }}" class="px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-semibold rounded-xl hover:opacity-90 transition shadow-md shadow-[#674c1d]/20">
                Buka Deposito Sekarang
            </a>
        </div>
        @endif
    </div>




    {{-- ===== SIMULASI CEPAT ===== --}}
    <div class="mx-4 mt-5 mb-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-gradient-to-br from-[#674c1d] to-[#d4af37] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#674c1d]">Simulasi Imbal Hasil</h2>
                    <p class="text-xs text-gray-500">Hitung estimasi keuntungan deposito Anda</p>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Jumlah Penempatan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                        <input type="text" id="sim_nominal" placeholder="1.000.000"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/10"
                            oninput="formatCurrency(this); hitungSimulasi()">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Minimal Rp 1.000.000</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Pilih Tenor</label>
                    <div class="grid grid-cols-4 gap-2">
                        @php
                            $uniqueTenors = $pakets->unique('tenor_bulan')->values();
                        @endphp
                        @foreach($uniqueTenors as $t)
                        <button type="button" onclick="selectTenor({{ $t->tenor_bulan }}, {{ $t->suku_bunga }}, this)"
                            data-bulan="{{ $t->tenor_bulan }}" data-rate="{{ $t->suku_bunga }}"
                            class="tenor-sim-btn py-2 rounded-xl border-2 text-center transition-all text-xs font-bold border-gray-200 text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d]">
                            {{ $t->tenor_bulan }}bln
                        </button>
                        @endforeach
                        {{-- Fallback jika paket kosong --}}
                        @if($pakets->isEmpty())
                        <button type="button" onclick="selectTenor(1, 6.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">1bln</button>
                        <button type="button" onclick="selectTenor(3, 7.5, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">3bln</button>
                        <button type="button" onclick="selectTenor(6, 9.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">6bln</button>
                        <button type="button" onclick="selectTenor(12, 12.0, this)" class="tenor-sim-btn py-2 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-600">12bln</button>
                        @endif
                    </div>
                </div>

                {{-- Result --}}
                <div id="sim_result" class="hidden simulasi-result bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/30 rounded-xl p-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Estimasi Bunga (sebelum pajak)</p>
                            <p id="sim_bunga" class="text-lg font-black text-[#674c1d]">Rp 0</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Total Pencairan</p>
                            <p id="sim_total" class="text-lg font-black text-green-700">Rp 0</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-[#d4af37]/20">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Bunga Setelah Pajak ({{ $pajakRate * 100 }}%)</span>
                            <span id="sim_bersih" class="font-semibold text-gray-700">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Pajak Bunga ({{ $pajakRate * 100 }}%)</span>
                            <span id="sim_pajak" class="font-semibold text-red-500">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PRODUK DEPOSITO ===== --}}
    <div class="mx-4 mb-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-[#674c1d] font-display">Pilihan Produk Deposito</h2>
            <span class="text-xs text-gray-400">Mulai dari Rp 1 juta</span>
        </div>

        {{-- List Produk Deposito --}}
        <div class="space-y-4">
            @if($pakets->isEmpty())
                @php
                    $tenorData = [
                        ['bulan' => 1, 'rate' => 6.0, 'label' => '1 Bulan'],
                        ['bulan' => 3, 'rate' => 7.5, 'label' => '3 Bulan'],
                        ['bulan' => 6, 'rate' => 9.0, 'label' => '6 Bulan'],
                        ['bulan' => 12, 'rate' => 12.0, 'label' => '12 Bulan'],
                    ];
                @endphp
                @foreach($tenorData as $td)
                <a href="{{ route('nasabah.deposito.pengajuan') }}" class="tenor-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between block hover:border-[#d4af37] hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center text-[#674c1d]">
                            @if($td['bulan'] <= 3)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif($td['bulan'] <= 6)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Deposito {{ $td['label'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Mulai dari Rp 1.000.000</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[#674c1d] font-black text-xl">{{ number_format($td['rate'], 2) }}%</span>
                        <p class="text-xs text-gray-400 font-medium">p.a.</p>
                        <span class="text-[#674c1d] text-xs font-medium">Buka →</span>
                    </div>
                </a>
                @endforeach
            @else
                @php
                    $hasCategory = $pakets->filter(function($p) { return $p->kategori != null; })->isNotEmpty();
                    $highestRateId = $pakets->sortByDesc('suku_bunga')->first()->id ?? null;
                    
                    // Separate featured from regular
                    $featuredPakets = $pakets->filter(function($p) use ($hasCategory, $highestRateId) {
                        return $p->kategori != null || (!$hasCategory && $p->id == $highestRateId);
                    });
                    
                    $regularPakets = $pakets->filter(function($p) use ($hasCategory, $highestRateId) {
                        return !($p->kategori != null || (!$hasCategory && $p->id == $highestRateId));
                    });
                @endphp

                {{-- Carousel Featured Packages --}}
                @if($featuredPakets->isNotEmpty())
                    <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory hide-scrollbar -mx-4 px-4">
                        @foreach($featuredPakets as $p)
                            <div class="snap-center shrink-0 w-[85vw] max-w-[340px]">
                                <div class="bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl p-5 shadow-lg relative overflow-hidden h-full flex flex-col justify-between block">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                                    <div class="relative z-10 flex-1 flex flex-col">
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($p->kategori)
                                                <span class="rate-badge text-xs px-2.5 py-1 rounded-full text-[#674c1d] bg-[#d4af37]">⭐ {{ $p->kategori->nama_kategori }}</span>
                                                <span class="text-white/70 text-xs">Promo Spesial</span>
                                            @else
                                                <span class="rate-badge text-xs px-2.5 py-1 rounded-full text-[#674c1d] bg-[#d4af37]">🔥 Bunga Tertinggi</span>
                                            @endif
                                        </div>
                                        <div class="flex items-end justify-between mb-4">
                                            <div>
                                                <p class="text-white/80 text-sm">{{ $p->nama_paket }}</p>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-[#f0d060] text-5xl font-black">{{ rtrim(rtrim($p->suku_bunga, '0'), '.') }}%</span>
                                                    <span class="text-white/80 text-lg font-medium">p.a.</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-white/60 text-xs">Mulai dari</p>
                                                <p class="text-white font-bold text-sm">Rp {{ number_format($p->minimal_nominal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mb-4 mt-auto">
                                            <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full">{{ $p->tenor_bulan }} Bulan</span>
                                            @if($p->keterangan)
                                            <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full truncate max-w-[150px]">{{ $p->keterangan }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('nasabah.deposito.pengajuan') }}?paket={{ $p->id }}"
                                            class="shimmer-gold w-full block text-center font-bold text-[#3a2800] py-3 rounded-xl text-sm transition-all active:scale-95">
                                            Buka Deposito →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Regular Packages --}}
                @if($regularPakets->isNotEmpty())
                    <div class="space-y-3 mt-2">
                        @foreach($regularPakets as $p)
                            <a href="{{ route('nasabah.deposito.pengajuan') }}?paket={{ $p->id }}" class="tenor-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between block hover:border-[#d4af37] hover:shadow-md relative overflow-hidden">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center text-[#674c1d]">
                                        @if($p->tenor_bulan <= 3)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @elseif($p->tenor_bulan <= 6)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $p->nama_paket }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Mulai dari Rp {{ number_format($p->minimal_nominal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[#674c1d] font-black text-xl">{{ rtrim(rtrim($p->suku_bunga, '0'), '.') }}%</span>
                                    <p class="text-xs text-gray-400 font-medium">p.a.</p>
                                    <span class="text-[#674c1d] text-xs font-medium">Buka →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>



    {{-- ===== RIWAYAT PENGAJUAN ===== --}}
    <div class="mx-4 mb-5" id="riwayat-pengajuan">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-bold text-[#674c1d] font-display">Riwayat Pengajuan</h2>
            <a href="{{ route('nasabah.deposito.riwayat') }}" class="text-sm text-[#d4af37] font-bold hover:text-[#674c1d] transition-colors">Lihat Semua →</a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-3">
            @if($riwayatPengajuan->isNotEmpty())
                @foreach($riwayatPengajuan as $pjn)
                @php
                    $statusMap = ['1' => ['label' => 'Proses', 'class' => 'bg-amber-100 text-amber-700'], '2' => ['label' => 'Disetujui', 'class' => 'bg-green-100 text-green-700'], '3' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700']];
                    $st = $statusMap[$pjn->status] ?? $statusMap['1'];
                @endphp
                <a href="{{ route('nasabah.deposito.status-pengajuan', $pjn->id) }}" class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0 hover:bg-[#674c1d]/5 -mx-4 px-4 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Deposito {{ $pjn->tenor?->tenor_bulan ?? '-' }} Bulan</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($pjn->nominal, 0, ',', '.') }} · {{ $pjn->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ $st['class'] }}">{{ $st['label'] }}</span>
                </a>
                @endforeach
            @else
                <div class="py-6 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/30 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Belum Ada Pengajuan</p>
                    <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">Anda belum pernah mengajukan deposito. Yuk, rencanakan masa depan finansial Anda dari sekarang!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== KEUNGGULAN ===== --}}
    <div class="mx-4 mb-5">
        <div class="bg-gradient-to-br from-[#faf9f0] to-white rounded-2xl border border-[#d4af37]/20 p-5">
            <h2 class="text-base font-bold text-[#674c1d] font-display mb-4">Mengapa Deposito Koperasi Majakara?</h2>
            <div class="space-y-4">
                @foreach([
                    ['icon' => '<svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>', 'title' => 'Imbal Hasil Kompetitif', 'desc' => 'Bunga deposito di atas rata-rata lembaga keuangan lain, mulai 6% hingga 12% p.a.'],
                    ['icon' => '<svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>', 'title' => 'Aman & Terpercaya', 'desc' => 'Dana Anda dikelola secara profesional di bawah naungan Koperasi berpengalaman.'],
                    ['icon' => '<svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', 'title' => 'Proses Cepat & Mudah', 'desc' => 'Pengajuan 100% online, hasil evaluasi dalam 1×24 jam kerja.'],
                    ['icon' => '<svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>', 'title' => 'Pantau Kapan Saja', 'desc' => 'Monitor perkembangan deposito dan bunga harian langsung dari aplikasi.'],
                ] as $feat)
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">{!! $feat['icon'] !!}</div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $feat['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $feat['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
{{-- ===== TABEL SIMULASI SEABANK STYLE ===== --}}
    <div class="mx-4 mb-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-[#674c1d] font-display">Tabel Simulasi Bunga</h2>
                <p class="text-xs text-gray-500 mt-1">Contoh perhitungan dengan penempatan Rp 10.000.000</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-[#674c1d]/5">
                            <th class="px-4 py-3 text-left font-bold text-[#674c1d] border-b border-gray-100">Keterangan</th>
                            <th class="px-4 py-3 text-center font-bold text-[#674c1d] border-b border-gray-100">1 Bulan</th>
                            <th class="px-4 py-3 text-center font-bold text-[#674c1d] border-b border-gray-100">3 Bulan</th>
                            <th class="px-4 py-3 text-center font-bold text-[#674c1d] border-b border-gray-100">6 Bulan</th>
                            <th class="px-4 py-3 text-center font-bold text-[#674c1d] border-b border-gray-100">12 Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = [
                                ['label' => 'Suku Bunga', 'vals' => ['6,00%', '7,50%', '9,00%', '12,00%']],
                                ['label' => 'Pokok', 'vals' => ['Rp 10.000.000', 'Rp 10.000.000', 'Rp 10.000.000', 'Rp 10.000.000']],
                                ['label' => 'Bunga Kotor', 'vals' => ['Rp 47.671', 'Rp 184.932', 'Rp 443.836', 'Rp 1.200.000']],
                                ['label' => 'Pajak (' . ($pajakRate * 100) . '%)', 'vals' => ['Rp 9.534', 'Rp 36.986', 'Rp 88.767', 'Rp 240.000'], 'class' => 'text-red-500'],
                                ['label' => 'Bunga Bersih', 'vals' => ['Rp 38.137', 'Rp 147.946', 'Rp 355.069', 'Rp 960.000'], 'class' => 'text-green-600 font-bold'],
                                ['label' => 'Total Cair', 'vals' => ['Rp 10.038.137', 'Rp 10.147.946', 'Rp 10.355.069', 'Rp 10.960.000'], 'class' => 'font-black text-[#674c1d]'],
                            ];
                        @endphp
                        @foreach($rows as $i => $row)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                            <td class="px-4 py-3 text-gray-600 border-b border-gray-50 whitespace-nowrap">{{ $row['label'] }}</td>
                            @foreach($row['vals'] as $v)
                            <td class="px-4 py-3 text-center border-b border-gray-50 {{ $row['class'] ?? 'text-gray-800' }} whitespace-nowrap">{{ $v }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-amber-50/50 border-t border-[#d4af37]/20">
                <p class="text-xs text-gray-500 italic">*Simulasi di atas adalah estimasi. Bunga aktual mengikuti suku bunga yang berlaku saat pencairan.</p>
            </div>
        </div>
    </div>


</div>

@push('scripts')
<script>
let selectedTenorBulan = 0;
let selectedRate = 0;

function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) value = parseInt(value).toLocaleString('id-ID');
    input.value = value;
}

function selectTenor(bulan, rate, el) {
    selectedTenorBulan = bulan;
    selectedRate = rate;
    // Reset styles
    document.querySelectorAll('.tenor-sim-btn').forEach(b => {
        b.classList.remove('border-[#674c1d]', 'bg-[#674c1d]', 'text-white');
        b.classList.add('border-gray-200', 'text-gray-600');
    });
    // Active style
    el.classList.remove('border-gray-200', 'text-gray-600');
    el.classList.add('border-[#674c1d]', 'bg-[#674c1d]', 'text-white');
    hitungSimulasi();
}

function hitungSimulasi() {
    const nominalVal = document.getElementById('sim_nominal').value.replace(/[^0-9]/g, '');
    const nominal = parseFloat(nominalVal) || 0;
    if (nominal <= 0 || selectedTenorBulan <= 0) {
        document.getElementById('sim_result').classList.add('hidden');
        return;
    }
    const hari = selectedTenorBulan * 30;
    const pembagiHari = {{ $pembagiHari }};
    const pajakRate = {{ $pajakRate }};
    const bungaKotor = nominal * (selectedRate / 100) * (hari / pembagiHari);
    const pajak = bungaKotor * pajakRate;
    const bungaBersih = bungaKotor - pajak;
    const total = nominal + bungaBersih;

    const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
    document.getElementById('sim_bunga').textContent = fmt(bungaKotor);
    document.getElementById('sim_total').textContent = fmt(total);
    document.getElementById('sim_bersih').textContent = fmt(bungaBersih);
    document.getElementById('sim_pajak').textContent = fmt(pajak);
    document.getElementById('sim_result').classList.remove('hidden');
}
</script>
@endpush
@endsection
