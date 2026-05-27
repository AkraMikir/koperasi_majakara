@extends('layouts.admin')

@section('title', 'Detail Gadai Fisik')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-10">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-2xl flex items-center justify-center font-mono font-bold text-xl shadow-lg shadow-gray-900/20">
                {{ substr($gadai->slot_kode, 0, 2) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display flex items-center gap-3">
                    Detail Gadai
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-lg font-mono">{{ $gadai->slot_kode }}</span>
                </h1>
                <p class="text-gray-500 mt-1">Informasi lengkap transaksi, barang, dan log riwayat</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status:</span>
                @if($gadai->status == 'active')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black rounded-lg">AKTIF</span>
                @elseif($gadai->status == 'grace_period')
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-black rounded-lg animate-pulse">MASA TENGGANG</span>
                @elseif($gadai->status == 'lunas')
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-black rounded-lg">LUNAS / SELESAI</span>
                @elseif($gadai->status == 'expired_final')
                    <span class="px-3 py-1 bg-gray-800 text-white text-xs font-black rounded-lg">HANGUS (FINAL)</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-black rounded-lg">{{ strtoupper($gadai->status) }}</span>
                @endif
            </div>
            <a href="{{ route('admin.gadai_baru.index') }}" 
                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 border border-gray-200 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
            <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h3 class="text-sm font-bold text-red-800">Perhatian!</h3>
            <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM 1: Info Nasabah & Barang --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Nasabah</h3>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xl">
                            {{ substr($gadai->nasabah->user->nama ?? 'N/A', 0, 2) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">{{ $gadai->nasabah->user->nama ?? 'Tanpa Nama' }}</p>
                            <p class="text-sm text-gray-500 flex items-center gap-1.5 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $gadai->nasabah->user->nomor_hp ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-500 font-medium mb-1">Disimpan di Cabang:</p>
                        <p class="font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $gadai->lokasi->nama_lokasi }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Data Barang Fisik</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-500 font-medium mb-1">Kategori Barang</p>
                                <span class="inline-flex px-2 py-1 rounded bg-amber-100 text-amber-800 text-xs font-bold">{{ $gadai->kategori->nama_kategori }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium mb-1">Status Barang</p>
                                @if($gadai->status == 'active')
                                    <span class="inline-flex px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold">Aktif Tersimpan</span>
                                @elseif($gadai->status == 'grace_period')
                                    <span class="inline-flex px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-bold">Tenggang (Aman)</span>
                                @elseif($gadai->status == 'lunas')
                                    <span class="inline-flex px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold">Sudah Diambil</span>
                                @elseif($gadai->status == 'auctioned')
                                    <span class="inline-flex px-2 py-1 rounded bg-amber-100 text-amber-700 text-xs font-bold">Dilelang</span>
                                @elseif($gadai->status == 'expired_final')
                                    <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-bold">Menunggu Lelang</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Nama Item</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $gadai->item->head_1 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM 2: Info Keuangan & Siklus --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Rincian Keuangan</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <span class="text-gray-600 font-medium">Pinjaman Pokok</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-medium">Biaya Jasa Admin</span>
                                <span class="text-[10px] text-blue-600 font-bold">Tarif Kategori: {{ number_format($gadai->kategori->rate_jasa, 2) }}%</span>
                            </div>
                            <span class="font-bold text-gray-900">Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-medium">Denda Keterlambatan</span>
                                <span class="text-[10px] text-red-600 font-bold">Tarif Kategori: {{ number_format($gadai->kategori->rate_denda, 2) }}%</span>
                            </div>
                            <span class="font-bold {{ $gadai->denda_aktif > 0 ? 'text-red-600' : 'text-gray-400' }}">Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-medium">Biaya Inap</span>
                                @if($gadai->item->nominal_inap > 0)
                                    <span class="text-[10px] text-amber-600 font-bold">Tarif Flat (Kendaraan): Rp {{ number_format($gadai->item->nominal_inap, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[10px] text-amber-600 font-bold">Tarif Kategori: {{ number_format($gadai->kategori->rate_inap_persen, 2) }}% dari Taksiran</span>
                                @endif
                            </div>
                            <span class="font-bold {{ $gadai->biaya_inap > 0 ? 'text-amber-600' : 'text-gray-400' }}">Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</span>
                        </div>
                        @if(($gadai->extra_pinjaman_nominal ?? 0) > 0)
                        <div class="flex justify-between items-center py-2.5 border-b border-dashed border-gray-100 bg-red-50/50 px-2 rounded-lg">
                            <div class="flex flex-col">
                                <span class="text-red-700 font-bold">Biaya Ekstra (Administrasi/Lainnya)</span>
                                <span class="text-[10px] text-red-500 font-medium">Alasan: {{ $gadai->extra_pinjaman_reason ?? '-' }}</span>
                            </div>
                            <span class="font-bold text-red-700">Rp {{ number_format($gadai->extra_pinjaman_nominal, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    @php
                        $totalTebus = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0);
                        $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                    @endphp

                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">Total Tebus Sekarang</p>
                        <p class="text-3xl font-black text-emerald-700">Rp {{ number_format($totalTebus, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#674c1d]"></div>
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Siklus & Waktu</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 font-medium">Tanggal Mulai</span>
                            <span class="font-semibold text-gray-800">{{ $gadai->tgl_mulai->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 font-medium">Jatuh Tempo</span>
                            <span class="font-bold text-blue-600">{{ $gadai->tgl_jatuh_tempo->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 font-medium">Batas Akhir Tenggang</span>
                            <span class="font-bold text-red-600">{{ $gadai->tgl_tenggang->format('d M Y') }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2">Riwayat Perpanjangan (Maks {{ $gadai->kategori->max_extend_default }}x)</p>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1 relative overflow-hidden">
                                @php $pct = ($gadai->jumlah_perpanjangan / $gadai->kategori->max_extend_default) * 100; @endphp
                                <div class="bg-[#674c1d] h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs font-bold text-right text-gray-700">{{ $gadai->jumlah_perpanjangan }} / {{ $gadai->kategori->max_extend_default }} Kali</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM 3: Aksi & Media --}}
        <div class="space-y-6">
            {{-- Foto Media --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Media Fisik Barang</h3>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($gadai->files as $file)
                        <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank" class="group relative block aspect-square rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                            <img src="{{ asset('storage/' . $file->path_file) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-2 py-8 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-xl bg-gray-50">
                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-medium text-gray-400">Belum ada foto.</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- RIWAYAT LOG ... (rest of the file) --}}


    {{-- ===== RIWAYAT LOG ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Log Transaksi & Riwayat Sistem
            </h3>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Jenis Aksi</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nominal Mutasi</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        {{-- Log Pembayaran Finansial (Prioritas) --}}
                        @foreach($gadai->paymentLogs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors bg-emerald-50/20">
                            <td class="px-6 py-3 whitespace-nowrap text-gray-600 font-medium">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if(in_array($log->jenis_pembayaran, ['tebus', 'lunas']))
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded">Pelunasan Gadai</span>
                                @else
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded">Pembayaran Perpanjangan</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap font-bold text-emerald-600">Rp {{ number_format($log->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-gray-600 font-semibold">{{ strtoupper($log->metode) }}</td>
                        </tr>
                        @endforeach
                        
                        {{-- Log Status History --}}
                        @foreach($gadai->history as $hist)
                        @if(in_array($hist->aksi, ['extend', 'lunas'])) @continue @endif
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 whitespace-nowrap text-gray-500">{{ $hist->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if($hist->aksi === 'create')
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded border border-blue-200">Gadai Didaftarkan</span>
                                @elseif($hist->aksi === 'extend')
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded border border-amber-200">Masa Gadai Diperpanjang</span>
                                @elseif($hist->aksi === 'lunas')
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded border border-green-200">Gadai Dilunasi</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded border border-gray-200">{{ strtoupper($hist->aksi) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-400 font-medium">-</td>
                            <td class="px-6 py-3 text-gray-600">{{ $hist->catatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Other logic if needed
});
</script>
@endsection
