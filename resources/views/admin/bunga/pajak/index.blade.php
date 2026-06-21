@extends('layouts.admin')

@section('title', 'Pembayaran Pajak Bunga - Koperasi Majakara')

@section('content')
{{-- Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bunga.index') }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#674c1d]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran Pajak Bunga</h1>
                </div>
                <p class="text-sm text-gray-500 mt-1 ml-10">Pencatatan PPh atas pendapatan bunga koperasi ke negara</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bunga.pajak.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d] hover:bg-[#4e3a15] text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Catat Pembayaran
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
</div>
@endif

{{-- Summary Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#674c1d] to-[#a07840] p-6 mb-6 shadow-xl text-white">
    <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/5 rounded-full"></div>
    <div class="absolute -right-4 bottom-0 w-64 h-32 bg-white/5 rounded-full"></div>
    <div class="relative z-10">
        <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider mb-4">Kewajiban PPh Bulan Ini — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-white/10">
            <div class="space-y-1">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                    <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">PPh Pinjaman (15%)</p>
                </div>
                <p class="text-2xl font-bold">Rp {{ number_format($kewajibanPphPinjaman, $kewajibanPphPinjaman == floor($kewajibanPphPinjaman) ? 0 : 2, ',', '.') }}</p>
                <p class="text-amber-300 text-xs">Basis realisasi: Rp {{ number_format($realisasiPinjaman, $realisasiPinjaman == floor($realisasiPinjaman) ? 0 : 2, ',', '.') }}</p>
            </div>
            <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                    <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">PPh Gadai (15%)</p>
                </div>
                <p class="text-2xl font-bold">Rp {{ number_format($kewajibanPphGadai, $kewajibanPphGadai == floor($kewajibanPphGadai) ? 0 : 2, ',', '.') }}</p>
                <p class="text-amber-300 text-xs">Basis realisasi: Rp {{ number_format($realisasiGadai, $realisasiGadai == floor($realisasiGadai) ? 0 : 2, ',', '.') }}</p>
            </div>
            <div class="space-y-1 pt-4 md:pt-0 md:pl-6">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                    <p class="text-amber-200 text-[10px] uppercase font-semibold tracking-wider">PPh Deposito (20%)</p>
                </div>
                <p class="text-2xl font-bold">Rp {{ number_format($kewajibanPphDeposito, $kewajibanPphDeposito == floor($kewajibanPphDeposito) ? 0 : 2, ',', '.') }}</p>
                <p class="text-amber-300 text-xs">Proyeksi dari {{ \App\Models\DepositoH::where('status','aktif')->count() }} deposito aktif</p>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Total Kewajiban PPh Bulan Ini (dari data aktual) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#674c1d]/20 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-[#674c1d]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Kewajiban PPh Bulan Ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalKewajiban, $totalKewajiban == floor($totalKewajiban) ? 0 : 2, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Dihitung dari data realisasi aktual</p>
    </div>
    {{-- Sisa Belum Dicatat --}}
    <div class="bg-white rounded-2xl shadow-sm border border-orange-200 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Belum Dicatat Bulan Ini</span>
        </div>
        <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($sisaBelumDicatat, $sisaBelumDicatat == floor($sisaBelumDicatat) ? 0 : 2, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Kewajiban − sudah dicatat</p>
    </div>
    {{-- Sudah Dibayar (semua periode, dari catatan) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Sudah Dibayar</span>
        </div>
        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($sudahBayar, $sudahBayar == floor($sudahBayar) ? 0 : 2, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Total semua periode (catatan)</p>
    </div>
    {{-- Belum Dibayar (dari catatan, status belum_bayar) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-gray-500 font-medium">Belum Dibayar (Catatan)</span>
        </div>
        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($belumBayar, $belumBayar == floor($belumBayar) ? 0 : 2, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-2">Status belum_bayar · {{ $jumlahRecord }} total catatan</p>
    </div>
</div>

{{-- Filter + Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h3 class="font-semibold text-gray-900">Daftar Catatan Pembayaran Pajak</h3>
            <p class="text-xs text-gray-400 mt-0.5">Semua pencatatan pembayaran PPh bunga</p>
        </div>
        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.bunga.pajak.index') }}" class="flex items-center gap-2 flex-wrap">
            <select name="jenis" class="text-sm border border-gray-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#8b6f2f]">
                <option value="">Semua Jenis</option>
                <option value="pph_pinjaman" {{ request('jenis') === 'pph_pinjaman' ? 'selected' : '' }}>PPh Pinjaman</option>
                <option value="pph_gadai" {{ request('jenis') === 'pph_gadai' ? 'selected' : '' }}>PPh Gadai</option>
                <option value="pph_deposito" {{ request('jenis') === 'pph_deposito' ? 'selected' : '' }}>PPh Deposito</option>
            </select>
            <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Periode</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Jenis</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Kotor (Basis)</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Tarif</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Jumlah Pajak</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Bersih</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase pb-3 pr-4">Status</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase pb-3">Bukti</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase pb-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 pr-4">
                        <span class="font-medium text-gray-900">{{ $record->periode_label }}</span>
                    </td>
                    <td class="py-3 pr-4">
                        @php
                            $jenisColor = match($record->jenis_pajak) {
                                'pph_pinjaman' => 'text-green-700 bg-green-50',
                                'pph_gadai'    => 'text-amber-700 bg-amber-50',
                                'pph_deposito' => 'text-red-600 bg-red-50',
                                default        => 'text-gray-600 bg-gray-50',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $jenisColor }}">
                            {{ $record->jenis_label }}
                        </span>
                    </td>
                    <td class="py-3 pr-4 text-right text-gray-700">Rp {{ number_format($record->jumlah_kotor, $record->jumlah_kotor == floor($record->jumlah_kotor) ? 0 : 2, ',', '.') }}</td>
                    <td class="py-3 pr-4 text-right">
                        <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">{{ number_format($record->tarif_persen, $record->tarif_persen == floor($record->tarif_persen) ? 0 : 1) }}%</span>
                    </td>
                    <td class="py-3 pr-4 text-right font-bold text-red-600">Rp {{ number_format($record->jumlah_pajak, $record->jumlah_pajak == floor($record->jumlah_pajak) ? 0 : 2, ',', '.') }}</td>
                    <td class="py-3 pr-4 text-right text-gray-600">Rp {{ number_format($record->jumlah_bersih, $record->jumlah_bersih == floor($record->jumlah_bersih) ? 0 : 2, ',', '.') }}</td>
                    <td class="py-3 pr-4 text-center">
                        @if($record->status === 'sudah_bayar')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold text-green-700 bg-green-50 border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold text-red-600 bg-red-50 border border-red-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01"/></svg>
                                Belum
                            </span>
                        @endif
                    </td>
                    <td class="py-3 pr-4 text-center">
                        @if($record->bukti_bayar)
                            <a href="{{ Storage::url($record->bukti_bayar) }}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Lihat Bukti">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.bunga.pajak.edit', $record->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.bunga.pajak.destroy', $record->id) }}" onsubmit="return confirm('Yakin hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            <p class="text-sm font-medium">Belum ada catatan pembayaran pajak</p>
                            <a href="{{ route('admin.bunga.pajak.create') }}" class="mt-1 text-xs text-[#674c1d] font-semibold hover:underline">Catat pembayaran pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
