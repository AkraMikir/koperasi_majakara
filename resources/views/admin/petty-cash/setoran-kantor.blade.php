@extends('layouts.admin')

@section('title', 'Setor Cash ke Kantor')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Setor Cash ke Kantor</h1>
            <p class="text-gray-600 mt-1">Kirim rekap setoran nasabah hari ini ke Owner untuk diverifikasi</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl px-5 py-3 text-white shadow-sm border border-green-500/30">
                <p class="text-[10px] text-white/70 uppercase font-bold tracking-wider">💵 Saldo Cash Saku</p>
                <p class="text-xl font-bold">Rp {{ number_format($saldoCash, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl px-5 py-3 text-white shadow-sm border border-blue-500/30">
                <p class="text-[10px] text-white/70 uppercase font-bold tracking-wider">🏦 Saldo TF Rekening</p>
                <p class="text-xl font-bold">Rp {{ number_format($saldoTransfer, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Ringkasan + Form (2/3) --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs text-green-700 font-medium uppercase">Total Cash Hari Ini</p>
                    <p class="text-2xl font-bold text-green-800 mt-1">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">Uang fisik di tangan</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs text-blue-700 font-medium uppercase">Total Transfer</p>
                    <p class="text-2xl font-bold text-blue-800 mt-1">Rp {{ number_format($totalTf, 0, ',', '.') }}</p>
                    <p class="text-xs text-blue-600 mt-1">Via transfer bank</p>
                </div>
            </div>

            {{-- Tabel Potongan Data --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5 flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-bold text-[#674c1d] uppercase">Potongan Data Hari Ini</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $transaksiPending->count() }} transaksi belum disetor</p>
                    </div>
                    <span class="font-bold text-[#674c1d]">Total: Rp {{ number_format($transaksiPending->sum('nominal'), 0, ',', '.') }}</span>
                </div>

                @if($transaksiPending->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium">Tidak ada transaksi yang perlu disetor</p>
                    <p class="text-sm mt-1">Semua transaksi sudah disetor atau belum ada transaksi hari ini</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Nasabah</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Nominal</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Transaksi</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Via</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Fitur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transaksiPending as $t)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $t->nasabah->user->nama ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $t->nasabah_id }}</p>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-[#674c1d]">
                                    Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-[#674c1d]/10 text-[#674c1d] rounded-lg text-xs font-medium">
                                        {{ $t->jnsTransaksi?->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php $kode = $t->jnsVia?->kode; @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium {{ in_array($kode, ['CS','TN']) ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $t->jnsVia?->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-xs text-gray-600">
                                    {{ $t->jnsFitur?->nama ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- Form Setor --}}
            @if($transaksiPending->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#674c1d] mb-5 font-display">Form Setor ke Kantor</h2>
                <form action="{{ route('admin.petty-cash.setoran-kantor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Upload Foto --}}
                    <div>
                        <label for="foto_setoran" class="block text-sm font-semibold text-gray-700 mb-2">Foto Bukti Setoran ke Kantor</label>
                        <input type="file" name="foto_setoran" id="foto_setoran" accept="image/*"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
                        <p class="text-xs text-gray-500 mt-1">Foto saat menyerahkan uang ke kantor (opsional)</p>
                    </div>

                    {{-- Checkbox Sudah Setor Fisik --}}
                    <div>
                        <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d]/40 transition-colors">
                            <input type="checkbox" name="sudah_setor_fisik" value="1" id="sudah_setor_fisik"
                                class="mt-0.5 text-[#674c1d] rounded" required>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Sudah Disetor Fisik ke Kantor</p>
                                <p class="text-xs text-gray-500 mt-0.5">Centang ini jika Anda sudah menyerahkan uang fisik ke kantor / owner secara langsung</p>
                            </div>
                        </label>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="keterangan_admin" class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="keterangan_admin" id="keterangan_admin" rows="2"
                            placeholder="Catatan tambahan untuk Owner..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent resize-none"></textarea>
                    </div>

                    {{-- Total & Submit --}}
                    <div class="bg-[#674c1d]/5 border border-[#674c1d]/20 rounded-xl p-4 flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm font-medium text-[#674c1d]">Total Setoran</p>
                            <p class="text-xs text-gray-500">{{ $transaksiPending->count() }} transaksi · {{ today()->format('d M Y') }}</p>
                        </div>
                        <p class="text-2xl font-bold text-[#674c1d]">Rp {{ number_format($transaksiPending->sum('nominal'), 0, ',', '.') }}</p>
                    </div>

                    <button type="submit"
                        onclick="return confirm(`Kirim setoran Rp {{ number_format((float) $transaksiPending->sum('nominal'), 0, ',', '.') }} ke Owner? Pastikan sudah centang konfirmasi fisik.`)"
                        class="w-full py-3 bg-[#674c1d] text-white rounded-xl font-bold text-sm hover:bg-[#4a3514] transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Setoran ke Owner
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Riwayat Setoran (1/3) --}}
        <div>
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <h2 class="text-sm font-bold text-[#674c1d] uppercase">Riwayat Setoran</h2>
                </div>
                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($riwayatSetoran as $s)
                    <div class="p-4">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <p class="font-semibold text-gray-900 text-sm">{{ $s->tgl_setoran->format('d M Y') }}</p>
                            @if($s->status === 'pending')
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span>
                            @elseif($s->status === 'approved_owner')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">ACC Owner</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs">Ditolak</span>
                            @endif
                        </div>
                        <p class="font-bold text-[#674c1d] text-sm">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $s->jumlah_nasabah }} nasabah</p>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        <p>Belum ada riwayat setoran</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
