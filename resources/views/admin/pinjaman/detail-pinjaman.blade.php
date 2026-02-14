@extends('layouts.admin')

@section('title', 'Detail Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pinjaman</h1>
            <p class="text-gray-600 mt-1">ID Pinjaman: #{{ $pinjaman->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pinjaman -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi
                    Pinjaman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Pinjam</p>
                        <p class="font-semibold text-[#674c1d] text-xl">Rp
                            {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis</p>
                        <span
                            class="inline-block mt-2 px-4 py-2 {{ $pinjaman->jenis === 'bulanan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($pinjaman->jenis) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lama Pinjam</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->lama_pinjam }}
                            {{ $pinjaman->jenis === 'bulanan' ? 'bulan' : 'minggu' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pinjam</p>
                        <p class="font-semibold text-gray-900">{{ $pinjaman->tgl_pinjam->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bunga</p>
                        <p class="font-semibold text-gray-900">{{ number_format($pinjaman->bunga, 2) }}%</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($pinjaman->bunga_rp, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Denda</p>
                        <p class="font-semibold text-gray-900">{{ number_format($pinjaman->denda_persen, 2) }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span
                            class="inline-block mt-2 px-4 py-2 {{ $pinjaman->status === 'telaksana' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($pinjaman->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lunas</p>
                        <span
                            class="inline-block mt-2 px-4 py-2 {{ $pinjaman->lunas === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($pinjaman->lunas) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Jadwal Angsuran -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Jadwal
                    Angsuran</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No. Urut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah
                                    Tagihan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Terbayar</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($angsuran as $item)
                            <tr
                                class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                                <td class="px-4 py-3 text-sm font-medium">#{{ $item->no_urut }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-[#674c1d]">Rp
                                        {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">Rp
                                        {{ number_format($item->jumlah_terbayar, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                    $statusConfig = [
                                    'belum' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                    'lunas' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                    'telat' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                    ];
                                    $status = $statusConfig[$item->status_bayar] ?? $statusConfig['belum'];
                                    @endphp
                                    <span
                                        class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold">
                                        {{ ucfirst($item->status_bayar) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <p class="text-gray-500">Belum ada jadwal angsuran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary (sticky) -->
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Ringkasan</h3>
                <div class="space-y-3">
                    @php
                    $totalTagihan = $angsuran->sum('jumlah_tagihan');
                    $totalTerbayar = $angsuran->sum('jumlah_terbayar');
                    $sisaTagihan = $totalTagihan - $totalTerbayar;
                    $lunasCount = $angsuran->where('status_bayar', 'lunas')->count();
                    $belumLunasCount = $angsuran->where('status_bayar', '!=', 'lunas')->count();
                    @endphp
                    <div>
                        <p class="text-sm text-gray-600">Total Tagihan</p>
                        <p class="font-semibold text-gray-900 text-lg">Rp
                            {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Terbayar</p>
                        <p class="font-semibold text-green-600 text-lg">Rp
                            {{ number_format($totalTerbayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sisa Tagihan</p>
                        <p class="font-semibold text-[#674c1d] text-lg">Rp
                            {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Angsuran Lunas</p>
                        <p class="font-semibold text-gray-900">{{ $lunasCount }} / {{ $angsuran->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Angsuran Belum Lunas</p>
                        <p class="font-semibold text-yellow-600">{{ $belumLunasCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Pelunasan Dipercepat -->
            @if($pinjaman->lunas === 'belum')
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Pelunasan Dipercepat</h3>
                <button onclick="showPelunasanModal()"
                    class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                    Pelunasan
                </button>
            </div>

            <!-- Modal Pelunasan Dipercepat -->
            <div id="pelunasanModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pelunasan Dipercepat</h3>
                    <form method="POST" action="{{ route('admin.pinjaman.pelunasan-dipercepat', $pinjaman->id) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin melakukan pelunasan dipercepat?')">
                        @csrf
                        @php
                        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
                        $totalTerbayar = $angsuran->sum('jumlah_terbayar');
                        $sisaTagihanPokok = $totalTagihan - $totalTerbayar;

                        // Hitung total denda
                        $totalDenda = 0;
                        foreach ($angsuran as $a) {
                        if ($a->status_bayar !== 'lunas') {
                        $hariTelat = $a->tgl_jatuh_tempo < now() ? now()->diffInDays($a->tgl_jatuh_tempo, false) : 0;
                            if ($hariTelat > 0) {
                            $sisaTagihanAngsuran = max(0, $a->jumlah_tagihan - ($a->jumlah_terbayar ?? 0));
                            $denda = $sisaTagihanAngsuran * ($pinjaman->denda_persen / 100) * $hariTelat;
                            $dendaMax = $a->jumlah_tagihan * 0.5;
                            $totalDenda += min($denda, $dendaMax);
                            }
                            }
                            }
                            $totalBayar = $sisaTagihanPokok + $totalDenda;
                            @endphp
                            <div class="space-y-4 mb-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">Ringkasan:</p>
                                    <div class="space-y-1">
                                        <div class="flex justify-between">
                                            <span class="text-sm">Sisa Tagihan Pokok:</span>
                                            <span class="text-sm font-semibold">Rp
                                                {{ number_format($sisaTagihanPokok, 0, ',', '.') }}</span>
                                        </div>
                                        @if($totalDenda > 0)
                                        <div class="flex justify-between">
                                            <span class="text-sm">Total Denda:</span>
                                            <span class="text-sm font-semibold text-red-600">Rp
                                                {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                        <div class="flex justify-between pt-2 border-t border-gray-300">
                                            <span class="font-semibold">Total Pembayaran:</span>
                                            <span class="font-bold text-[#674c1d]">Rp
                                                {{ number_format($totalBayar, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Potongan (Opsional,
                                        Rp)</label>
                                    <input type="number" name="potongan" step="0.01" min="0" max="{{ $totalBayar }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                        placeholder="0.00" value="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                                        (Opsional)</label>
                                    <textarea name="keterangan" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                        placeholder="Tambahkan keterangan..."></textarea>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="hidePelunasanModal()"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Konfirmasi
                                </button>
                            </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($pinjaman->lunas === 'belum')
<script>
function showPelunasanModal() {
    document.getElementById('pelunasanModal').classList.remove('hidden');
}

function hidePelunasanModal() {
    document.getElementById('pelunasanModal').classList.add('hidden');
}
</script>
@endif
@endsection