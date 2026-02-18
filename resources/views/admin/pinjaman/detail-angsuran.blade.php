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
                        <p class="font-semibold text-gray-900">{{ $angsuran->pinjaman->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->pinjaman->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->pinjaman->nasabah->user->nomor_hp ?? 'N/A' }}</p>
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
                    @php
                        $hariTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas' 
                            ? now()->diffInDays($angsuran->tgl_jatuh_tempo, false) 
                            : 0;
                        $totalTagihanPlusDenda = $angsuran->jumlah_tagihan + ($dendaDisplay ?? 0);
                    @endphp
                    @if(($dendaDisplay ?? 0) > 0 || ($hariTelat > 0 && $angsuran->status_bayar !== 'lunas'))
                    <div>
                        <p class="text-sm text-gray-600">Denda</p>
                        <p class="font-semibold text-red-600 text-xl">Rp {{ number_format($dendaDisplay ?? 0, 0, ',', '.') }}</p>
                        @if($hariTelat > 0)
                        <p class="text-xs text-red-500 mt-1">Telat {{ $hariTelat }} hari</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Tagihan + Denda</p>
                        <p class="font-semibold text-[#674c1d] text-xl">Rp {{ number_format($totalTagihanPlusDenda, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    @if($angsuran->tgl_bayar)
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Bayar</p>
                        <p class="font-semibold text-gray-900">{{ $angsuran->tgl_bayar->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    <div>
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

            <!-- Bukti Transfer (jika angsuran sudah dibayar) -->
            @if($angsuran->status_bayar === 'lunas' && isset($buktiTransferAngsuran) && $buktiTransferAngsuran->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mt-6">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Transfer</h2>
                <p class="text-sm text-gray-500 mb-4">Bukti pembayaran untuk angsuran ini (dari pengajuan pembayaran yang sudah terlaksana).</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($buktiTransferAngsuran as $bukti)
                    @php
                        $filePath = $bukti->file_path ?? null;
                        $fileExists = $filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath);
                        $imageUrl = $fileExists ? asset('storage/' . $filePath) : null;
                        $fileName = $filePath ? basename($filePath) : 'bukti-transfer';
                    @endphp
                    @if($imageUrl)
                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 hover:shadow-md transition-all group">
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden cursor-pointer" onclick="window.open('{{ $imageUrl }}', '_blank')">
                            <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </div>
                        <div class="p-3 flex items-center justify-between gap-2">
                            <span class="text-xs text-gray-500 truncate flex-1">{{ $bukti->keterangan ?: $fileName }}</span>
                            <a href="{{ $imageUrl }}" target="_blank" rel="noopener" class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#674c1d] text-white text-xs font-semibold rounded-lg hover:bg-[#5a4018] transition-colors">
                                Buka / Unduh
                            </a>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar: Ringkasan progres pembayaran -->
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Ringkasan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Progress Pembayaran</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                @php
                                    $progressPercent = $angsuran->jumlah_tagihan > 0 ? ($angsuran->jumlah_terbayar / $angsuran->jumlah_tagihan) * 100 : 0;
                                @endphp
                                <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] h-3 rounded-full" style="width: {{ number_format($progressPercent, 2) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($progressPercent, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
