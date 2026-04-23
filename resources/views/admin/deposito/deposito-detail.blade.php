@extends('layouts.admin')

@section('title', 'Detail Deposito')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
        <span>/</span>
        <a href="{{ route('admin.deposito.deposito-list') }}" class="hover:text-[#674c1d]">Daftar Deposito</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $deposito->nomor_deposito }}</span>
    </div>

    {{-- Info Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-4">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs text-gray-400 font-mono mb-0.5">{{ $deposito->nomor_deposito }}</p>
                <h1 class="text-lg font-bold text-gray-800">Deposito {{ $deposito->tenor->tenor_bulan ?? '-' }} Bulan</h1>
            </div>
            @if($deposito->status === 'aktif')
                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
            @elseif($deposito->status === 'dicairkan')
                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">Dicairkan</span>
            @else
                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($deposito->status) }}</span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Nasabah</p>
                <p class="font-semibold text-gray-800">{{ $deposito->nasabah->user->nama ?? '-' }}</p>
                <p class="text-xs text-gray-500">{{ $deposito->nasabah->user->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Nominal Awal</p>
                <p class="font-bold text-2xl text-[#674c1d]">Rp {{ number_format($deposito->nominal_awal, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Suku Bunga</p>
                <p class="font-bold text-[#674c1d] text-lg">{{ number_format($deposito->bunga * 100, 2) }}% <span class="text-sm font-normal text-gray-500">p.a.</span></p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tenor</p>
                <p class="font-semibold text-gray-800">{{ $deposito->tenor->tenor_bulan ?? '-' }} bulan ({{ $deposito->tenor->tenor_hari ?? '-' }} hari)</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Mulai</p>
                <p class="font-semibold text-gray-800">{{ $deposito->tgl_mulai?->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Jatuh Tempo</p>
                <p class="font-semibold {{ $deposito->tgl_jatuh_tempo?->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                    {{ $deposito->tgl_jatuh_tempo?->format('d F Y') }}
                    @if($deposito->status === 'aktif' && $deposito->tgl_jatuh_tempo)
                        @php
                            $now = now();
                            $target = $deposito->tgl_jatuh_tempo;
                            $diff = $now->diff($target);
                            $bulanSisa = $diff->y * 12 + $diff->m;
                            $hariSisaCount = $diff->d;
                            $isPast = $now->gt($target);
                        @endphp
                        <span class="text-xs text-gray-400 ml-1">
                            (@if($isPast)
                                Jatuh Tempo
                            @else
                                {{ $bulanSisa > 0 ? $bulanSisa . ' bulan ' : '' }}{{ $hariSisaCount > 0 || $bulanSisa == 0 ? $hariSisaCount . ' hari ' : '' }}lagi
                            @endif)
                        </span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if($deposito->tgl_mulai && $deposito->tgl_jatuh_tempo && $deposito->status === 'aktif')
        @php
            $totalDays = $deposito->tenor->tenor_hari ?? $deposito->tgl_mulai->diffInDays($deposito->tgl_jatuh_tempo);
            $lewatDays = $deposito->tgl_mulai->diffInDays(now());
            $persen = $totalDays > 0 ? min(100, round(($lewatDays / $totalDays) * 100)) : 0;
        @endphp
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Progress Tenor</span>
                <span class="font-semibold">{{ $persen }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all" style="width: {{ $persen }}%"></div>
            </div>
        </div>
        @endif

        {{-- Estimasi Bunga --}}
        @php
            $bungaPerHari = ((float)$deposito->nominal_awal * (float)$deposito->bunga) / 365;
            $hariTersimpan = $deposito->tgl_mulai ? min($deposito->tgl_mulai->diffInDays(now()), $deposito->tenor->tenor_hari ?? 365) : 0;
            $totalBunga = $bungaPerHari * ($deposito->tenor->tenor_hari ?? 365);
            $bungaAkumulasi = $bungaPerHari * $hariTersimpan;
            $pajak = $totalBunga * 0.20;
            $totalBersih = (float)$deposito->nominal_awal + $totalBunga - $pajak;
        @endphp
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-3 gap-3">
            <div class="bg-amber-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500 mb-1">Estimasi Bunga</p>
                <p class="font-bold text-[#674c1d] text-sm">Rp {{ number_format($totalBunga, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500 mb-1">Pajak (20%)</p>
                <p class="font-bold text-red-600 text-sm">Rp {{ number_format($pajak, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500 mb-1">Total Pencairan</p>
                <p class="font-bold text-green-700 text-sm">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Histori Transaksi --}}
    @if($deposito->transDeposito->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-4">
        <h2 class="font-bold text-gray-800 text-sm mb-3">Histori Transaksi Deposito</h2>
        <div class="divide-y divide-gray-50">
            @foreach($deposito->transDeposito as $trans)
            <div class="py-3 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ ucwords(str_replace('_', ' ', $trans->jenis)) }}</p>
                    <p class="text-xs text-gray-400">{{ $trans->tgl_transaksi ? \Carbon\Carbon::parse($trans->tgl_transaksi)->format('d M Y H:i') : '-' }}</p>
                    @if($trans->keterangan)
                    <p class="text-xs text-gray-500 mt-0.5">{{ $trans->keterangan }}</p>
                    @endif
                </div>
                <p class="font-bold text-sm {{ in_array($trans->jenis, ['bunga_harian', 'setor_awal']) ? 'text-green-600' : 'text-red-500' }}">
                    Rp {{ number_format($trans->nominal, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Info Pencairan (jika sudah dicairkan) --}}
    @if($deposito->pencairan)
    <div class="bg-blue-50 rounded-xl border border-blue-200 p-5 mb-4">
        <h2 class="font-bold text-blue-700 text-sm mb-3">Informasi Pencairan</h2>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-blue-600">Tanggal Cairkan</p>
                <p class="font-semibold text-blue-800">{{ $deposito->pencairan->created_at?->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600">Nominal Dicairkan</p>
                <p class="font-bold text-blue-800">Rp {{ number_format($deposito->pencairan->nominal_akhir ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Dokumen Lampiran --}}
    @if(($deposito->pengajuan && $deposito->pengajuan->foto_bukti_tf) || ($deposito->pencairan && $deposito->pencairan->foto_bukti_tf))
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-4">
        <h2 class="font-bold text-gray-800 text-sm mb-3">Dokumen Lampiran</h2>
        <div class="grid grid-cols-2 gap-4">
            @if($deposito->pengajuan && $deposito->pengajuan->foto_bukti_tf)
            <div>
                <p class="text-xs text-gray-500 mb-2">Bukti Transfer Setor Awal</p>
                <a href="{{ Storage::url($deposito->pengajuan->foto_bukti_tf) }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-colors relative group">
                    <img src="{{ Storage::url($deposito->pengajuan->foto_bukti_tf) }}" alt="Bukti Setor" class="w-full h-32 object-cover transition-transform group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">Lihat Penuh</span>
                    </div>
                </a>
            </div>
            @endif

            @if($deposito->pencairan && $deposito->pencairan->foto_bukti_tf)
            <div>
                <p class="text-xs text-gray-500 mb-2">Bukti Transfer Pencairan</p>
                <a href="{{ Storage::url($deposito->pencairan->foto_bukti_tf) }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-colors relative group">
                    <img src="{{ Storage::url($deposito->pencairan->foto_bukti_tf) }}" alt="Bukti Pencairan" class="w-full h-32 object-cover transition-transform group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">Lihat Penuh</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.deposito.deposito-list') }}" class="text-sm text-gray-500 hover:text-[#674c1d]">← Kembali ke daftar deposito</a>
    </div>
</div>
@endsection
