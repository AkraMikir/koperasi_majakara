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

        {{-- Nasabah Info Card --}}
        @php
            $dep = $pencairan->deposito;
            $nasabah = $pencairan->nasabah;
            $isEarly = $dep && $dep->tgl_jatuh_tempo > now();
            $bungaKotor = ($dep && !$isEarly) ? ($dep->nominal_awal * $dep->bunga * (($dep->tenor?->tenor_hari ?? 365) / 365)) : 0;
            $pajak = $bungaKotor * 0.2;
            $estimasiCair = $dep ? ($dep->nominal_awal + $bungaKotor - $pajak) : 0;
        @endphp

        @if($isEarly)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-5 rounded-r-xl">
                <p class="text-sm text-yellow-800 font-bold">INFO: Penarikan Lebih Awal (Early Withdrawal)</p>
                <p class="text-xs text-yellow-700 mt-1">Deposito ditarik sebelum jatuh tempo. Sesuai kebijakan koperasi,
                    pengembalian kembali 100% nominal awal tanpa bunga berjalan.</p>
            </div>
        @endif

        <div class="bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl p-5 text-white mb-5">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-3">Data Nasabah</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-white/70 text-xs">Nama</p>
                    <p class="font-bold">{{ $nasabah?->user?->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-white/70 text-xs">Email</p>
                    <p class="font-semibold text-sm">{{ $nasabah?->user?->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-white/70 text-xs">No. Rekening</p>
                    <p class="font-bold font-mono">{{ $nasabah?->dataRek?->no_rek ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-white/70 text-xs">Nama Bank</p>
                    <p class="font-semibold">{{ $nasabah?->dataRek?->nama_bank ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Deposito Summary --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ringkasan Deposito</p>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">No. Deposito</p>
                    <p class="font-mono font-semibold text-gray-800">{{ $dep?->nomor_deposito ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Tenor</p>
                    <p class="font-semibold text-gray-800">{{ $dep?->tenor?->tenor_bulan ?? '-' }} Bulan</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Nominal Awal</p>
                    <p class="font-bold text-[#674c1d]">Rp {{ number_format($dep?->nominal_awal ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Suku Bunga</p>
                    <p class="font-bold text-[#674c1d]">{{ number_format(($dep?->bunga ?? 0) * 100, 2) }}% p.a.</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Estimasi Bunga Bersih</p>
                    <p class="font-semibold text-green-600">Rp {{ number_format($bungaKotor - $pajak, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Estimasi Total Cair</p>
                    <p class="font-black text-[#674c1d] text-lg">Rp {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Tanggal Mulai</p>
                    <p class="font-semibold text-gray-800">{{ $dep?->tgl_mulai?->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Jatuh Tempo</p>
                    <p class="font-semibold {{ $dep?->tgl_jatuh_tempo?->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $dep?->tgl_jatuh_tempo?->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Proses TF --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    {{ session('error') }}</div>
            @endif

            @if($pencairan->isPending())
                {{-- STAGE 1: OWNER INITIATION --}}
                <p class="text-sm font-bold text-gray-700 mb-4">Tahap 1: Kirim Dana ke Admin Operasional</p>
                <p class="text-xs text-gray-500 mb-4 italic">Dana akan dipotong dari saldo Owner dan dikirim ke Petty Cash Admin
                    terpilih untuk nantinya ditransfer ke nasabah.</p>

                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.proses', $pencairan->id) }}">
                    @csrf

                    {{-- Pilih Admin --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Pilih Admin Operasional (Penerima Dana) <span class="text-red-500">*</span>
                        </label>
                        <select name="admin_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none @error('admin_id') border-red-400 @enderror">
                            <option value="">-- Pilih Admin --</option>
                            @foreach($admins as $adm)
                                <option value="{{ $adm->id }}" {{ old('admin_id') == $adm->id ? 'selected' : '' }}>
                                    {{ $adm->nama }} (Saldo TF: Rp
                                    {{ number_format(\App\Models\PettyCashSaldo::getSaldo($adm->id, 'admin', 'transfer'), 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('admin_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nominal Akhir --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nominal yang Dikirim ke Admin <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">Rp</span>
                            <input type="number" name="nominal_akhir" id="nominal_akhir"
                                value="{{ old('nominal_akhir', round($estimasiCair)) }}" min="1"
                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none @error('nominal_akhir') border-red-400 @enderror"
                                placeholder="{{ number_format($estimasiCair, 0, ',', '.') }}">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Estimasi otomatis: Rp
                            {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                        @error('nominal_akhir')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Catatan untuk admin…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white py-3 rounded-xl text-sm font-bold hover:opacity-90 transition shadow">
                            ✓ Kirim Dana ke Admin
                        </button>
                        <a href="{{ route('admin.deposito.pencairan-tf.index') }}"
                            class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>

            @elseif($pencairan->isDiproses())
                {{-- STAGE 2: ADMIN FINALIZATION --}}
                <p class="text-sm font-bold text-gray-700 mb-2">Tahap 2: Finalisasi Transfer ke Nasabah</p>
                <div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-xl mb-4 border border-blue-100">
                    <p class="font-bold">Informasi PCP:</p>
                    <p>Status: <span class="font-bold">Dana sudah dikirim Owner</span></p>
                    <p>Nominal: <span class="font-bold text-sm">Rp
                            {{ number_format($pencairan->nominal_akhir, 0, ',', '.') }}</span></p>
                    <p class="mt-1 text-[10px] uppercase font-black opacity-70">ADMIN WAJIB MELAKUKAN TRANSFER KE REKENING
                        NASABAH LALU UPLOAD BUKTI DI BAWAH INI.</p>
                </div>

                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.finish', $pencairan->id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="nominal_akhir" value="{{ $pencairan->nominal_akhir }}">

                    {{-- Data Rekening Nasabah (Reminder) --}}
                    <div class="bg-gray-50 p-3 rounded-xl mb-4 border border-gray-200">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Tujuan Transfer</p>
                        <p class="text-sm font-black text-gray-800">{{ $nasabah?->dataRek?->nama_bank ?? '-' }} -
                            {{ $nasabah?->dataRek?->no_rek ?? '-' }}</p>
                        <p class="text-xs text-gray-600">a.n
                            {{ $nasabah?->dataRek?->nama_rekening ?? ($nasabah?->user?->nama ?? '-') }}</p>
                    </div>

                    {{-- Foto Bukti TF --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Upload Bukti Transfer Nasabah <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="foto_bukti_tf" accept="image/*" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#674c1d] file:text-white file:text-xs file:font-semibold file:cursor-pointer @error('foto_bukti_tf') border-red-400 @enderror">
                        @error('foto_bukti_tf')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan Finalisasi (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Misal: Sudah ditransfer via m-Banking BCA..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-green-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow">
                            ✓ Konfirmasi Transfer Selesai
                        </button>
                        <a href="{{ route('admin.deposito.pencairan-tf.index') }}"
                            class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                            Kembali
                        </a>
                    </div>
                </form>
            @endif
        </div>

    </div>
@endsection