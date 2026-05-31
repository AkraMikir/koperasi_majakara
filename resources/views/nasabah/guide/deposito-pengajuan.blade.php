@extends('layouts.nasabah')

@section('title', 'Panduan Deposito')

@section('content')
<div class="w-full pb-8">
    {{-- Breadcrumb --}}
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.deposito.index') }}" class="hover:text-[#8b6f2f]">Deposito</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Pengajuan & Pemantauan</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        {{-- Judul --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#d4af37] to-[#8b6f2f]">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 19l10-10 4 4 6-6m0 0v6m0-6h-6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Deposito</h1>
                <p class="text-gray-600 text-sm">Cara membuka deposito, memilih paket, dan memantau hasilnya</p>
            </div>
        </div>

        {{-- Penjelasan singkat --}}
        <div class="bg-amber-50 rounded-2xl border border-amber-200/80 p-5">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-amber-900 mb-1">Apa itu Deposito?</h2>
                    <p class="text-sm text-amber-900/85 leading-relaxed">
                        Deposito adalah simpanan berjangka yang menghasilkan <strong>imbal hasil (bunga)</strong> lebih besar dari tabungan biasa.
                        Dana Anda "dikunci" selama tenor yang dipilih (1–12 bulan). Saat jatuh tempo,
                        pokok + bunga bersih (setelah dipotong <strong>pajak 20%</strong>) otomatis cair ke tabungan Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- Alur Pengajuan --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Cara Membuka Deposito
            </h2>
            <div class="flex flex-wrap items-center gap-2 text-xs mb-6">
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">1</span>
                    Buka Menu Deposito
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">2</span>
                    Pilih Paket
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">3</span>
                    Isi Nominal & PIN
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">4</span>
                    Admin Setujui
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 rounded-lg border border-green-200 shadow-sm">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Deposito Aktif
                </span>
            </div>

            <ol class="space-y-5">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Buka menu <strong>Deposito</strong> di navigasi</p>
                        <p class="text-sm text-gray-600 mt-0.5">Tekan ikon Deposito di navbar. Anda akan masuk ke Dashboard Deposito yang menampilkan deposito aktif (jika ada) dan daftar paket tersedia.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Klik <strong>Buka Deposito</strong> dan pilih paket</p>
                        <p class="text-sm text-gray-600 mt-0.5">Pilih paket deposito sesuai kebutuhan (1, 3, 6, atau 12 bulan). Setiap paket menampilkan suku bunga per tahun.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Isi nominal & konfirmasi dengan PIN</p>
                        <p class="text-sm text-gray-600 mt-0.5">Masukkan nominal (min. <span class="font-semibold text-amber-700">Rp 1.000.000</span>). Estimasi bunga bersih ditampilkan otomatis. Konfirmasi dengan <strong>6 digit PIN</strong> keamanan Anda.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">4</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pantau status pengajuan</p>
                        <p class="text-sm text-gray-600 mt-0.5">Status: <span class="text-amber-700 font-semibold">Menunggu</span> → <span class="text-green-600 font-semibold">Disetujui</span> / <span class="text-red-600 font-semibold">Ditolak</span>. Jika disetujui, deposito langsung aktif.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Paket Aktif dari DB --}}
        @if($paketDeposito->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Paket Deposito Tersedia
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($paketDeposito as $paket)
                <div class="rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-4 text-center shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs text-amber-600/80 font-bold uppercase tracking-widest mb-1">{{ $paket->kategori->nama_kategori ?? 'Reguler' }}</p>
                    <p class="text-2xl font-black text-[#674c1d]">{{ $paket->tenor_bulan }}<span class="text-sm font-semibold ml-0.5">bln</span></p>
                    <div class="mt-2 inline-block bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ number_format($paket->suku_bunga, 2) }}% / thn
                    </div>
                    @if($paket->nama_paket)
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">{{ $paket->nama_paket }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-3">* Suku bunga dapat berubah sesuai kebijakan koperasi.</p>
        </div>
        @endif

        {{-- Cara Pantau --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Memantau Deposito Aktif
            </h2>
            <ul class="space-y-4 text-sm text-gray-700">
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span><strong>Progress Bar Tenor</strong> — Menampilkan persentase waktu tenor yang sudah berjalan. Semakin penuh = semakin dekat jatuh tempo.</span>
                </li>
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <span><strong>Estimasi Bunga Bersih</strong> — Sudah dikurangi pajak 20%. Ini yang akan Anda terima saat jatuh tempo.</span>
                </li>
                <li class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span><strong>Jatuh Tempo</strong> — Sistem otomatis mencairkan pokok + bunga bersih ke tabungan Anda. Tidak perlu aksi apa pun dari Anda.</span>
                </li>
            </ul>
        </div>

        {{-- Preview iframe --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview Dashboard Deposito. Scroll di dalam frame untuk melihat seluruh halaman.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe src="{{ route('nasabah.deposito.index') }}" title="Preview: Dashboard Deposito"
                    class="w-full border-0" style="height: 70vh; min-height: 420px;" loading="lazy"></iframe>
            </div>
            <a href="{{ route('nasabah.deposito.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors">
                Buka Dashboard Deposito
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        {{-- Hal Penting --}}
        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-5">
            <h3 class="font-bold text-amber-900 mb-2">Hal penting tentang Deposito</h3>
            <ul class="text-sm text-amber-900/90 space-y-1.5">
                <li>• <strong>Minimal nominal</strong> deposito adalah <strong>Rp 1.000.000</strong>.</li>
                <li>• <strong>Pajak bunga 20%</strong> dipotong otomatis — estimasi "bunga bersih" sudah memperhitungkan ini.</li>
                <li>• Dana <strong>tidak bisa ditarik</strong> sebelum jatuh tempo kecuali mengajukan pembatalan (bunga tidak diberikan).</li>
                <li>• Pengajuan membutuhkan <strong>persetujuan admin</strong>. Pastikan saldo tabungan mencukupi.</li>
            </ul>
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('nasabah.deposito.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                Dashboard Deposito <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.deposito.pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#d4af37]/20 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#d4af37]/30 transition-colors">
                Buka Deposito <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.deposito.riwayat') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-[#8b6f2f]/40 text-[#674c1d] rounded-xl text-sm font-medium hover:bg-[#674c1d]/5 transition-colors">
                Riwayat <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                ← Kembali ke Guide
            </a>
        </div>
    </div>
</div>
@endsection
