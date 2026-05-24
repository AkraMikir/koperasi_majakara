@extends('layouts.admin')

@section('title', 'Proses Pencairan TF - ' . $pencairan->deposito?->nomor_deposito)

@section('content')
    <div class="p-6 max-w-2xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
            <span>/</span>
            <a href="{{ route('admin.deposito.pencairan-tf.index') }}" class="hover:text-[#674c1d]">Pencairan TF</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $pencairan->deposito?->nomor_deposito }}</span>
        </div>

        @php
            $dep = $pencairan->deposito;
            $nasabah = $pencairan->nasabah;
            $isCancel = $pencairan->is_cancel;
            $isEarly = $dep && $dep->tgl_jatuh_tempo > now();
            $bungaKotor = ($dep && !$isEarly) ? ($dep->nominal_awal * $dep->bunga * (($dep->tenor?->tenor_hari ?? 365) / 365)) : 0;
            $pajak = $bungaKotor * 0.2;
            
            if ($isCancel) {
                $estimasiCair = $dep ? $dep->nominal_awal : 0;
            } else {
                $estimasiCair = $dep ? ($dep->nominal_awal + $bungaKotor - $pajak) : 0;
            }
        @endphp

        {{-- Simplified Destination Card --}}
        <div class="bg-gradient-to-br {{ $isCancel ? 'from-red-600 to-red-800' : 'from-[#674c1d] to-[#8b6f2f]' }} rounded-2xl p-6 text-white mb-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest">{{ $isCancel ? 'Pembatalan - Tujuan Transfer' : 'Tujuan Transfer (Nasabah)' }}</p>
                @if($isCancel)
                    <span class="bg-white/20 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">Tanpa Bunga</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-black font-mono tracking-tighter">{{ $nasabah?->dataRek?->no_rekening ?? '-' }}</p>
                    <p class="text-sm font-bold opacity-90">{{ $nasabah?->dataRek?->nama_bank ?? '-' }}</p>
                    <p class="text-xs opacity-70 mt-1">a.n {{ $nasabah?->dataRek?->nama_pemilik_rekening ?? ($nasabah?->user?->nama ?? '-') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[10px] font-bold uppercase">Estimasi Cair</p>
                    <p class="text-xl font-black">Rp {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Simplified Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif

            @php
                $isDirect = $pencairan->isPending() && auth()->user()->role === 'admin_operasional';
                $isManual = $pencairan->isDiproses();
            @endphp

            @if(auth()->user()->role === 'admin_utama' && $pencairan->isPending())
                {{-- OWNER FLOW: Send Dana to Admin --}}
                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.proses', $pencairan->id) }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Admin Penerima</label>
                        <select name="admin_id" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <option value="">-- Pilih Admin --</option>
                            @foreach($admins as $adm)
                                <option value="{{ $adm->id }}">{{ $adm->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="nominal_akhir" value="{{ round($estimasiCair) }}"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 font-bold text-lg text-[#674c1d] outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg">
                        ✓ Kirim Dana ke Admin
                    </button>
                </form>
            @elseif($isDirect || $isManual)
                {{-- ADMIN FLOW: Finalize Liquidation --}}
                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.finish', $pencairan->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="nominal_akhir" value="{{ $isManual ? round($pencairan->nominal_akhir) : round($estimasiCair) }}"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 font-bold text-lg text-[#674c1d] outline-none">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Foto</label>
                        <div class="relative group h-32">
                            <input type="file" name="foto_bukti_tf" accept="image/*" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="h-full border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center group-hover:border-[#674c1d] transition bg-gray-50/50">
                                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Klik untuk Upload Bukti</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg">
                        ✓ Konfirmasi Cairkan
                    </button>
                </form>
            @endif
        </div>
       </div>

    </div>
@endsection