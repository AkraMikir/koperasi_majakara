@extends('layouts.nasabah')

@section('title', 'Detail Deposito')

@push('styles')
<style>
    .progress-ring { transition: stroke-dashoffset 1s ease; }
    .gradient-ring { background: conic-gradient(#d4af37 var(--pct), #e5e7eb 0); }
    .info-row { border-bottom: 1px solid #f3f4f6; }
    .info-row:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<div class="w-full pb-6">

    {{-- ===== HEADER ===== --}}
    <div class="bg-gradient-to-br from-[#4a3514] to-[#8b6f2f] px-4 pt-6 pb-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('nasabah.deposito.index') }}" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <p class="text-white/70 text-xs font-mono">{{ $deposito->nomor_deposito }}</p>
                    <h1 class="text-white font-bold text-lg">Detail Deposito</h1>
                </div>
                <div class="ml-auto">
                    @php
                        $statusMap = [
                            'aktif'     => ['Aktif', 'bg-green-400/30 text-green-200 border border-green-400/30'],
                            'dicairkan' => ['Dicairkan', 'bg-blue-400/30 text-blue-200 border border-blue-400/30'],
                            'ditutup'   => ['Ditutup', 'bg-gray-400/30 text-gray-200 border border-gray-400/30'],
                            'gagal'     => ['Gagal', 'bg-red-400/30 text-red-200 border border-red-400/30'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$deposito->status] ?? ['Aktif', 'bg-green-400/30 text-green-200'];
                    @endphp
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
            </div>

            {{-- Main balance card --}}
            <div class="text-center">
                <p class="text-white/70 text-xs mb-1">Nominal Awal</p>
                <h2 class="text-white text-4xl font-black font-display">
                    Rp {{ number_format($deposito->nominal_awal, 0, ',', '.') }}
                </h2>
                <div class="flex items-center justify-center gap-3 mt-2">
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full">{{ $deposito->tenor?->tenor_bulan ?? '-' }} Bulan</span>
                    <span class="bg-[#d4af37]/30 text-[#f0d060] text-xs px-3 py-1 rounded-full font-bold">{{ number_format($deposito->bunga * 100, 2) }}% p.a.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PROGRESS CARD ===== --}}
    @php
        $tglMulai = $deposito->tgl_mulai;
        $tglJatuhTempo = $deposito->tgl_jatuh_tempo;
        $totalHari = $deposito->tenor->tenor_hari ?? ($tglMulai && $tglJatuhTempo ? $tglMulai->diffInDays($tglJatuhTempo) : 0);
        $lewatHari = $tglMulai ? min($totalHari, $tglMulai->diffInDays(now())) : 0;
        $persen = $totalHari > 0 ? round(($lewatHari / $totalHari) * 100) : 0;
        $hariSisa = $tglJatuhTempo ? max(0, (int) now()->diffInDays($tglJatuhTempo, false)) : 0;

        // Estimasi bunga
        $bungaKotor = $deposito->nominal_awal * $deposito->bunga * ($totalHari / 365);
        $pajak = $bungaKotor * 0.2;
        $bungaBersih = $bungaKotor - $pajak;
        $totalCair = $deposito->nominal_awal + $bungaBersih;
    @endphp

    <div class="mx-4 -mt-10 relative z-10 mb-4">
        <div class="bg-white rounded-2xl shadow-md p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#674c1d] text-sm">Progress Tenor</h3>
                <span class="text-xs font-bold {{ $hariSisa <= 7 ? 'text-red-500' : 'text-gray-500' }}">
                    {{ $hariSisa }} hari lagi
                </span>
            </div>
            <div class="relative w-full bg-gray-200 rounded-full h-3 mb-3 overflow-hidden">
                <div class="h-3 rounded-full bg-gradient-to-r from-[#674c1d] via-[#8b6f2f] to-[#d4af37] transition-all duration-700"
                     style="width: {{ $persen }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-400">
                <span>{{ $tglMulai?->format('d M Y') ?? '-' }}</span>
                <span class="font-bold text-[#674c1d]">{{ $persen }}% selesai</span>
                <span>{{ $tglJatuhTempo?->format('d M Y') ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- ===== RINCIAN FINANSIAL ===== --}}
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-[#faf9f0] to-white">
                <h3 class="font-bold text-[#674c1d] text-sm">Rincian Finansial</h3>
            </div>
            <div class="p-4 space-y-0">
                @foreach([
                    ['label' => 'Nominal Awal', 'value' => 'Rp ' . number_format($deposito->nominal_awal, 0, ',', '.')],
                    ['label' => 'Suku Bunga', 'value' => number_format($deposito->bunga * 100, 2) . '% p.a.', 'highlight' => true],
                    ['label' => 'Estimasi Bunga Kotor', 'value' => 'Rp ' . number_format($bungaKotor, 0, ',', '.')],
                    ['label' => 'Pajak Bunga (20%)', 'value' => '- Rp ' . number_format($pajak, 0, ',', '.'), 'red' => true],
                    ['label' => 'Estimasi Bunga Bersih', 'value' => 'Rp ' . number_format($bungaBersih, 0, ',', '.'), 'green' => true],
                ] as $row)
                <div class="flex justify-between items-center info-row py-3">
                    <span class="text-sm text-gray-500">{{ $row['label'] }}</span>
                    <span class="text-sm font-bold {{ isset($row['highlight']) ? 'text-[#674c1d]' : (isset($row['red']) ? 'text-red-500' : (isset($row['green']) ? 'text-green-600' : 'text-gray-800')) }}">
                        {{ $row['value'] }}
                    </span>
                </div>
                @endforeach
                {{-- Total --}}
                <div class="flex justify-between items-center pt-3 mt-1 border-t-2 border-[#674c1d]/10">
                    <span class="text-sm font-bold text-gray-700">Estimasi Total Pencairan</span>
                    <span class="text-lg font-black text-[#674c1d]">Rp {{ number_format($totalCair, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DETAIL DEPOSITO ===== --}}
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-[#faf9f0] to-white">
                <h3 class="font-bold text-[#674c1d] text-sm">Informasi Deposito</h3>
            </div>
            <div class="p-4">
                @foreach([
                    ['label' => 'Nomor Deposito', 'value' => $deposito->nomor_deposito, 'mono' => true],
                    ['label' => 'Tenor', 'value' => ($deposito->tenor?->tenor_bulan ?? '-') . ' Bulan'],
                    ['label' => 'Tanggal Mulai', 'value' => $deposito->tgl_mulai?->format('d M Y') ?? '-'],
                    ['label' => 'Jatuh Tempo', 'value' => $deposito->tgl_jatuh_tempo?->format('d M Y') ?? '-'],
                    ['label' => 'Metode Pencairan', 'value' => ucwords(str_replace('_', ' ', $deposito->metode_pencairan))],
                    ['label' => 'Status', 'value' => ucfirst($deposito->status)],
                ] as $row)
                <div class="flex justify-between items-center info-row py-3">
                    <span class="text-sm text-gray-500">{{ $row['label'] }}</span>
                    <span class="text-sm font-bold {{ isset($row['mono']) ? 'font-mono text-gray-700' : 'text-gray-800' }}">{{ $row['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== RIWAYAT BUNGA HARIAN ===== --}}
    @if($deposito->bungaHarian->isNotEmpty())
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-[#674c1d] text-sm">Akumulasi Bunga Harian</h3>
                    <span class="text-xs text-gray-400">{{ $deposito->bungaHarian->count() }} hari</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left font-bold text-[#674c1d]">Tanggal</th>
                            <th class="px-4 py-3 text-right font-bold text-[#674c1d]">Bunga Harian</th>
                            <th class="px-4 py-3 text-right font-bold text-[#674c1d]">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposito->bungaHarian->take(10) as $bh)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($bh->tanggal)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right text-green-600 font-semibold">+Rp {{ number_format($bh->bunga_harian, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-800">Rp {{ number_format($bh->saldo_akhir, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($deposito->bungaHarian->count() > 10)
            <div class="p-3 text-center text-xs text-gray-400 border-t border-gray-100">
                Menampilkan 10 dari {{ $deposito->bungaHarian->count() }} entri
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== RIWAYAT TRANSAKSI ===== --}}
    @if($deposito->transDeposito->isNotEmpty())
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-bold text-[#674c1d] text-sm">Riwayat Transaksi</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($deposito->transDeposito as $trans)
                @php
                    $jenisMap = ['setor_awal' => ['Setoran Awal', 'bg-blue-100 text-blue-700'], 'bunga' => ['Bunga', 'bg-green-100 text-green-700'], 'pencairan' => ['Pencairan', 'bg-amber-100 text-amber-700']];
                    [$jenisLabel, $jenisClass] = $jenisMap[$trans->jenis] ?? [$trans->jenis, 'bg-gray-100 text-gray-700'];
                    $isPositive = in_array($trans->jenis, ['setor_awal', 'bunga']);
                @endphp
                <div class="p-4 {{ $loop->even ? 'bg-gray-50/50' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 {{ $isPositive ? 'bg-green-50' : 'bg-amber-50' }} rounded-xl flex items-center justify-center">
                                <svg class="w-4 h-4 {{ $isPositive ? 'text-green-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($isPositive)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $jenisClass }} font-semibold">{{ $jenisLabel }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($trans->tgl_transaksi)->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-sm {{ $isPositive ? 'text-green-600' : 'text-amber-600' }}">
                            {{ $isPositive ? '+' : '-' }}Rp {{ number_format($trans->nominal, 0, ',', '.') }}
                        </p>
                    </div>
                    @if($trans->keterangan)
                    <p class="text-xs text-gray-400 mt-1 ml-12">{{ $trans->keterangan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ===== DOKUMEN LAMPIRAN ===== --}}
    @if(($deposito->pengajuan && $deposito->pengajuan->foto_bukti_tf) || ($deposito->pencairan && $deposito->pencairan->foto_bukti_tf))
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-[#faf9f0] to-white">
                <h3 class="font-bold text-[#674c1d] text-sm">Dokumen Lampiran</h3>
            </div>
            <div class="p-4 grid grid-cols-2 gap-4">
                @if($deposito->pengajuan && $deposito->pengajuan->foto_bukti_tf)
                <div>
                    <p class="text-xs text-gray-500 mb-2">Bukti Setor Awal</p>
                    <a href="{{ Storage::url($deposito->pengajuan->foto_bukti_tf) }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-colors relative group">
                        <img src="{{ Storage::url($deposito->pengajuan->foto_bukti_tf) }}" alt="Bukti Setor" class="w-full h-32 object-cover transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">Lihat Penuh</span>
                        </div>
                    </a>
                </div>
                @endif
                
                @if($deposito->pencairan && $deposito->pencairan->foto_bukti_tf)
                <div>
                    <p class="text-xs text-gray-500 mb-2">Bukti Pencairan</p>
                    <a href="{{ Storage::url($deposito->pencairan->foto_bukti_tf) }}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 hover:border-[#674c1d] transition-colors relative group">
                        <img src="{{ Storage::url($deposito->pencairan->foto_bukti_tf) }}" alt="Bukti Pencairan" class="w-full h-32 object-cover transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">Lihat Penuh</span>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ===== SESSION ALERTS ===== --}}
    @if(session('success'))
    <div class="mx-4 mb-4">
        <div class="bg-green-50 border border-green-200 rounded-2xl px-4 py-3 text-sm text-green-800 font-medium">
            ✓ {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 mb-4">
        <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-800 font-medium">
            ✗ {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- ===== PENCAIRAN SECTION ===== --}}
    @if($deposito->status === 'aktif' && $deposito->tgl_jatuh_tempo)
    @php
        $sudahJatuhTempo = now()->gte($deposito->tgl_jatuh_tempo);
        $requestPending  = $deposito->pencairan?->status === 'pending';
        $requestSelesai  = $deposito->pencairan?->status === 'selesai';
    @endphp

    @if($requestSelesai)
    {{-- Sudah dicairkan --}}
    <div class="mx-4 mb-4">
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
            <p class="font-bold text-blue-800 text-sm">✓ Pencairan Diproses</p>
            <p class="text-xs text-blue-700 mt-1">
                Dana deposito Anda telah dicairkan senilai
                <strong>Rp {{ number_format($deposito->pencairan->nominal_akhir, 0, ',', '.') }}</strong>.
                Silakan cek rekening atau saldo tabungan Anda.
            </p>
        </div>
    </div>

    @elseif($requestPending)
    {{-- Request sudah diajukan, menunggu admin --}}
    <div class="mx-4 mb-4">
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-amber-800 text-sm">Permintaan {{ $deposito->pencairan->is_cancel ? 'Pembatalan' : 'Pencairan' }} Sedang Diproses</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        @if(!$deposito->pencairan->is_cancel)
                        Metode: <strong>{{ $deposito->pencairan->jenis_pencairan === 'rek_nasabah' ? 'Transfer ke Rekening' : 'Saldo Tabungan' }}</strong>
                        —
                        @endif
                        Tim kami sedang memproses permintaan Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @elseif($sudahJatuhTempo)
    {{-- Jatuh tempo & belum ada request --}}
    <div class="mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-[#d4af37]/40 overflow-hidden">
            <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] px-4 py-3">
                <p class="text-white font-bold text-sm">Deposito Jatuh Tempo – Ajukan Pencairan</p>
                <p class="text-white/70 text-xs mt-0.5">Pilih metode pencairan dana Anda</p>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('nasabah.deposito.ajukan-cairkan', $deposito->id) }}">
                    @csrf
                    <div class="space-y-3 mb-4">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] transition has-[:checked]:border-[#674c1d] has-[:checked]:bg-[#faf9f0]">
                            <input type="radio" name="jenis_pencairan" value="rek_nasabah" class="accent-[#674c1d]" required>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Transfer ke Rekening</p>
                                <p class="text-xs text-gray-500">Dana akan ditransfer ke rekening bank Anda</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] transition has-[:checked]:border-[#674c1d] has-[:checked]:bg-[#faf9f0]">
                            <input type="radio" name="jenis_pencairan" value="saldo_tabungan" class="accent-[#674c1d]">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Masuk ke Saldo Tabungan</p>
                                <p class="text-xs text-gray-500">Dana langsung ditambahkan ke saldo tabungan Anda</p>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-gray-400 mb-3">
                        Estimasi pencairan: <strong class="text-[#674c1d]">Rp {{ number_format($totalCair, 0, ',', '.') }}</strong>
                        (sudah dipotong pajak 20%)
                    </p>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white py-3 rounded-xl text-sm font-bold hover:opacity-90 transition shadow">
                        Ajukan Pencairan
                    </button>
                </form>
            </div>
        </div>
    </div>

    @else
    {{-- Belum jatuh tempo - tampilkan peringatan saja --}}
    <div class="mx-4 mb-4 space-y-4">
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <div class="text-2xl">⏰</div>
                <div>
                    <p class="font-bold text-amber-800 text-sm">Deposito Akan Jatuh Tempo</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Deposito Anda akan jatuh tempo dalam <strong>{{ $hariSisa }} hari</strong> lagi
                        ({{ $tglJatuhTempo?->format('d M Y') }}). Tim kami akan menghubungi Anda.
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Section Pembatalan Deposito --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
            <div class="bg-red-50 border-b border-red-100 px-4 py-3">
                <p class="text-red-700 font-bold text-sm">Batalkan Deposito</p>
            </div>
            <div class="p-4">
                <p class="text-xs text-gray-500 mb-4">
                    Anda dapat membatalkan deposito sebelum jatuh tempo. Namun, <strong class="text-red-600">Anda tidak akan menerima bunga</strong> dan hanya nominal pokok sebesar <strong>Rp {{ number_format($deposito->nominal_awal, 0, ',', '.') }}</strong> yang akan dikembalikan.
                </p>
                <button type="button" onclick="openCancelModal()"
                    class="w-full bg-red-50 text-red-600 border border-red-200 py-3 rounded-xl text-sm font-bold hover:bg-red-100 transition shadow-sm">
                    Ajukan Pembatalan Deposito
                </button>
            </div>
        </div>
    </div>
    @endif

    @endif

    {{-- MODAL CANCEL DEPOSITO --}}
    <div id="cancelModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancelModalContent">
            <div class="p-6">
                <!-- Icon Warning Premium -->
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Pembatalan</h3>
                
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-5 text-left text-xs text-red-800 leading-relaxed font-semibold">
                    <p class="font-bold">Peringatan Penting:</p>
                    <p class="mt-1 text-red-700 font-normal">
                        Jika Anda membatalkan deposito sebelum jatuh tempo, Anda <strong class="text-red-900 font-bold">TIDAK AKAN</strong> menerima bunga. 
                        Hanya nominal pokok yang akan dikembalikan:
                    </p>
                    <div class="mt-2 text-center py-2 bg-white rounded-lg border border-red-200">
                        <span class="text-lg font-black text-red-700">Rp {{ number_format($deposito->nominal_awal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('nasabah.deposito.ajukan-cancel', $deposito->id) }}" class="space-y-4" id="cancelForm">
                    @csrf
                    <div class="text-left">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Masukkan PIN 6 Digit</label>
                        <input type="password" name="pin" id="cancelPinInput" maxlength="6" placeholder="••••••" required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#8b6f2f] focus:ring-4 focus:ring-[#8b6f2f]/10 outline-none transition-all font-mono text-center text-2xl tracking-[0.5em]">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeCancelModal()" class="flex-1 py-3.5 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all text-sm">
                            Kembali
                        </button>
                        <button type="submit" id="cancelSubmitBtn" disabled class="flex-1 py-3.5 bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-200 opacity-50 cursor-not-allowed transition-all hover:bg-red-700 text-sm">
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const cancelModal = document.getElementById('cancelModal');
    const cancelModalContent = document.getElementById('cancelModalContent');
    const cancelPinInput = document.getElementById('cancelPinInput');
    const cancelSubmitBtn = document.getElementById('cancelSubmitBtn');
    const cancelForm = document.getElementById('cancelForm');

    function openCancelModal() {
        cancelModal.classList.remove('hidden');
        cancelModal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            cancelModalContent.classList.remove('scale-95', 'opacity-0');
            cancelModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        cancelPinInput.value = '';
        updateCancelSubmitButton();
        
        setTimeout(() => cancelPinInput.focus(), 350);
    }

    function closeCancelModal() {
        cancelModalContent.classList.remove('scale-100', 'opacity-100');
        cancelModalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            cancelModal.classList.add('hidden');
            cancelModal.classList.remove('flex');
        }, 300);
    }

    function updateCancelSubmitButton() {
        const isPinValid = cancelPinInput.value.length === 6;
        
        if (isPinValid) {
            cancelSubmitBtn.disabled = false;
            cancelSubmitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            cancelSubmitBtn.disabled = true;
            cancelSubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    cancelPinInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        updateCancelSubmitButton();
    });

    cancelForm.addEventListener('submit', function(e) {
        cancelSubmitBtn.disabled = true;
        cancelSubmitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
    });

    // Close on outside click
    cancelModal.addEventListener('click', (e) => {
        if (e.target === cancelModal) closeCancelModal();
    });
</script>
@endpush
@endsection

