@extends('layouts.nasabah')

@section('title', 'Angsuran')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.pinjaman.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Angsuran</h1>
                <p class="text-white/90 text-sm">Daftar semua angsuran pinjaman Anda dikelompokkan per pinjaman</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <p class="text-xs font-semibold text-gray-700">Filter Angsuran</p>
            </div>
            <form method="GET" action="{{ route('nasabah.pinjaman.angsuran') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Jenis</label>
                        <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                            <option value="bulanan" {{ request('jenis', 'bulanan') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="mingguan" {{ request('jenis') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                            <option value="">Semua Status</option>
                            <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option>
                            <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="telat" {{ request('status') === 'telat' ? 'selected' : '' }}>Telat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#8b6f2f] focus:ring-2 focus:ring-[#8b6f2f]/20">
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-[#8b6f2f] to-[#a0824d] text-white font-semibold px-6 py-2 rounded-xl hover:shadow-lg transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table: dikelompokkan per pinjaman (No, Pinjaman, Detail Angsuran) -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20 bg-gradient-to-r from-[#8b6f2f]/5 to-[#d4af37]/5">
                            <th class="px-4 py-3 text-center text-xs font-bold text-[#8b6f2f] uppercase w-14">No</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-[#8b6f2f] uppercase min-w-[180px]">Pinjaman</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase min-w-[280px]">Detail Angsuran (urut bulan terdekat)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjamanList ?? [] as $no => $pinjaman)
                        @php
                            $tempos = $jenis === 'bulanan' ? $pinjaman->tempoBulanan : $pinjaman->tempoMingguan;
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-center align-middle text-sm font-semibold text-gray-700 w-14">{{ ($pinjamanList->firstItem() ?? 1) + $no }}</td>
                            <td class="px-4 py-3 text-center align-middle min-w-[180px]">
                                <p class="text-sm font-bold text-gray-900">#{{ $pinjaman->id }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($pinjaman->jenis) }} · {{ $pinjaman->lama_pinjam }} {{ $pinjaman->jenis === 'bulanan' ? 'bulan' : 'minggu' }}</p>
                                <p class="text-xs font-medium text-[#8b6f2f] mt-1">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="rounded-xl overflow-hidden shadow-sm border border-[#8b6f2f]/20 bg-gradient-to-b from-amber-50/80 to-white">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="bg-gradient-to-r from-[#8b6f2f]/15 to-[#d4af37]/10">
                                                <th class="px-3 py-2 text-center font-semibold text-[#8b6f2f] w-12">No</th>
                                                <th class="px-3 py-2 text-left font-semibold text-[#8b6f2f]">Jatuh Tempo</th>
                                                <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Tagihan</th>
                                                <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Denda</th>
                                                <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Terbayar</th>
                                                <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Sisa</th>
                                                <th class="px-3 py-2 text-right font-semibold text-[#8b6f2f]">Total Harus Bayar</th>
                                                <th class="px-3 py-2 text-left font-semibold text-[#8b6f2f]">Status</th>
                                                <th class="px-2 py-2 w-8"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tempos as $t)
                                            @php
                                                $sisa = max(0, $t->jumlah_tagihan - ($t->jumlah_terbayar ?? 0));
                                                $dendaT = $t->denda_berjalan ?? 0;
                                                $totalHarusBayarT = $sisa + $dendaT;
                                                $isTelat = $t->tgl_jatuh_tempo < now() && $t->status_bayar !== 'lunas';
                                                $statusClass = $t->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : ($isTelat ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');
                                                $statusLabel = $t->status_bayar === 'lunas' ? 'Lunas' : ($isTelat ? 'Telat' : 'Belum');
                                            @endphp
                                            <tr class="border-t border-[#8b6f2f]/10 hover:bg-amber-50/50 transition-colors cursor-pointer align-middle"
                                                onclick="window.location.href='{{ route('nasabah.pinjaman.detail-angsuran', ['id' => $t->id, 'jenis' => $jenis]) }}'">
                                                <td class="px-3 py-2 text-center align-middle font-medium text-gray-700 w-12">{{ $t->no_urut }}</td>
                                                <td class="px-3 py-2 align-middle text-gray-700">
                                                    {{ $t->tgl_jatuh_tempo->format('d M Y') }}
                                                    @if($isTelat)
                                                    <span class="block text-red-600 text-[10px]">Telat</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-right align-middle font-semibold text-[#8b6f2f]">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 text-right align-middle {{ $dendaT > 0 ? 'text-red-600 font-medium' : 'text-gray-400' }}">{{ $dendaT > 0 ? 'Rp ' . number_format($dendaT, 0, ',', '.') : '—' }}</td>
                                                <td class="px-3 py-2 text-right align-middle {{ ($t->jumlah_terbayar ?? 0) >= $t->jumlah_tagihan ? 'text-green-600' : 'text-gray-700' }}">Rp {{ number_format($t->jumlah_terbayar ?? 0, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 text-right align-middle font-medium text-gray-900">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 text-right align-middle font-semibold text-[#8b6f2f]">Rp {{ number_format($totalHarusBayarT, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 align-middle">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full {{ $statusClass }} font-medium">{{ $statusLabel }}</span>
                                                </td>
                                                <td class="px-2 py-2 align-middle">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Belum ada pinjaman dengan angsuran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($pinjamanList) && $pinjamanList->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pinjamanList->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
