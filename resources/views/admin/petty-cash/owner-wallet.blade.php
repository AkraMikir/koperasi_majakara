@extends('layouts.admin')

@section('title', 'Dompet Utama Owner')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dompet Utama (Main Wallet)</h1>
            <p class="text-gray-600 mt-1">Kelola saldo utama, modal, dan pengeluaran kantor pusat</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="openModal('modalMasuk')"
                    class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Dana Masuk (Modal)
            </button>
            <button onclick="openModal('modalKeluar')"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
                Dana Keluar
            </button>
            <button onclick="openModal('modalWithdraw')"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-semibold hover:bg-purple-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/>
                </svg>
                Tarik Saldo
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Saldo Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Saldo --}}
        <div class="bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-white/70 text-sm font-medium mb-1">Total Saldo Wallet Utama</p>
                <p class="text-4xl font-extrabold font-display">Rp {{ number_format($saldoCash + $saldoTf, 0, ',', '.') }}</p>
                <div class="mt-4 flex items-center gap-2 text-xs text-white/60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04m12.892 4.48c.058.666.088 1.34.088 2.02a11.952 11.952 0 01-13.633 11.853m15.356-18.063l-1.045 1.045m1.045-1.045l-1.045 1.045M9.045 15.045l1.045-1.045m-1.045 1.045l1.045-1.045"/>
                    </svg>
                    <span>Terverifikasi Aman (Audit-Proof)</span>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                </svg>
            </div>
        </div>

        {{-- Saldo Cash --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-l-green-500">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-sm font-medium text-uppercase tracking-wider">Cash Fisik</p>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 font-display">Rp {{ number_format($saldoCash, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2">Disimpan di Brankas Kantor</p>
        </div>

        {{-- Saldo TF --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-500 text-sm font-medium text-uppercase tracking-wider">Mutasi Bank (TF)</p>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 font-display">Rp {{ number_format($saldoTf, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2">Tersedia di Rekening Pusat</p>
        </div>
    </div>

    {{-- Saldo per Sumber --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 mb-8">
        @php
            $sources = [
                'tabungan' => ['Tabungan', 'purple', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                'pinjaman' => ['Pinjaman', 'blue', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                'petty_cash' => ['Petty (Clearing)', 'emerald', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                'other' => ['Modal Awal', 'amber', 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
            ];
        @endphp

        @foreach($sources as $key => $info)
        @php
            $det = (object)($sourceDetails[$key] ?? ['total_cash' => 0, 'total_tf' => 0]);
            $total = $det->total_cash + $det->total_tf;
            $color = $info[1];
        @endphp
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-{{ $color }}-100 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-12 h-12 bg-{{ $color }}-100 rounded-xl flex items-center justify-center flex-shrink-0 text-{{ $color }}-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info[2] }}"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Saldo {{ $info[0] }}</p>
                <p class="text-xl font-black text-{{ $color }}-900 font-display">Rp {{ number_format($total, 0, ',', '.') }}</p>
                <div class="flex items-center gap-3 mt-1.5 pt-1.5 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[9px] text-gray-400 uppercase font-bold">Cash</span>
                        <span class="text-[11px] font-bold text-gray-700">Rp {{ number_format($det->total_cash, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-px h-4 bg-gray-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] text-gray-400 uppercase font-bold">Bank</span>
                        <span class="text-[11px] font-bold text-gray-700">Rp {{ number_format($det->total_tf, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter & Recent Transactions --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-[#674c1d] font-display">Riwayat Transaksi Wallet Utama</h2>
            
            <form action="{{ route('admin.petty-cash.owner-wallet.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-sm">
                <select name="sumber" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]">
                    <option value="">Semua Sumber</option>
                    <option value="tabungan" {{ request('sumber') == 'tabungan' ? 'selected' : '' }}>Tabungan</option>
                    <option value="pinjaman" {{ request('sumber') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                    <option value="petty_cash" {{ request('sumber') == 'petty_cash' ? 'selected' : '' }}>Petty Cash</option>
                    <option value="other" {{ request('sumber') == 'other' ? 'selected' : '' }}>Modal Awal</option>
                </select>
                <select name="tipe" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]">
                    <option value="">Semua Tipe</option>
                    <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Dana Masuk</option>
                    <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Dana Keluar</option>
                    <option value="kirim_admin_hold" {{ request('tipe') == 'kirim_admin_hold' ? 'selected' : '' }}>Kirim ke Admin</option>
                    <option value="terima_setoran" {{ request('tipe') == 'terima_setoran' ? 'selected' : '' }}>Terima Setoran</option>
                </select>
                <input type="date" name="tgl_dari" value="{{ request('tgl_dari') }}" class="rounded-lg border-gray-300 focus:ring-[#674c1d] focus:border-[#674c1d]">
                <button type="submit" class="p-2 bg-[#674c1d] text-white hover:bg-[#4a3514] rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">ID / Waktu</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Sumber / Tipe / Keterangan</th>
                        <th class="px-6 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-right font-bold text-gray-700 uppercase tracking-wider bg-gray-100/50">Running Balance</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Bukti</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900 text-xs">{{ $t->id }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y - H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                @php
                                    $sumberBadge = [
                                        'tabungan' => ['bg-purple-100 text-purple-700', 'TABUNGAN'],
                                        'pinjaman' => ['bg-blue-100 text-blue-700', 'PINJAMAN'],
                                        'petty_cash' => ['bg-emerald-100 text-emerald-700', 'PETTY CASH'],
                                        'other' => ['bg-amber-100 text-amber-700', 'MODAL AWAL'],
                                    ];
                                    $tipeBadge = [
                                        'masuk' => ['bg-green-100 text-green-700', 'MASUK'],
                                        'keluar' => ['bg-red-100 text-red-700', 'KELUAR'],
                                        'kirim_admin_hold' => ['bg-amber-100 text-amber-700', 'HOLD'],
                                        'terima_setoran' => ['bg-blue-100 text-blue-700', 'SETORAN'],
                                        'penerimaan_angsuran' => ['bg-indigo-100 text-indigo-700', 'ANGSURAN'],
                                    ];
                                    $sBadge = $sumberBadge[$t->sumber ?? 'other'] ?? ['bg-gray-50 text-gray-500', strtoupper($t->sumber ?? 'N/A')];
                                    $tBadge = $tipeBadge[$t->tipe] ?? ['bg-gray-50 text-gray-500', strtoupper($t->tipe)];
                                @endphp
                                <span class="px-2 py-0.5 {{ $sBadge[0] }} rounded text-[10px] font-extrabold border border-current/10">{{ $sBadge[1] }}</span>
                                <span class="px-2 py-0.5 {{ $tBadge[0] }} rounded text-[10px] font-extrabold">{{ $tBadge[1] }}</span>
                            </div>
                            <p class="text-gray-700 text-xs leading-relaxed font-medium">{{ $t->keterangan }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="space-y-1">
                                @if($t->nominal_cash != 0)
                                <div class="text-[10px]">
                                    <span class="text-gray-400 mr-1 uppercase">Cash:</span>
                                    <span class="font-bold {{ $t->nominal_cash > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $t->nominal_cash > 0 ? '+' : '' }} Rp {{ number_format($t->nominal_cash, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endif
                                @if($t->nominal_tf != 0)
                                <div class="text-[10px]">
                                    <span class="text-gray-400 mr-1 uppercase">Bank:</span>
                                    <span class="font-bold {{ $t->nominal_tf > 0 ? 'text-blue-600' : 'text-purple-600' }}">
                                        {{ $t->nominal_tf > 0 ? '+' : '' }} Rp {{ number_format($t->nominal_tf, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endif
                                <div class="pt-1 border-t border-gray-100 font-extrabold text-gray-900">
                                    {{ $t->mutasi > 0 ? '+' : '' }} Rp {{ number_format($t->mutasi, 0, ',', '.') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right bg-gray-50/50">
                            <p class="text-sm font-black text-[#674c1d] font-display">
                                Rp {{ number_format($t->running_balance, 0, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                @if($t->bukti_foto_cash)
                                <button onclick="viewImage('{{ asset('storage/' . $t->bukti_foto_cash) }}', 'Bukti Cash')"
                                        class="p-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100" title="Lihat Bukti Cash">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    </svg>
                                </button>
                                @endif
                                @if($t->bukti_foto_tf)
                                <button onclick="viewImage('{{ asset('storage/' . $t->bukti_foto_tf) }}', 'Bukti Transfer')"
                                        class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100" title="Lihat Bukti TF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                                @endif
                                @if(!$t->bukti_foto_cash && !$t->bukti_foto_tf)
                                <span class="text-gray-300 text-[10px] italic">No Evidence</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(in_array($t->tipe, ['masuk', 'keluar']))
                            <form action="{{ route('admin.petty-cash.owner-wallet.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo akan disesuaikan otomatis.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.172a2 2 0 01-1.414-.586l-1.414-1.414a2 2 0 00-1.414-.586H8.414a2 2 0 00-1.414.586l-1.414 1.414a2 2 0 01-1.414.586H3m14 18h2a2 2 0 002-2v-5m-16 0h2a2 2 0 012 2v5m16 0h-4m-8 0H3"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Belum ada riwayat transaksi di Wallet Utama</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div class="p-6 border-t border-gray-100">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modals --}}
@include('admin.petty-cash.partials._owner_wallet_modals')

{{-- Image Viewer Modal --}}
<div id="imageViewer" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeModal('imageViewer')" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-sm flex items-center gap-1 font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            TUTUP
        </button>
        <div class="bg-white rounded-2xl p-2 overflow-hidden">
            <h3 id="viewerTitle" class="text-sm font-bold text-gray-700 px-4 py-2 border-b">Bukti Transaksi</h3>
            <img id="viewerImg" src="" alt="Proof" class="w-full max-h-[80vh] object-contain">
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function viewImage(src, title) {
        document.getElementById('viewerImg').src = src;
        document.getElementById('viewerTitle').innerText = title;
        openModal('imageViewer');
    }

    // Close on click outside
    window.onclick = function(event) {
        ['modalMasuk', 'modalKeluar', 'modalWithdraw', 'imageViewer'].forEach(id => {
            const modal = document.getElementById(id);
            if (event.target == modal) {
                closeModal(id);
            }
        });
    }

    // 🔥 Global Form Submission Protector
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
        }
    });
</script>
@endpush
@endsection
