@extends('layouts.admin')

@section('title', 'Detail Angsuran')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Angsuran</h1>
            <p class="text-gray-600 mt-1">ID Angsuran: #{{ $angsuran->id }} - No. Urut: #{{ $angsuran->no_urut }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.angsuran', ['jenis' => $jenis]) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pinjaman -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Pinjaman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">ID Pinjaman</p>
                        <p class="font-semibold text-gray-900">#{{ $angsuran->pinjaman_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Pinjam</p>
                        <p class="font-semibold text-[#674c1d]">Rp {{ number_format($angsuran->pinjaman->jumlah_pinjam ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis</p>
                        <span class="inline-block mt-2 px-4 py-2 {{ $angsuran->pinjaman->jenis === 'bulanan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($angsuran->pinjaman->jenis ?? 'N/A') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status Pinjaman</p>
                        <span class="inline-block mt-2 px-4 py-2 {{ $angsuran->pinjaman->lunas === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($angsuran->pinjaman->lunas ?? 'belum') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Angsuran -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Angsuran</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">No. Urut</p>
                        <p class="font-semibold text-gray-900 text-xl">#{{ $angsuran->no_urut }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jatuh Tempo</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->tgl_jatuh_tempo->format('d M Y, H:i') }}</p>
                        @if($angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas')
                            <p class="text-sm text-red-600 mt-1">⚠ Telat {{ $angsuran->tgl_jatuh_tempo->diffForHumans() }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Tagihan</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($angsuran->jumlah_tagihan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Terbayar</p>
                        <p class="font-semibold text-green-600 text-xl">Rp {{ number_format($angsuran->jumlah_terbayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sisa Tagihan</p>
                        <p class="font-semibold text-[#8b6f2f] text-xl">Rp {{ number_format($angsuran->jumlah_tagihan - $angsuran->jumlah_terbayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusConfig = [
                                'belum' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Belum Lunas'],
                                'lunas' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Lunas'],
                                'telat' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Telat'],
                            ];
                            $status = $statusConfig[$angsuran->status_bayar] ?? $statusConfig['belum'];
                        @endphp
                        <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($angsuran->status_bayar !== 'lunas')
            <!-- Form Pembayaran -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Input Pembayaran</h3>
                <form method="POST" action="{{ route('admin.pinjaman.update-pembayaran-angsuran', $angsuran->id) }}">
                    @csrf
                    <input type="hidden" name="jenis" value="{{ $jenis }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" step="0.01" min="0" max="{{ $angsuran->jumlah_tagihan - $angsuran->jumlah_terbayar }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                placeholder="0.00"
                                value="{{ $angsuran->jumlah_tagihan - $angsuran->jumlah_terbayar }}">
                            <p class="text-xs text-gray-500 mt-1">Maksimal: Rp {{ number_format($angsuran->jumlah_tagihan - $angsuran->jumlah_terbayar, 0, ',', '.') }}</p>
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            ✓ Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold text-gray-900">Angsuran Sudah Lunas</p>
                </div>
            </div>
            @endif

            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Ringkasan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Progress Pembayaran</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] h-3 rounded-full" style="width: {{ ($angsuran->jumlah_terbayar / $angsuran->jumlah_tagihan) * 100 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format(($angsuran->jumlah_terbayar / $angsuran->jumlah_tagihan) * 100, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
