@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Gadai')

@section('content')
<div class="w-full pb-10 px-4">
    <div class="mx-auto mt-4 max-w-4xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Status Pengajuan Anda</h2>

        @if($pengajuan->isEmpty())
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada pengajuan pembayaran atau perpanjangan.</p>
                <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-block mt-6 text-[#674c1d] font-bold hover:underline">Kembali ke Dashboard</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($pengajuan as $item)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between p-4 {{ $item->status == 'pending' ? 'bg-amber-50' : ($item->status == 'approved' ? 'bg-emerald-50' : 'bg-red-50') }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-600' : ($item->status == 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600') }}">
                                @if($item->jenis_pengajuan == 'lunas')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-tighter">PENGAJUAN {{ strtoupper($item->jenis_pengajuan) }}</p>
                                <p class="font-bold text-gray-800">{{ $item->gadaiActive->item->nama_item }}</p>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1.5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $item->status == 'pending' ? 'bg-amber-200 text-amber-800' : ($item->status == 'approved' ? 'bg-emerald-200 text-emerald-800' : 'bg-red-200 text-red-800') }}">
                                {{ $item->status }}
                            </span>
                            @if($item->status == 'approved')
                            <a href="{{ route('nasabah.struk-gadai', $item->gadai_active_id) }}" target="_blank"
                               class="px-2 py-0.5 bg-amber-600 hover:bg-amber-700 text-white text-[9px] font-black rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Struk
                            </a>
                            @endif
                            <p class="text-[10px] text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Nominal</p>
                            <p class="font-bold text-gray-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Metode</p>
                            <p class="font-bold text-gray-900">{{ strtoupper($item->metode) }}</p>
                        </div>
                        @if($item->metode == 'cash')
                        <div class="col-span-2 bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium">Rencana Janji Temu</p>
                                <p class="text-sm font-bold text-gray-800">{{ $item->tgl_janji_temu ? $item->tgl_janji_temu->format('d M Y | H:i') : '-' }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        @else
                        <div class="col-span-2">
                            <p class="text-[10px] text-gray-400 font-medium mb-1.5">Bukti Transfer</p>
                            <div class="flex flex-wrap gap-2">
                                @if($item->files->count() > 0)
                                    @foreach($item->files as $file)
                                        <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Transfer')" class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105">
                                            <img src="{{ asset('storage/'.$file->path_file) }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                @elseif($item->bukti_transfer)
                                    <button onclick="showPhotoPreview('{{ asset('storage/'.$item->bukti_transfer) }}', 'Bukti Transfer')" class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-all shadow-xs scale-95 hover:scale-105">
                                        <img src="{{ asset('storage/'.$item->bukti_transfer) }}" class="w-full h-full object-cover">
                                    </button>
                                @else
                                    <p class="text-xs text-gray-500 italic">Tidak ada bukti</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($item->keterangan)
                        <div class="col-span-2 bg-gray-50 rounded-2xl p-4 border border-gray-100 mt-2">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1.5">Keterangan Pengajuan Anda</p>
                            <p class="text-xs text-gray-700 font-medium leading-relaxed italic">"{{ $item->keterangan }}"</p>
                        </div>
                        @endif
                        
                        @if($item->processed_at)
                        <div class="col-span-2 pt-4 mt-2 border-t border-gray-100">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Respon Admin</h4>
                            </div>
                            
                            @if($item->admin_keterangan)
                            <div class="bg-gray-50 rounded-2xl p-4 mb-3 border border-gray-100">
                                <p class="text-xs text-gray-700 font-medium leading-relaxed italic">"{{ $item->admin_keterangan }}"</p>
                            </div>
                            @endif

                            @php
                                $adminFiles = $item->files->where('tipe_foto', 'penyerahan');
                            @endphp

                            @if($adminFiles->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($adminFiles as $file)
                                <button onclick="showPhotoPreview('{{ asset('storage/'.$file->path_file) }}', 'Bukti Administrasi')" class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-xs hover:border-[#674c1d] transition-all">
                                    <img src="{{ asset('storage/'.$file->path_file) }}" class="w-full h-full object-cover">
                                </button>
                                @endforeach
                            </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[10px] text-gray-500">Diproses oleh <span class="font-bold">{{ $item->admin->nama ?? 'Admin' }}</span> pada {{ $item->processed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
