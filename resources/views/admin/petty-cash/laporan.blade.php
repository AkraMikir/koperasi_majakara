@extends('layouts.admin')

@section('title', 'Laporan Petty Cash')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Petty Cash</h1>
            <p class="text-gray-600 mt-1">Riwayat lengkap aliran dana operasional</p>
        </div>
        <a href="{{ route('admin.petty-cash.dashboard') }}"
           class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            ← Dashboard
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Card Emas: Dompet Utama Internal --}}
        <div class="bg-white border border-[#674c1d]/20 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-[#674c1d]">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h9zm-9-2h10V8H12v8z"/></svg>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-[#674c1d]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-[#674c1d] uppercase">Modal & Biaya Internal</p>
                    <p class="text-[10px] text-gray-500 font-medium">Dana Masuk vs Biaya Kantor</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-medium tracking-tight">Dana Masuk (Modal):</span>
                    <span class="text-sm font-bold text-green-600">+ Rp {{ number_format($manualIn, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <span class="text-xs text-gray-500 font-medium tracking-tight">Dana Keluar (Biaya):</span>
                    <span class="text-sm font-bold text-red-600">- Rp {{ number_format($manualOut, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Net Internal</span>
                    <span class="text-md font-extrabold text-[#674c1d]">Rp {{ number_format($manualIn - $manualOut, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Card Hijau: Operasional (Owner -> Admin) --}}
        <div class="bg-white border border-green-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-green-700 uppercase">Total Pengiriman</p>
                    <p class="text-[10px] text-green-500 font-medium">Owner → Admin Operasional</p>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-green-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cash (Fisik)</p>
                    <p class="text-sm font-bold text-green-600">Rp {{ number_format($totalPenerimaanCash, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Transfer (Bank)</p>
                    <p class="text-sm font-bold text-green-600">Rp {{ number_format($totalPenerimaanTf, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Card Biru: Operasional (Admin -> Owner) --}}
        <div class="bg-white border border-blue-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-blue-700 uppercase">Total Setoran</p>
                    <p class="text-[10px] text-blue-500 font-medium">Admin Operasional → Owner</p>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</p>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-blue-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cash (Fisik)</p>
                    <p class="text-sm font-bold text-blue-600">Rp {{ number_format($totalSetoranCash, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Transfer (Bank)</p>
                    <p class="text-sm font-bold text-blue-600">Rp {{ number_format($totalSetoranTf, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Rekonsiliasi --}}
    <div class="bg-indigo-900 text-white rounded-2xl p-6 shadow-lg shadow-indigo-900/20 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
        <div class="absolute -right-10 -top-10 opacity-10">
            <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        </div>
        <div class="relative z-10">
            <h3 class="text-lg font-bold font-display tracking-wide mb-1 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04m12.892 4.48c.058.666.088 1.34.088 2.02a11.952 11.952 0 01-13.633 11.853m15.356-18.063l-1.045 1.045m1.045-1.045l-1.045 1.045M9.045 15.045l1.045-1.045m-1.045 1.045l1.045-1.045M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Audit System: Rumus Keseimbangan Dana
            </h3>
            <p class="text-indigo-200 text-xs">
                (Manual In + Total Setoran) - (Manual Out + Total Kiriman) = 
                <span class="text-white font-black underline uppercase ml-1">Saldo Saat Ini</span>
            </p>
        </div>
        <div class="flex items-center gap-6 relative z-10 border-t md:border-t-0 md:border-l border-white/20 pt-4 md:pt-0 md:pl-8">
            <div class="text-right">
                <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Total Saldo Dompet Utama</p>
                <p class="text-3xl font-black font-display">Rp {{ number_format($currentSaldoCash + $currentSaldoTf, 0, ',', '.') }}</p>
            </div>
            <div class="hidden lg:block w-px h-10 bg-white/20"></div>
            <div class="hidden lg:flex flex-col gap-1 text-[10px] font-bold text-white/60">
                <span class="flex justify-between gap-4">CASH: <span class="text-white">Rp {{ number_format($currentSaldoCash, 0, ',', '.') }}</span></span>
                <span class="flex justify-between gap-4">BANK: <span class="text-white">Rp {{ number_format($currentSaldoTf, 0, ',', '.') }}</span></span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Admin</label>
                <select name="admin_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]">
                    <option value="">Semua Admin</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                <select name="status" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved_owner" {{ request('status') === 'approved_owner' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-[#674c1d] text-white rounded-xl text-sm font-semibold hover:bg-[#4a3514] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['admin_id', 'tanggal_dari', 'tanggal_sampai', 'status']))
            <a href="{{ route('admin.petty-cash.laporan') }}"
               class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm hover:bg-gray-50 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Admin</th>
                        <th class="px-5 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Setor Fisik</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporan as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 text-sm text-gray-700">{{ $item->tgl_setoran->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-900 text-sm">{{ $item->admin->nama ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-[#674c1d]">Rp {{ number_format($item->total_setor, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-center text-sm">{{ $item->jumlah_nasabah }} orang</td>
                        <td class="px-5 py-4 text-center">
                            <span class="{{ $item->sudah_setor_fisik ? 'text-green-600' : 'text-yellow-600' }} text-sm">
                                {{ $item->sudah_setor_fisik ? '✅' : '⏳' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($item->status === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                            @elseif($item->status === 'approved_owner')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">ACC</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button onclick="document.getElementById('detail-lap-{{ $item->id }}').classList.toggle('hidden')"
                                class="text-xs text-[#674c1d] hover:underline font-medium">
                                Detail
                            </button>
                        </td>
                    </tr>
                    {{-- Detail Row --}}
                    <tr id="detail-lap-{{ $item->id }}" class="hidden bg-gray-50">
                        <td colspan="7" class="px-5 py-4">
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-gray-600 w-32">ID</th>
                                            <th class="px-3 py-2 text-left text-gray-600">Nasabah</th>
                                            <th class="px-3 py-2 text-right text-gray-600">Nominal</th>
                                            <th class="px-3 py-2 text-center text-gray-600">Via</th>
                                            <th class="px-3 py-2 text-center text-gray-600">Fitur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach((array) $item->data_potongan as $d)
                                        @php
                                            // 1. Ambil langsung dari JSON (data baru)
                                            $displayId = $d['ref_id'] ?? null;
                                            
                                            // 2. Fallback untuk data lama (data_potongan di DB belum ada ref_id)
                                            if (!$displayId && isset($d['pctn_id'])) {
                                                $pctn = $item->transaksiNasabah->firstWhere('id', $d['pctn_id']);
                                                if ($pctn) {
                                                    // Ambil dari ref_id di tbl petty_cash_transaksi_nasabah
                                                    $displayId = $pctn->ref_id;
                                                    
                                                    // Jika masih null, coba cari via relationship (untuk data yang sangat lama/salah input)
                                                    if (!$displayId) {
                                                        // Cek Tabungan
                                                        if ($pctn->transTabungan) {
                                                            $displayId = $pctn->transTabungan->id;
                                                        } 
                                                        // Cek Pengajuan (Angsuran/Setoran)
                                                        elseif ($pctn->pengajuanPembayaran) {
                                                            $displayId = $pctn->pengajuanPembayaran->id;
                                                        }
                                                        elseif ($pctn->pengajuanTabungan) {
                                                            $displayId = $pctn->pengajuanTabungan->id;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 font-mono text-gray-500 whitespace-nowrap">{{ $displayId ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $d['nama'] ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right font-semibold">Rp {{ number_format($d['nominal'] ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-center">{{ $d['via'] ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center">{{ $d['fitur'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-gray-500">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Tidak ada data laporan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporan->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $laporan->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
