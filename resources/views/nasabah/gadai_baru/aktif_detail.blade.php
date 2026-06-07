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
    <div class="@if($isTenggang) bg-gradient-to-br from-red-700 via-red-600 to-red-500 @elseif($isLunas) bg-gradient-to-br from-emerald-700 to-emerald-500 @elseif($isHangus) bg-gradient-to-br from-gray-700 to-gray-600 @else bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] @endif relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-80 h-80 bg-white rounded-full -mr-40 -mt-40"></div>
        </div>
        <div class="relative z-10 px-5 pt-6 pb-5">
            {{-- Back & Slot --}}
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('nasabah.gadai_baru.index') }}" class="flex items-center gap-1.5 text-white/80 hover:text-white text-xs font-bold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
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
            <div class="mb-1">
                <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Status Transaksi</p>
                <h1 class="text-2xl font-black text-white mt-0.5">
                    @if($isTenggang) ⚠ MASA TENGGANG
                    @elseif($isLunas) ✅ LUNAS / SELESAI
                    @elseif($isHangus) ❌ HANGUS
                    @else 🔒 AKTIF & AMAN
                    @endif
                </h1>
                <p class="text-white/70 text-sm mt-0.5">{{ $gadai->item->head_1 }} • {{ $gadai->kategori->nama_kategori }}</p>
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
                        <span class="text-white font-black text-sm">{{ $sisaHari }} hari lagi</span>
                    @else
                        <span class="text-white font-black text-sm">Lewat {{ abs($sisaHari) }} hari</span>
                    @endif
                </div>
                <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bg-white/80 transition-all" style="width: {{ min(100, $progressPct) }}%"></div>
                </div>
                <div class="flex justify-between text-[9px] text-white/60 font-bold mt-1.5">
                    <span>{{ $mulai->format('d M Y') }}</span>
                    <span>{{ $jatuhTempo->format('d M Y') }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- CTA inside hero (active & tenggang) --}}
        @if(in_array($gadai->status, ['active', 'grace_period']))
        <div class="relative z-10 px-5 pb-6 flex gap-3">
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}"
               class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white text-emerald-700 font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all hover:bg-emerald-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tebus — Rp {{ number_format($totalTebus, 0, ',', '.') }}
            </a>
            @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}"
               class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-white/20 hover:bg-white/30 text-white font-black rounded-2xl text-xs uppercase tracking-widest border border-white/30 active:scale-95 transition-all backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Perpanjang
            </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Session alerts --}}
    @foreach(['success'=>'green','warning'=>'amber','error'=>'red'] as $t => $c)
    @if(session($t))
    <div class="mx-4 mt-4"><div class="bg-{{ $c }}-50 border border-{{ $c }}-200 rounded-2xl p-4 text-{{ $c }}-700 text-sm font-bold">{{ session($t) }}</div></div>
    @endif
    @endforeach

    {{-- ===== BODY CARDS ===== --}}
    <div class="mx-4 mt-5 space-y-4">

        {{-- Rincian Biaya --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Rincian Tagihan</h3>
            </div>
            <div class="p-6 space-y-0">
                @php
                    $biayaRows = [
                        ['label' => 'Pinjaman Pokok', 'sub' => null, 'value' => $gadai->nominal_deal, 'color' => 'text-gray-900'],
                        ['label' => 'Biaya Jasa Admin', 'sub' => 'Tarif: '.number_format($gadai->kategori->rate_jasa,2).'%', 'value' => $gadai->biaya_jasa, 'color' => 'text-gray-900'],
                        ['label' => 'Denda Keterlambatan', 'sub' => 'Tarif: '.number_format($gadai->kategori->rate_denda,2).'%', 'value' => $gadai->denda_aktif, 'color' => $gadai->denda_aktif > 0 ? 'text-red-600' : 'text-gray-400'],
                        ['label' => 'Biaya Inap', 'sub' => $gadai->item->nominal_inap > 0 ? 'Flat: Rp '.number_format($gadai->item->nominal_inap,0,',','.') : 'Tarif: '.number_format($gadai->kategori->rate_inap_persen,2).'%', 'value' => $gadai->biaya_inap, 'color' => $gadai->biaya_inap > 0 ? 'text-amber-600' : 'text-gray-400'],
                    ];
                    if (($gadai->extra_pinjaman_nominal ?? 0) > 0) {
                        $biayaRows[] = ['label' => 'Biaya Ekstra (Administrasi/Lainnya)', 'sub' => 'Alasan: ' . ($gadai->extra_pinjaman_reason ?? '-'), 'value' => $gadai->extra_pinjaman_nominal, 'color' => 'text-red-700 bg-red-50/50 px-2 rounded-lg'];
                    }
                @endphp
                @foreach($biayaRows as $row)
                <div class="flex justify-between items-center py-3 {{ !$loop->last ? 'border-b border-dashed border-gray-100' : '' }}">
                    <div>
                        <p class="text-xs font-bold text-gray-600">{{ $row['label'] }}</p>
                        @if($row['sub'])<p class="text-[9px] text-gray-400 font-bold mt-0.5">{{ $row['sub'] }}</p>@endif
                    </div>
                    <span class="font-black {{ $row['color'] }} text-sm">Rp {{ number_format($row['value'], 0, ',', '.') }}</span>
                </div>
                @endforeach
                {{-- Total --}}
                <div class="mt-4 grid gap-3 {{ $gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default ? 'grid-cols-2' : 'grid-cols-1' }}">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                        <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest mb-1">Total Tebus / Lunas</p>
                        <p class="text-xl font-black text-emerald-700">Rp {{ number_format($totalTebus, 0, ',', '.') }}</p>
                    </div>
                    @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-[9px] text-amber-600 font-black uppercase tracking-widest mb-1">Total Perpanjang Saja</p>
                        <p class="text-xl font-black text-amber-700">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Jaminan & Waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Data Jaminan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Data Jaminan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Kategori</span>
                        <span class="px-2.5 py-1 rounded-xl bg-amber-50 border border-amber-200 text-[#674c1d] text-xs font-black">{{ $gadai->kategori->nama_kategori }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Nama Item</span>
                        <span class="text-sm font-black text-gray-900 text-right max-w-[60%] leading-tight">{{ $gadai->item->head_1 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Lokasi Simpan</span>
                        <span class="text-xs font-bold text-gray-700 text-right">{{ $gadai->lokasi->nama_lokasi }}</span>
                    </div>
                </div>
                {{-- Foto --}}
                @if($gadai->files->isNotEmpty())
                <div class="mt-4 grid grid-cols-3 gap-2">
                    @foreach($gadai->files->take(3) as $file)
                    <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank"
                       class="aspect-square rounded-xl overflow-hidden border border-gray-100 block hover:shadow-md transition-all">
                        <img src="{{ asset('storage/' . $file->path_file) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Siklus Waktu --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Siklus & Waktu</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Tanggal Mulai</span>
                        <span class="text-sm font-black text-gray-800">{{ $mulai->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Jatuh Tempo</span>
                        <span class="text-sm font-black text-blue-600">{{ $jatuhTempo->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 font-bold">Batas Tenggang</span>
                        <span class="text-sm font-black text-red-600">{{ $tenggang->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Batas Perpanjangan</span>
                        <span class="text-[10px] font-black text-gray-700">{{ $gadai->jumlah_perpanjangan }} / {{ $gadai->kategori->max_extend_default }} Kali</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        @php $pct = ($gadai->jumlah_perpanjangan / max(1, $gadai->kategori->max_extend_default)) * 100; @endphp
                        <div class="bg-gradient-to-r from-amber-500 to-[#674c1d] h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    @if($gadai->jumlah_perpanjangan >= $gadai->kategori->max_extend_default)
                    <p class="text-[9px] text-red-500 font-bold mt-1.5">Batas perpanjangan telah habis.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== RIWAYAT TRANSAKSI ===== --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Riwayat Pembayaran & Log</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Jenis</th>
                            <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Jumlah</th>
                            <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @foreach($gadai->paymentLogs as $log)
                        <tr class="hover:bg-gray-50/50 bg-emerald-50/20">
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if(in_array($log->jenis_pembayaran, ['tebus','lunas']))
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 text-[9px] font-black rounded-lg">Pelunasan Gadai</span>
                                @else
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-black rounded-lg">Pembayaran Perpanjangan</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-emerald-600 font-black whitespace-nowrap">Rp {{ number_format($log->nominal, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-gray-500 font-medium">{{ strtoupper($log->metode) }}</td>
                        </tr>
                        @endforeach
                        @foreach($gadai->history as $hist)
                        @if(in_array($hist->aksi, ['extend','lunas'])) @continue @endif
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3 text-gray-400 whitespace-nowrap">{{ $hist->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($hist->aksi === 'create')
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[9px] font-black rounded-lg">Gadai Didaftarkan</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-black rounded-lg">{{ strtoupper($hist->aksi) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-300">—</td>
                            <td class="px-5 py-3 text-gray-500 font-medium">{{ $hist->catatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
