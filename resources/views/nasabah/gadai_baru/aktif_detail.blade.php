@extends('layouts.nasabah')

@section('title', 'Detail Gadai Saya')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-10 px-4 mt-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#d4af37] text-white rounded-2xl flex items-center justify-center font-mono font-black text-xl shadow-lg shadow-amber-900/20">
                {{ substr($gadai->slot_kode, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 font-display flex items-center gap-3">
                    Detail Gadai Saya
                    <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-[#674c1d] text-xs rounded-xl font-mono font-bold">{{ $gadai->slot_kode }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Rincian lengkap status barang jaminan dan tagihan pelunasan Anda</p>
            </div>
        </div>
        <div class="flex items-center gap-3 self-start sm:self-auto">
            <a href="{{ route('nasabah.gadai_baru.index') }}" 
                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 border border-gray-200 font-bold rounded-2xl hover:bg-gray-50 transition-colors shadow-sm text-sm">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-green-700 text-sm font-bold shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-amber-700 text-sm font-bold shadow-sm">
        {{ session('warning') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-red-700 text-sm font-bold shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- ===== STATUS BANNER ===== --}}
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $gadai->status == 'grace_period' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                @if($gadai->status == 'grace_period')
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                @endif
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Transaksi Saat Ini</p>
                <div class="flex items-center gap-2 mt-0.5">
                    @if($gadai->status == 'active')
                        <span class="text-lg font-black text-emerald-700">AKTIF / AMAN</span>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg">TERSIMPAN BAIK</span>
                    @elseif($gadai->status == 'grace_period')
                        <span class="text-lg font-black text-red-600">MASA TENGGANG</span>
                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-black rounded-lg animate-pulse">SEGERA TEBUS/PERPANJANG</span>
                    @elseif($gadai->status == 'lunas')
                        <span class="text-lg font-black text-blue-600">LUNAS / SELESAI</span>
                    @elseif($gadai->status == 'expired_final')
                        <span class="text-lg font-black text-gray-700">HANGUS (MENUNGGU LELANG)</span>
                    @else
                        <span class="text-lg font-black text-gray-700">{{ strtoupper($gadai->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- AKSI LANGSUNG DARI BANNER --}}
        @if(in_array($gadai->status, ['active', 'grace_period']))
        @php
            $totalTagihan = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap;
            $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}" 
                class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md hover:shadow-lg transition-all">
                Tebus / Lunasi
            </a>
            @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}" 
                class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md hover:shadow-lg transition-all">
                Perpanjang
            </a>
            @endif
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM 1: Info Nasabah & Barang --}}
        <div class="space-y-6">
            {{-- DATA BARANG FISIK --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-[#674c1d]"></div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Data Jaminan</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori Barang</p>
                                <span class="inline-flex px-2.5 py-1 rounded-xl bg-amber-50 border border-amber-200 text-[#674c1d] text-xs font-black">{{ $gadai->kategori->nama_kategori }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Barang</p>
                                @if($gadai->status == 'active')
                                    <span class="inline-flex px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">Tersimpan Aman</span>
                                @elseif($gadai->status == 'grace_period')
                                    <span class="inline-flex px-2.5 py-1 rounded-xl bg-red-50 text-red-700 text-xs font-bold border border-red-100">Dalam Masa Tenggang</span>
                                @elseif($gadai->status == 'lunas')
                                    <span class="inline-flex px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">Sudah Diambil</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-xl bg-gray-50 text-gray-700 text-xs font-bold border border-gray-100">Menunggu Lelang</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Item</p>
                            <p class="font-black text-gray-900 text-lg leading-tight">{{ $gadai->item->head_1 }}</p>
                            @if($gadai->item->head_2)
                                <p class="text-xs text-gray-500 italic mt-0.5">{{ $gadai->item->head_2 }}</p>
                            @endif
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Lokasi Cabang Penyimpanan:</p>
                            <p class="font-bold text-gray-800 text-xs flex items-center gap-2 mt-1">
                                <svg class="w-4 h-4 text-[#674c1d] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $gadai->lokasi->nama_lokasi }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOTO FISIK --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto Media Jaminan</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($gadai->files as $file)
                        <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank" class="group relative block aspect-square rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 hover:shadow-md transition-all">
                            <img src="{{ asset('storage/' . $file->path_file) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </a>
                    @empty
                        <div class="col-span-2 py-8 flex flex-col items-center justify-center border border-dashed border-gray-200 rounded-2xl bg-gray-50">
                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada foto.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOLOM 2: Info Keuangan & Rincian Bunga --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Rincian Tagihan & Biaya</h3>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <span class="text-gray-600 font-bold text-xs uppercase tracking-wider">Pinjaman Pokok</span>
                            <span class="font-black text-gray-900 text-base">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- BREAKDOWN JASA ADMIN --}}
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-bold text-xs uppercase tracking-wider">Biaya Jasa Admin</span>
                                <span class="text-[9px] text-blue-600 font-black tracking-wide">Tarif Kategori: {{ number_format($gadai->kategori->rate_jasa, 2) }}%</span>
                            </div>
                            <span class="font-black text-gray-900 text-sm">Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- BREAKDOWN DENDA --}}
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-bold text-xs uppercase tracking-wider">Denda Keterlambatan</span>
                                <span class="text-[9px] text-red-600 font-black tracking-wide">Tarif Kategori: {{ number_format($gadai->kategori->rate_denda, 2) }}%</span>
                            </div>
                            <span class="font-black {{ $gadai->denda_aktif > 0 ? 'text-red-600' : 'text-gray-400' }} text-sm">Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- BREAKDOWN INAP --}}
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-bold text-xs uppercase tracking-wider">Biaya Inap</span>
                                @if($gadai->item->nominal_inap > 0)
                                    <span class="text-[9px] text-amber-600 font-black tracking-wide">Tarif Flat (Kendaraan): Rp {{ number_format($gadai->item->nominal_inap, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[9px] text-amber-600 font-black tracking-wide">Tarif Kategori: {{ number_format($gadai->kategori->rate_inap_persen, 2) }}% dari Taksiran</span>
                                @endif
                            </div>
                            <span class="font-black {{ $gadai->biaya_inap > 0 ? 'text-amber-600' : 'text-gray-400' }} text-sm">Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @php
                        $totalTebus = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                    @endphp

                    <div class="space-y-3">
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-black uppercase tracking-widest mb-1">Total Tebus / Pelunasan Sekarang</p>
                            <p class="text-2xl sm:text-3xl font-black text-emerald-700 tracking-tight">Rp {{ number_format($totalTebus, 0, ',', '.') }}</p>
                        </div>
                        
                        @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                            <p class="text-[10px] text-amber-600 font-black uppercase tracking-widest mb-1">Total Untuk Perpanjang Saja</p>
                            <p class="text-xl font-black text-amber-700 tracking-tight">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM 3: Siklus & Waktu --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Siklus & Waktu</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold uppercase tracking-wide">Tanggal Mulai</span>
                            <span class="font-black text-gray-800">{{ $gadai->tgl_mulai->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold uppercase tracking-wide">Jatuh Tempo</span>
                            <span class="font-black text-blue-600">{{ $gadai->tgl_jatuh_tempo->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold uppercase tracking-wide">Batas Akhir Tenggang</span>
                            <span class="font-black text-red-600">{{ $gadai->tgl_tenggang->format('d M Y') }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Batas Perpanjangan</span>
                                <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">{{ $gadai->jumlah_perpanjangan }} / {{ $gadai->kategori->max_extend_default }} Kali</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 mb-1 relative overflow-hidden">
                                @php $pct = ($gadai->jumlah_perpanjangan / $gadai->kategori->max_extend_default) * 100; @endphp
                                <div class="bg-gradient-to-r from-amber-500 to-[#674c1d] h-3 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[9px] text-gray-400 italic">Maksimal perpanjangan default kategori adalah {{ $gadai->kategori->max_extend_default }} kali.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== RIWAYAT TRANSAKSI / MUTASI ===== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-black text-gray-800 flex items-center gap-2 text-sm sm:text-base">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Riwayat Pembayaran & Log Transaksi
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis Mutasi</th>
                        <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs font-bold text-gray-700">
                    {{-- Payment logs --}}
                    @foreach($gadai->paymentLogs as $log)
                    <tr class="hover:bg-gray-50/50 bg-emerald-50/10">
                        <td class="px-6 py-3.5 whitespace-nowrap text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            @if(in_array($log->jenis_pembayaran, ['tebus', 'lunas']))
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 text-[10px] font-black rounded-xl border border-green-200">Pelunasan Gadai</span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-xl border border-emerald-200">Pembayaran Perpanjangan</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap text-emerald-600 font-black">Rp {{ number_format($log->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-3.5 text-gray-500 font-medium">{{ strtoupper($log->metode) }}</td>
                    </tr>
                    @endforeach
                    
                    {{-- History logs --}}
                    @foreach($gadai->history as $hist)
                    @if(in_array($hist->aksi, ['extend', 'lunas'])) @continue @endif
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-3.5 whitespace-nowrap text-gray-500">{{ $hist->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            @if($hist->aksi === 'create')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-xl border border-blue-200">Gadai Didaftarkan</span>
                            @elseif($hist->aksi === 'extend')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-xl border border-amber-200">Masa Gadai Diperpanjang</span>
                            @elseif($hist->aksi === 'lunas')
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 text-[10px] font-black rounded-xl border border-green-200">Gadai Dilunasi</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-xl border border-gray-200">{{ strtoupper($hist->aksi) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap text-gray-400">-</td>
                        <td class="px-6 py-3.5 text-gray-600 font-medium">{{ $hist->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
