@extends('layouts.admin')

@section('title', 'Pencairan Deposito via Tabungan')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Pencairan Deposito – Saldo Tabungan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar permintaan pencairan dana langsung ke tabungan nasabah</p>
        </div>
        <a href="{{ route('admin.deposito.index') }}" class="text-sm text-gray-500 hover:text-[#674c1d]">← Dashboard Deposito</a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-blue-700">{{ $pendingCount }}</p>
            <p class="text-xs text-blue-600 font-semibold mt-1">Menunggu Proses</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-gray-700">{{ $pencairans->total() }}</p>
            <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-green-700">{{ $pencairans->where('status','selesai')->count() }}</p>
            <p class="text-xs text-green-600 font-semibold mt-1">Selesai (halaman ini)</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito…"
            class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="selesai" @selected(request('status')=='selesai')>Selesai</option>
        </select>
        <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No. Deposito</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal Akhir</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pencairans as $p)
                @php
                    $dep = $p->deposito;
                    $bungaKotor = $dep ? ($dep->nominal_awal * $dep->bunga * ($dep->tenor?->tenor_hari ?? 365) / 365) : 0;
                    $pajak = $bungaKotor * 0.2;
                    $estimasiCair = $dep ? ($dep->nominal_awal + $bungaKotor - $pajak) : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $p->nasabah?->user?->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $p->nasabah?->user?->email ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $dep?->nomor_deposito ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <p class="font-bold text-[#674c1d]">Rp {{ number_format($p->nominal_akhir, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">Est: Rp {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        {{ $dep?->tgl_jatuh_tempo?->format('d M Y') ?? '-' }}
                        @if($dep?->tgl_jatuh_tempo?->isPast())
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded font-semibold">Jatuh Tempo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($p->status === 'pending')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Pending</span>
                        @elseif($p->status === 'selesai')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($p->status === 'pending')
                        {{-- Modal konfirmasi --}}
                        <button type="button"
                            onclick="document.getElementById('modal-{{ $p->id }}').classList.remove('hidden')"
                            class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-blue-700 transition font-semibold">
                            Cairkan ke Tabungan
                        </button>

                        {{-- Modal --}}
                        <div id="modal-{{ $p->id }}" class="hidden fixed inset-0 bg-gray-800/60 z-50 flex items-center justify-center px-4">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                                <h3 class="font-bold text-gray-800 text-lg mb-2">Konfirmasi Pencairan ke Tabungan</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Dana deposito <strong>{{ $dep?->nomor_deposito }}</strong> milik
                                    <strong>{{ $p->nasabah?->user?->nama }}</strong> senilai
                                    <strong class="text-[#674c1d]">Rp {{ number_format($p->nominal_akhir, 0, ',', '.') }}</strong>
                                    akan langsung ditambahkan ke saldo tabungan. Proses ini tidak dapat dibatalkan.
                                </p>
                                <form method="POST" action="{{ route('admin.deposito.pencairan-tabungan.proses', $p->id) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan (opsional)</label>
                                        <input type="text" name="catatan" placeholder="Catatan tambahan…"
                                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#674c1d]">
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="submit"
                                            class="flex-1 bg-blue-600 text-white py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                                            ✓ Cairkan Sekarang
                                        </button>
                                        <button type="button"
                                            onclick="document.getElementById('modal-{{ $p->id }}').classList.add('hidden')"
                                            class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('admin.deposito.deposito-detail', $p->deposito_id) }}"
                            class="text-xs text-[#674c1d] hover:underline">Lihat Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <p class="text-sm font-semibold">Belum ada request pencairan ke tabungan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pencairans->hasPages())
    <div class="mt-4">{{ $pencairans->links() }}</div>
    @endif
</div>
@endsection
