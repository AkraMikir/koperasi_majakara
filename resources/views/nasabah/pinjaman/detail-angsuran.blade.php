@extends('layouts.nasabah')

@section('title', 'Detail Angsuran')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button & Struk -->
    <div class="mx-4 mt-4 mb-4 flex flex-wrap items-center gap-2">
        {{-- 
        <a href="{{ route('nasabah.pinjaman.struk-angsuran', $angsuran->id) }}?jenis={{ urlencode($jenis ?? 'bulanan') }}" target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d] text-white rounded-xl shadow hover:shadow-md transition-all hover:bg-[#8b6f2f]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download PDF Struk
        </a>
        --}}
        <a href="{{ route('nasabah.pinjaman.angsuran', ['jenis' => $jenis ?? 'bulanan']) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Angsuran</h1>
                        <p class="text-white/90 text-sm">Angsuran ke-{{ $angsuran->no_urut }}</p>
                    </div>
                    <span class="px-4 py-2 {{ $angsuran->status_bayar === 'lunas' ? 'bg-green-500' : ($isTelat ? 'bg-red-500' : 'bg-yellow-500') }} text-white rounded-full text-sm font-semibold">
                        {{ $angsuran->status_bayar === 'lunas' ? 'Lunas' : ($isTelat ? 'Telat' : 'Belum') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Angsuran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Angsuran</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jatuh Tempo</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $angsuran->tgl_jatuh_tempo->format('d M Y, H:i') }}</p>
                    @if($isTelat)
                    <p class="text-sm text-red-600 mt-1">Telat {{ $angsuran->tgl_jatuh_tempo->diffForHumans() }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Tagihan</p>
                    <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($angsuran->jumlah_tagihan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Terbayar</p>
                    <p class="font-semibold {{ $angsuran->jumlah_terbayar >= $angsuran->jumlah_tagihan ? 'text-green-600' : 'text-orange-600' }} text-lg">
                        Rp {{ number_format($angsuran->jumlah_terbayar ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Sisa Tagihan</p>
                    <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
                </div>
                @if(isset($denda) && $denda > 0)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Denda</p>
                    <p class="font-semibold text-red-600 text-lg">Rp {{ number_format($denda, 0, ',', '.') }}</p>
                    @php
                        $hariTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas' 
                            ? now()->diffInDays($angsuran->tgl_jatuh_tempo, false) 
                            : 0;
                    @endphp
                    @if($hariTelat > 0)
                    <p class="text-xs text-red-500 mt-1">Telat {{ $hariTelat }} hari</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Tagihan + Denda</p>
                    <p class="font-semibold text-[#8b6f2f] text-lg">Rp {{ number_format($totalTagihanPlusDenda, 0, ',', '.') }}</p>
                </div>
                @endif
                @if(isset($angsuran->tgl_bayar) && $angsuran->tgl_bayar)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Bayar</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $angsuran->tgl_bayar->format('d M Y, H:i') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status Pembayaran</p>
                    <span class="px-3 py-1 {{ $angsuran->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : ($isTelat ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} rounded-full text-sm font-semibold">
                        {{ $angsuran->status_bayar === 'lunas' ? 'Lunas' : ($isTelat ? 'Telat' : 'Belum') }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis Angsuran</p>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($jenis) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Pinjaman Terkait -->
    @if($angsuran->pinjaman)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Pinjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">ID Pinjaman</p>
                    <p class="font-semibold text-gray-900">#{{ str_pad($angsuran->pinjaman->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Pinjaman</p>
                    <p class="font-semibold text-gray-900">Rp {{ number_format($angsuran->pinjaman->jumlah_pinjam, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-semibold text-gray-900">{{ $angsuran->pinjaman->tgl_pinjam->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis</p>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($angsuran->pinjaman->jenis) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('nasabah.pinjaman.detail-pinjaman', $angsuran->pinjaman->id) }}" 
                    class="inline-flex items-center gap-2 text-[#8b6f2f] font-semibold hover:underline">
                    Lihat Detail Pinjaman
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Bukti Transfer (jika angsuran sudah dibayar) -->
    @if($angsuran->status_bayar === 'lunas' && isset($buktiTransferAngsuran) && $buktiTransferAngsuran->isNotEmpty())
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-4 font-display">Bukti Transfer</h2>
            <p class="text-sm text-gray-500 mb-4">Bukti pembayaran yang diberikan admin sebagai pegangan Anda. Klik untuk preview atau unduh.</p>
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
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden cursor-pointer" onclick="window.open('{{ $imageUrl }}', '_blank', 'width=800,height=600')">
                        <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-3 flex items-center justify-between gap-2">
                        <span class="text-xs text-gray-500 truncate flex-1">{{ $bukti->keterangan ?: $fileName }}</span>
                        <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#8b6f2f] text-white text-xs font-semibold rounded-lg hover:bg-[#674c1d] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Unduh
                        </a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mx-4 mb-6">
        <a href="{{ route('nasabah.pinjaman.angsuran', ['jenis' => $jenis]) }}" 
            class="inline-flex items-center gap-2 text-[#8b6f2f] font-semibold hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Angsuran
        </a>
    </div>
</div>
@endsection
