@extends('layouts.admin')

@section('title', 'Detail Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pinjaman</h1>
            <p class="text-gray-600 mt-1">ID Pinjaman: #{{ $pinjaman->id }}</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <a href="{{ route('admin.pinjaman.struk-pencairan', $pinjaman->id) }}" target="_blank" class="w-full sm:w-auto px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-medium inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download Struk PDF
            </a>
            <a href="{{ route('admin.pinjaman.struk-pencairan-b5', $pinjaman->id) }}" target="_blank" class="w-full sm:w-auto px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors text-sm font-medium inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download Kwitansi PDF
            </a>
            <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}"
                class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium inline-flex items-center justify-center gap-2">
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
                        <p class="font-semibold text-gray-900">{{ number_format($pinjaman->bunga, 2) }}% (flat {{ number_format($pinjaman->bunga_flat_hari ?? ($pinjaman->bunga / 30), 2) }}% per hari)</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($pinjaman->bunga_rp, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Denda</p>
                        <p class="font-semibold text-gray-900">{{ number_format($pinjaman->denda_persen, 2) }}%</p>
                    </div>
                    <div>
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
                            <tr class="border-b-2 border-[#674c1d]/20 bg-linear-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No. Urut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jumlah Tagihan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Terbayar</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-[#674c1d] uppercase w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($angsuran as $item)
                            @php
                                $sisaHari = $item->hitungSisaHari();
                            @endphp
                            <tr
                                class="border-b border-gray-100 hover:bg-linear-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                                <td class="px-4 py-3 text-sm font-medium">#{{ $item->no_urut }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div>{{ $item->tgl_jatuh_tempo->format('d M Y') }}</div>
                                    @if($item->status_bayar !== 'lunas')
                                        @if($sisaHari > 0)
                                            <span class="text-[10px] text-gray-500 font-semibold">(Sisa {{ $sisaHari }} Hari)</span>
                                        @elseif($sisaHari === 0)
                                            <span class="text-[10px] text-yellow-600 font-bold">(Jatuh Tempo Hari Ini)</span>
                                        @else
                                            <span class="text-[10px] text-red-600 font-bold">(Telat {{ abs($sisaHari) }} Hari)</span>
                                        @endif
                                    @endif
                                </td>
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
                                <td class="px-4 py-3 text-center">
                                    @if($item->status_bayar !== 'lunas')
                                        <button type="button"
                                            onclick="kirimPengingatWa('{{ $pinjaman->jenis }}', '{{ $item->id }}', this)"
                                            class="inline-flex items-center justify-center gap-1.5 p-1.5 sm:px-3 sm:py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all text-xs font-medium shadow-sm hover:scale-105"
                                            title="Kirim WA Pengingat">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.717-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.63 1.97 14.16 .947 11.536.947c-5.445 0-9.87 4.37-9.874 9.799-.001 1.77.476 3.498 1.38 5.048l-.995 3.637 3.73-.974zm12.515-5.32c-.3-.15-1.772-.875-2.047-.975-.275-.1-.475-.15-.675.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.267-.467-2.413-1.485-.892-.793-1.493-1.773-1.668-2.073-.175-.3-.018-.463.13-.61.134-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.493-.51-.675-.52-.172-.007-.368-.009-.565-.009-.196 0-.517.074-.787.374-.27.3-1.03 1.01-1.03 2.463 0 1.453 1.056 2.859 1.203 3.059.148.2 2.08 3.175 5.038 4.453.704.304 1.254.485 1.681.62.707.224 1.35.193 1.859.118.568-.084 1.772-.725 2.022-1.425.25-.7.25-1.299.175-1.425-.075-.125-.275-.2-.575-.35z"/>
                                            </svg>
                                            <span class="hidden sm:inline">Kirim WA</span>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
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

            <!-- Bukti Pelunasan -->
            @if($pinjaman->buktiPelunasan)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Bukti Pelunasan</h3>
                <div class="space-y-4">
                    @php
                        $filePath = $pinjaman->buktiPelunasan->file_path;
                        $imageUrl = asset('storage/' . $filePath);
                    @endphp
                    <div class="rounded-xl overflow-hidden border border-gray-200 cursor-pointer" onclick="window.open('{{ $imageUrl }}', '_blank')">
                        <img src="{{ $imageUrl }}" alt="Bukti Pelunasan" class="w-full h-auto hover:scale-105 transition-transform duration-200">
                    </div>
                    <a href="{{ $imageUrl }}" download class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Bukti
                    </a>
                </div>
            </div>
            @endif

            <!-- Foto Pencairan -->
            @if($fotoPencairan->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-1">Foto Pencairan</h3>
                <p class="text-xs text-gray-500 mb-4">Bukti transfer saat pencairan dana pinjaman</p>
                <div class="space-y-4">
                    @foreach($fotoPencairan as $foto)
                    @php $imgUrl = asset('storage/' . $foto->file_path); @endphp
                    <div>
                        <div class="rounded-xl overflow-hidden border border-gray-200 cursor-pointer" onclick="window.open('{{ $imgUrl }}', '_blank')">
                            <img src="{{ $imgUrl }}" alt="Foto Pencairan" class="w-full h-auto hover:scale-105 transition-transform duration-200">
                        </div>
                        @if($foto->keterangan)
                        <p class="text-xs text-gray-500 mt-2">{{ $foto->keterangan }}</p>
                        @endif
                        <a href="{{ $imgUrl }}" download class="mt-2 w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Foto Transaksi Lain-lain -->
            @if($fotoLainLain->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-1">Foto Transaksi Lain-lain</h3>
                <p class="text-xs text-gray-500 mb-4">Dokumen atau bukti transaksi tambahan terkait pinjaman ini</p>
                <div class="space-y-4">
                    @foreach($fotoLainLain as $foto)
                    @php $imgUrl = asset('storage/' . $foto->file_path); @endphp
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-mono">{{ $foto->owner_trans }}</span>
                        </div>
                        <div class="rounded-xl overflow-hidden border border-gray-200 cursor-pointer" onclick="window.open('{{ $imgUrl }}', '_blank')">
                            <img src="{{ $imgUrl }}" alt="Foto Transaksi" class="w-full h-auto hover:scale-105 transition-transform duration-200">
                        </div>
                        @if($foto->keterangan)
                        <p class="text-xs text-gray-500 mt-2">{{ $foto->keterangan }}</p>
                        @endif
                        <a href="{{ $imgUrl }}" download class="mt-2 w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            <!-- Pelunasan Dipercepat — hanya Admin Utama -->
            @canPelunasanDipercepat
            @if($pinjaman->lunas === 'belum')
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Pelunasan Dipercepat</h3>
                <button onclick="showPelunasanModal()"
                    class="w-full px-4 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                    Pelunasan
                </button>
            </div>

            <!-- Modal Pelunasan Dipercepat -->
            <div id="pelunasanModal"
                class="hidden fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pelunasan Dipercepat</h3>
                    <form id="pelunasanForm" method="POST" action="{{ route('admin.pinjaman.pelunasan-dipercepat', $pinjaman->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
                        $totalTerbayar = $angsuran->sum('jumlah_terbayar');
                        $sisaTagihanPokok = $totalTagihan - $totalTerbayar;

                        // Hitung total denda
                        $totalDenda = 0;
                        foreach ($angsuran as $a) {
                            if ($a->status_bayar !== 'lunas') {
                                $totalDenda += $a->hitungDenda();
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
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pelunasan <span class="text-red-500">*</span></label>
                                    <input type="file" name="bukti_foto" accept="image/*" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 10MB.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Potongan (Opsional,
                                        Rp)</label>
                                    <input type="text" name="potongan" id="potongan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                                        placeholder="0" value="0" oninput="formatCurrency(this)">
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
            @endcanPelunasanDipercepat
        </div>
    </div>
</div>

@canPelunasanDipercepat
@if($pinjaman->lunas === 'belum')
<script>
function showPelunasanModal() {
    document.getElementById('pelunasanModal').classList.remove('hidden');
}

function hidePelunasanModal() {
    document.getElementById('pelunasanModal').classList.add('hidden');
}

function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value) value = parseInt(value).toLocaleString('id-ID');
    input.value = value;
}

document.getElementById('pelunasanForm').addEventListener('submit', function(e) {
    if (!confirm('Apakah Anda yakin ingin melakukan pelunasan dipercepat?')) {
        e.preventDefault();
        return false;
    }
    const input = document.getElementById('potongan');
    if (input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
});
</script>
@endif
@endcanPelunasanDipercepat
@endsection

@push('scripts')
<script>
function kirimPengingatWa(jenis, tempoId, btn) {
    if (!confirm('Buka WhatsApp untuk mengirim pesan pengingat jatuh tempo?')) return;
    
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '...';
    
    fetch(`/admin/pinjaman/angsuran/${jenis}/${tempoId}/buat-pengingat`)
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => { throw new Error(data.message || 'Gagal memproses request'); });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                let phone = data.phone;
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('+')) {
                    phone = phone.substring(1);
                }
                
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(data.message)}`;
                window.open(url, '_blank');
            } else {
                alert(data.message || 'Terjadi kesalahan saat membuat pesan.');
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Terjadi kesalahan sistem saat menghubungi server.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
}
</script>
@endpush