@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Gadai')

@section('content')
<div class="max-w-3xl mx-auto pb-12 mt-6 px-4">
    <!-- Header Title -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Status Pengajuan</h2>
            <p class="text-sm font-medium text-gray-500 mt-1">Pantau status transaksi gadai Anda</p>
        </div>
        <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-sm border border-gray-100 text-gray-500 hover:text-[#674c1d] hover:border-amber-200 hover:bg-amber-50 transition-all group">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
    </div>

    @if($pengajuan->isEmpty())
        <div class="bg-gradient-to-b from-white to-gray-50 rounded-3xl p-10 shadow-lg border border-gray-100 text-center flex flex-col items-center justify-center min-h-[300px]">
            <div class="w-24 h-24 bg-gradient-to-br from-amber-50 to-white rounded-full flex items-center justify-center mb-5 border border-amber-100 shadow-inner">
                <svg class="w-12 h-12 text-[#d4af37] opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengajuan</h3>
            <p class="text-gray-500 text-sm max-w-sm mb-6">Anda belum memiliki riwayat pengajuan perpanjangan ataupun pelunasan gadai saat ini.</p>
            <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#674c1d] hover:bg-[#543e18] text-white text-sm font-bold rounded-xl transition-all shadow-[0_4px_10px_rgba(103,76,29,0.2)] hover:-translate-y-0.5 gap-2">
                Kembali ke Dashboard
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($pengajuan as $item)
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 relative group">
                <!-- Status specific decorative top border -->
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ $item->status == 'pending' ? 'bg-gradient-to-r from-amber-300 to-amber-500' : ($item->status == 'approved' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-gradient-to-r from-red-400 to-rose-500') }}"></div>
                
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border {{ $item->status == 'pending' ? 'bg-amber-50 border-amber-100 text-amber-600 group-hover:scale-110 transition-transform' : ($item->status == 'approved' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-red-50 border-red-100 text-red-600') }}">
                                @if($item->jenis_pengajuan == 'lunas')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider {{ $item->jenis_pengajuan == 'lunas' ? 'bg-indigo-50 text-indigo-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ strtoupper($item->jenis_pengajuan) }}
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-gray-900 text-lg leading-tight">{{ $item->gadaiActive->item->nama_item }}</h3>
                                <p class="text-xs text-gray-500 font-medium mt-1">Diajukan: {{ $item->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-end gap-2 sm:gap-2">
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold tracking-wide shadow-sm border {{ $item->status == 'pending' ? 'bg-amber-100/50 text-amber-700 border-amber-200' : ($item->status == 'approved' ? 'bg-emerald-100/50 text-emerald-700 border-emerald-200' : 'bg-red-100/50 text-red-700 border-red-200') }}">
                                @if($item->status == 'pending')
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        Menunggu Proses
                                    </span>
                                @elseif($item->status == 'approved')
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </span>
                            {{-- 
                            @if($item->status == 'approved')
                            <a href="{{ route('nasabah.struk-gadai', $item->gadai_active_id) }}" target="_blank"
                               class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-black rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm mt-1 sm:mt-0 w-full sm:w-max">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Cetak Struk
                            </a>
                            @endif
                            --}}
                            <p class="text-[10px] text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Tracking Box Area -->
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 shadow-inner">
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div class="col-span-1">
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Nominal</p>
                                <p class="font-black text-[#674c1d] text-lg">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-span-1">
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Metode</p>
                                <p class="font-bold text-gray-800 flex items-center gap-1.5">
                                    @if($item->metode == 'cash')
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    @endif
                                    {{ strtoupper($item->metode) }}
                                </p>
                            </div>

                            @if($item->metode == 'cash')
                            <div class="col-span-2 bg-white rounded-xl p-4 border border-amber-100 shadow-sm flex items-center justify-between mt-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Rencana Janji Temu</p>
                                        <p class="text-sm font-black text-gray-900 mt-0.5">{{ $item->tgl_janji_temu ? $item->tgl_janji_temu->format('d M Y | H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-span-2 mt-2">
                                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mb-2">Bukti Transfer</p>
                                <div class="flex flex-wrap gap-3">
                                    @if($item->files->count() > 0)
                                        @foreach($item->files as $file)
                                            <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Transfer')" class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white ring-1 ring-gray-200 hover:ring-[#d4af37] transition-all shadow-sm hover:shadow-md hover:scale-105 group/img relative">
                                                <img src="{{ asset('storage/'.$file->path_file) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                </div>
                                            </button>
                                        @endforeach
                                    @elseif($item->bukti_transfer)
                                        <button onclick="showPhotoPreview('{{ asset('storage/'.$item->bukti_transfer) }}', 'Bukti Transfer')" class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white ring-1 ring-gray-200 hover:ring-[#d4af37] transition-all shadow-sm hover:shadow-md hover:scale-105 group/img relative">
                                            <img src="{{ asset('storage/'.$item->bukti_transfer) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        </button>
                                    @else
                                        <p class="text-xs text-gray-500 italic bg-white px-3 py-1.5 rounded-lg border border-gray-200">Tidak ada bukti upload</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($item->keterangan)
                            <div class="col-span-2 bg-white rounded-xl p-4 border border-gray-200 mt-2 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 bottom-0 w-1 bg-gray-300"></div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1.5">Catatan Anda</p>
                                <p class="text-sm text-gray-700 font-medium italic">"{{ $item->keterangan }}"</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($item->processed_at)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-wide">Respon Admin</h4>
                                <p class="text-[10px] text-gray-500">Diproses oleh <span class="font-bold text-gray-700">{{ $item->admin->nama ?? 'Sistem' }}</span> pada {{ $item->processed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($item->admin_keterangan)
                        <div class="bg-blue-50/50 rounded-xl p-4 mb-4 border border-blue-100/50 shadow-sm relative overflow-hidden ml-11">
                            <div class="absolute top-0 left-0 bottom-0 w-1 bg-blue-300"></div>
                            <p class="text-sm text-blue-900 font-medium italic">"{{ $item->admin_keterangan }}"</p>
                        </div>
                        @endif

                        @php
                            $adminFiles = $item->files->where('tipe_foto', 'penyerahan');
                        @endphp

                        @if($adminFiles->count() > 0)
                        <div class="ml-11">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-2">Bukti Administrasi / Penyerahan</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($adminFiles as $file)
                                <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Administrasi')" class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white ring-1 ring-gray-200 hover:ring-blue-400 transition-all shadow-sm hover:shadow-md hover:scale-105 group/img relative">
                                    <img src="{{ asset('storage/'.$file->path_file) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
