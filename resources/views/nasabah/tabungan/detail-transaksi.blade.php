@extends('layouts.nasabah')

@section('title', 'Detail Transaksi')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Transaksi</h1>
                        <p class="text-white/90 text-sm">ID: #{{ $transaksi->id }}</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.index') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="mx-4 mb-6 space-y-6">
        <!-- Info Transaksi -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Transaksi</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Transaksi</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->tgl_transaksi->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis</p>
                        <span class="inline-block px-4 py-2 {{ ($transaksi->jenis ?? '') === 'setoran' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($transaksi->jenis ?? '-') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nominal</p>
                        <p class="text-3xl font-bold {{ ($transaksi->jenis ?? '') === 'setoran' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($transaksi->jenis ?? '') === 'setoran' ? '+' : '-' }}Rp {{ number_format(abs((float) $transaksi->nominal), 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Via</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->via ? ucfirst($transaksi->via) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">ID Transaksi</p>
                        <p class="font-semibold text-gray-900 font-mono">{{ $transaksi->id_transaksi ?? 'TRX-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                @if($transaksi->keterangan)
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-900">{{ $transaksi->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Bukti Foto (jika setoran) -->
        @if(($transaksi->jenis ?? '') === 'setoran' && $transaksi->pengajuanSetor && $transaksi->pengajuanSetor->buktiFoto->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Bukti Transfer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($transaksi->pengajuanSetor->buktiFoto as $bukti)
                <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" 
                    onclick="showPhotoPreview('{{ asset('storage/' . $bukti->file_path) }}', 'Bukti Transfer #{{ $loop->iteration }}')">
                    <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Transfer" class="w-full h-48 object-cover">
                    <div class="p-4 bg-gray-50">
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                            Klik untuk memperbesar
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Info Pengajuan -->
        @if($transaksi->pengajuanSetor || $transaksi->pengajuanTarik)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Pengajuan</h2>
            <div class="space-y-3">
                @if($transaksi->pengajuanSetor)
                <div>
                    <p class="text-sm text-gray-600">ID Pengajuan Setor</p>
                    <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanSetor->id }}</p>
                    <p class="text-xs text-gray-500 mt-1">Status: 
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Menunggu', 'color' => 'text-yellow-600'],
                                '2' => ['label' => 'Disetujui', 'color' => 'text-green-600'],
                                '3' => ['label' => 'Ditolak', 'color' => 'text-red-600'],
                            ];
                            $status = $statusConfig[$transaksi->pengajuanSetor->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="{{ $status['color'] }} font-semibold">{{ $status['label'] }}</span>
                    </p>
                </div>
                @endif
                @if($transaksi->pengajuanTarik)
                <div>
                    <p class="text-sm text-gray-600">ID Pengajuan Penarikan</p>
                    <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanTarik->id }}</p>
                    <p class="text-xs text-gray-500 mt-1">Status: 
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Menunggu', 'color' => 'text-yellow-600'],
                                '2' => ['label' => 'Disetujui', 'color' => 'text-green-600'],
                                '3' => ['label' => 'Ditolak', 'color' => 'text-red-600'],
                            ];
                            $status = $statusConfig[$transaksi->pengajuanTarik->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="{{ $status['color'] }} font-semibold">{{ $status['label'] }}</span>
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
