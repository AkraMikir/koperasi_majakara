@extends('layouts.nasabah')

@section('title', 'Detail Gadai Saya')

@section('content')
@php
    $isTenggang = $gadai->status == 'grace_period';
    $isLunas = $gadai->status == 'lunas';
    $isHangus = $gadai->status == 'expired_final';
    $totalTebus = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0);
    $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
    $today = now()->startOfDay();
    $jatuhTempo = \Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay();
    $tenggang = \Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay();
    $mulai = \Carbon\Carbon::parse($gadai->tgl_mulai)->startOfDay();
    $sisaHari = $isTenggang ? $today->diffInDays($tenggang, false) : $today->diffInDays($jatuhTempo, false);
    $totalDays = max(1, $mulai->diffInDays($jatuhTempo));
    $elapsedDays = min($totalDays, $mulai->diffInDays($today));
    $progressPct = round(($elapsedDays / $totalDays) * 100);
@endphp

<div class="w-full pb-24">

    {{-- ===== HERO STATUS BANNER ===== --}}
    <div class="@if($isTenggang) bg-gradient-to-br from-red-700 via-red-600 to-red-500 shadow-red-500/30 @elseif($isLunas) bg-gradient-to-br from-emerald-700 to-emerald-500 shadow-emerald-500/30 @elseif($isHangus) bg-gradient-to-br from-gray-700 to-gray-600 shadow-gray-500/30 @else bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] shadow-[#d4af37]/30 @endif relative overflow-hidden rounded-b-3xl shadow-2xl border-b border-white/20">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-80 h-80 bg-white rounded-full -mr-40 -mt-40 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full -ml-32 -mb-32 blur-2xl"></div>
        </div>
        <div class="relative z-10 px-6 pt-6 pb-6">
            {{-- Back & Slot --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('nasabah.gadai_baru.index') }}" class="flex items-center gap-2 text-white/80 hover:text-white text-xs font-bold transition-all group">
                    <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20 group-hover:bg-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    Kembali
                </a>
                <div class="flex gap-2">
                    {{-- 
                    <a href="{{ route('nasabah.struk-gadai', $gadai->id) }}" 
                       class="px-3 py-1 bg-white/20 text-white text-[10px] font-black rounded-full border border-white/30 hover:bg-white/30 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Struk
                    </a>
                    --}}
                    <span class="px-3 py-1 bg-white/20 text-white text-[10px] font-black rounded-full font-mono border border-white/30">{{ $gadai->slot_kode }}</span>
                </div>
            </div>

            {{-- Status label --}}
            <div class="mb-2">
                <p class="text-white/80 text-[10px] font-black uppercase tracking-widest mb-1 drop-shadow-md">Status Transaksi</p>
                <h1 class="text-2xl font-black text-white mt-0.5 tracking-tight font-display drop-shadow-lg">
                    @if($isTenggang) ⚠ MASA TENGGANG
                    @elseif($isLunas) ✅ LUNAS / SELESAI
                    @elseif($isHangus) ❌ HANGUS
                    @else 🔒 AKTIF & AMAN
                    @endif
                </h1>
                <p class="text-white/80 text-sm mt-1.5 font-medium drop-shadow-md">{{ $gadai->item->head_1 }} &bull; {{ $gadai->kategori->nama_kategori }}</p>
            </div>

            {{-- Countdown --}}
            @if($isLunas && $gadai->tgl_ambil_limit)
            <div class="mt-4 bg-amber-500/25 rounded-2xl px-4 py-3 backdrop-blur-sm border border-amber-500/30">
                <div class="flex items-center justify-between">
                    <span class="text-white text-[10px] font-black uppercase tracking-widest">⚠️ Batas Waktu Pengambilan</span>
                    <span class="text-white font-black text-sm" id="detail-countdown">-- H -- M -- S</span>
                </div>
                <p class="text-white/85 text-[10px] font-bold mt-1.5 leading-relaxed">Barang wajib diambil sebelum tanggal {{ $gadai->tgl_ambil_limit->format('d M Y H:i') }}. Jika melebihi batas waktu, barang akan dinyatakan hangus secara hukum.</p>
                <script>
                    (function() {
                        const limitTime = new Date("{{ $gadai->tgl_ambil_limit->toIso8601String() }}").getTime();
                        const timerId = setInterval(function() {
                            const now = new Date().getTime();
                            const distance = limitTime - now;
                            if (distance < 0) {
                                clearInterval(timerId);
                                document.getElementById("detail-countdown").innerHTML = "HANGUS";
                                return;
                            }
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            document.getElementById("detail-countdown").innerHTML = 
                                (days > 0 ? days + " Hari " : "") + hours + " Jam " + minutes + " Menit " + seconds + " Detik";
                        }, 1000);
                    })();
                </script>
            </div>
            @elseif(!$isLunas && !$isHangus)
            <div class="mt-4 bg-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm border border-white/20">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-white/80 text-[10px] font-black uppercase tracking-widest">
                        @if($isTenggang) Sisa Masa Tenggang @else Sisa Waktu Gadai @endif
                    </span>
                    @if($sisaHari >= 0)
                        <span class="text-white font-black text-sm bg-white/20 px-3 py-1 rounded-full">{{ $sisaHari }} hari lagi</span>
                    @else
                        <span class="text-white font-black text-sm bg-red-500/50 px-3 py-1 rounded-full">Lewat {{ abs($sisaHari) }} hari</span>
                    @endif
                </div>
                <div class="w-full bg-black/20 rounded-full h-2.5 overflow-hidden border border-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-white/70 to-white shadow-[0_0_10px_rgba(255,255,255,0.5)] transition-all duration-1000" style="width: {{ min(100, $progressPct) }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-white/80 font-bold mt-2">
                    <span>Mulai: {{ $mulai->format('d M Y') }}</span>
                    <span>Tempo: {{ $jatuhTempo->format('d M Y') }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- CTA inside hero (active & tenggang) --}}
        @if(in_array($gadai->status, ['active', 'grace_period']))
        <div class="relative z-10 px-6 pb-8 flex gap-4">
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}"
               class="flex-1 flex items-center justify-center gap-2 py-4 bg-white text-emerald-700 font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-black/10 active:scale-95 transition-all hover:bg-emerald-50 hover:shadow-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tebus — Rp {{ number_format($totalTebus, 0, ',', '.') }}
            </a>
            @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}"
               class="flex-1 flex items-center justify-center gap-2 py-4 bg-white/20 hover:bg-white/30 text-white font-black rounded-2xl text-xs uppercase tracking-widest border border-white/40 active:scale-95 transition-all backdrop-blur-md shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Perpanjang
            </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Session alerts --}}
    <div class="mx-4 mt-6">
        @foreach(['success'=>'emerald','warning'=>'amber','error'=>'red'] as $t => $c)
        @if(session($t))
        <div class="bg-{{ $c }}-50 border border-{{ $c }}-200 rounded-2xl p-4 text-{{ $c }}-700 text-sm font-bold shadow-sm flex items-center gap-3 mb-4">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session($t) }}
        </div>
        @endif
        @endforeach
    </div>

    {{-- ===== BODY CARDS ===== --}}
    <div class="mx-4 mt-2 space-y-6">

        {{-- Rincian Biaya --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#d4af37]/5 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 transition-transform group-hover:scale-150"></div>
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3 relative z-10">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#674c1d] to-[#d4af37] text-white flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest font-display">Rincian Tagihan</h3>
            </div>
            <div class="p-6 space-y-0 relative z-10">
                @php
                    $biayaRows = [
                        ['label' => 'Pinjaman Pokok', 'sub' => null, 'value' => $gadai->nominal_deal, 'color' => 'text-gray-900'],
                        ['label' => 'Biaya Jasa Admin', 'sub' => 'Tarif: '.number_format($gadai->kategori->rate_jasa,2).'%', 'value' => $gadai->biaya_jasa, 'color' => 'text-gray-900'],
                        ['label' => 'Denda Keterlambatan', 'sub' => 'Tarif: '.number_format($gadai->kategori->rate_denda,2).'%', 'value' => $gadai->denda_aktif, 'color' => $gadai->denda_aktif > 0 ? 'text-red-600' : 'text-gray-400'],
                        ['label' => 'Biaya Inap', 'sub' => $gadai->item->nominal_inap > 0 ? 'Flat: Rp '.number_format($gadai->item->nominal_inap,0,',','.') : 'Tarif: '.number_format($gadai->kategori->rate_inap_persen,2).'%', 'value' => $gadai->biaya_inap, 'color' => $gadai->biaya_inap > 0 ? 'text-amber-600' : 'text-gray-400'],
                    ];
                    if (($gadai->extra_pinjaman_nominal ?? 0) > 0) {
                        $biayaRows[] = ['label' => 'Biaya Ekstra', 'sub' => 'Alasan: ' . ($gadai->extra_pinjaman_reason ?? '-'), 'value' => $gadai->extra_pinjaman_nominal, 'color' => 'text-red-700 bg-red-50/50 px-2 py-1 rounded-lg'];
                    }
                @endphp
                @foreach($biayaRows as $row)
                <div class="flex justify-between items-center py-3.5 {{ !$loop->last ? 'border-b border-dashed border-gray-200' : '' }}">
                    <div>
                        <p class="text-xs font-bold text-gray-700">{{ $row['label'] }}</p>
                        @if($row['sub'])<p class="text-[10px] text-gray-400 font-semibold mt-1">{{ $row['sub'] }}</p>@endif
                    </div>
                    <span class="font-black {{ $row['color'] }} text-sm tracking-tight">Rp {{ number_format($row['value'], 0, ',', '.') }}</span>
                </div>
                @endforeach
                {{-- Total --}}
                <div class="mt-6 grid gap-4 {{ $gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default ? 'grid-cols-2' : 'grid-cols-1' }}">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200/60 shadow-sm relative overflow-hidden group/tebus">
                        <div class="absolute inset-0 bg-emerald-100 opacity-0 group-hover/tebus:opacity-50 transition-opacity"></div>
                        <div class="relative z-10">
                            <p class="text-[9px] text-emerald-700 font-black uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Total Tebus / Lunas
                            </p>
                            <p class="text-xl font-black text-emerald-800 tracking-tighter">Rp {{ number_format($totalTebus, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200/60 shadow-sm relative overflow-hidden group/extend">
                        <div class="absolute inset-0 bg-amber-100 opacity-0 group-hover/extend:opacity-50 transition-opacity"></div>
                        <div class="relative z-10">
                            <p class="text-[9px] text-amber-700 font-black uppercase tracking-widest mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Total Perpanjang Saja
                            </p>
                            <p class="text-xl font-black text-amber-800 tracking-tighter">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Jaminan & Waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Data Jaminan --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 p-6 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.9 4.44c-.16.12-.36.18-.57.18-.21 0-.41-.06-.57-.18l-7.9-4.44A.991.991 0 013 16.5v-9c0-.38.21-.71.53-.88l7.9-4.44c.16-.12.36-.18.57-.18.21 0 .41.06.57.18l7.9 4.44c.32.17.53.5.53.88v9z"></path></svg>
                </div>
                <h3 class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Data Jaminan
                </h3>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Kategori</span>
                        <span class="px-3 py-1 rounded-xl bg-gradient-to-r from-amber-50 to-amber-100/50 border border-amber-200/50 text-[#674c1d] text-xs font-black shadow-sm">{{ $gadai->kategori->nama_kategori }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Nama Item</span>
                        <span class="text-sm font-black text-gray-900 text-right max-w-[60%] leading-tight">{{ $gadai->item->head_1 }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Lokasi Simpan</span>
                        <span class="text-xs font-bold text-gray-700 text-right flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $gadai->lokasi->nama_lokasi }}
                        </span>
                    </div>
                </div>
                {{-- Foto --}}
                @if($gadai->files->isNotEmpty())
                <div class="mt-5 grid grid-cols-3 gap-3 relative z-10">
                    @foreach($gadai->files->take(3) as $file)
                    <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank"
                       class="aspect-square rounded-2xl overflow-hidden border border-gray-200 block hover:shadow-lg hover:border-[#d4af37]/50 transition-all group">
                        <img src="{{ asset('storage/' . $file->path_file) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Siklus Waktu --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 p-6 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Siklus & Waktu
                </h3>
                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Tanggal Mulai</span>
                        <span class="text-sm font-black text-gray-800">{{ $mulai->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Jatuh Tempo</span>
                        <span class="text-sm font-black text-[#674c1d]">{{ $jatuhTempo->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-500 font-bold">Batas Tenggang</span>
                        <span class="text-sm font-black text-red-600">{{ $tenggang->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-gray-100 relative z-10">
                    <div class="flex justify-between items-center mb-2.5">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Perpanjangan
                        </span>
                        <span class="text-[10px] font-black px-2 py-1 bg-gray-100 rounded-lg text-gray-700">{{ $gadai->jumlah_perpanjangan }} / {{ $gadai->kategori->max_extend_default }} Kali</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
                        @php $pct = ($gadai->jumlah_perpanjangan / max(1, $gadai->kategori->max_extend_default)) * 100; @endphp
                        <div class="bg-gradient-to-r from-[#d4af37] to-[#674c1d] h-3 rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
                    </div>
                    @if($gadai->jumlah_perpanjangan >= $gadai->kategori->max_extend_default)
                    <p class="text-[10px] text-red-500 font-bold mt-2 bg-red-50 p-2 rounded-lg flex items-center gap-2">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Batas perpanjangan telah habis.
                    </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== RIWAYAT TRANSAKSI ===== --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden mt-8">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center border border-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest font-display">Riwayat Pembayaran & Log</h3>
            </div>
            
            <div class="p-2 space-y-2 bg-gray-50/30">
                @foreach($gadai->paymentLogs as $log)
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-[#d4af37]/30 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-black text-gray-900 text-sm">
                                @if(in_array($log->jenis_pembayaran, ['tebus','lunas'])) Pelunasan Gadai @else Pembayaran Perpanjangan @endif
                            </p>
                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ $log->created_at->format('d M Y, H:i') }} &bull; {{ strtoupper($log->metode) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-black text-emerald-700 bg-emerald-50 px-3 py-1 rounded-xl">Rp {{ number_format($log->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                @foreach($gadai->history as $hist)
                @if(in_array($hist->aksi, ['extend','lunas'])) @continue @endif
                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-gray-300 transition-colors opacity-90">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="font-black text-gray-800 text-sm capitalize">
                                @if($hist->aksi === 'create') Gadai Didaftarkan @else {{ $hist->aksi }} @endif
                            </p>
                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ $hist->created_at->format('d M Y, H:i') }} &bull; {{ $hist->catatan }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($gadai->paymentLogs->isEmpty() && $gadai->history->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Belum ada riwayat transaksi</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
