@extends('layouts.nasabah')

@section('title', 'Panduan Gadai — Cara Mengajukan')

@section('content')
<div class="w-full pb-8">
    {{-- Breadcrumb --}}
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.gadai_baru.index') }}" class="hover:text-[#8b6f2f]">Gadai</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Pengajuan Gadai</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        {{-- Judul --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#674c1d] to-[#d4af37]">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Pengajuan Gadai</h1>
                <p class="text-gray-600 text-sm">Cara memilih barang, mengajukan gadai, dan memahami biaya</p>
            </div>
        </div>

        {{-- Apa itu Gadai --}}
        <div class="bg-gradient-to-br from-[#674c1d]/5 to-[#d4af37]/10 rounded-2xl border border-[#8b6f2f]/20 p-5">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#674c1d]/15 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-[#674c1d] mb-1">Apa itu Gadai?</h2>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Gadai adalah layanan pinjaman uang tunai dengan jaminan barang berharga (Emas, Elektronik, Kendaraan).
                        Anda menyerahkan barang ke koperasi sebagai agunan dan mendapatkan pinjaman sesuai nilai taksiran.
                        Setelah melunasi pinjaman beserta biayanya, barang dikembalikan kepada Anda.
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
                Cara Mengajukan Gadai Baru
            </h2>

            {{-- Flow chips --}}
            <div class="flex flex-wrap items-center gap-2 text-xs mb-6">
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-[#674c1d]/5 rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-[10px]">1</span>
                    Browse Barang
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-[#674c1d]/5 rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-[10px]">2</span>
                    Pilih Item
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-[#674c1d]/5 rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-[10px]">3</span>
                    Pilih Metode Bayar
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-[#674c1d]/5 rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-[10px]">4</span>
                    Konfirmasi PIN
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 rounded-lg border border-green-200 shadow-sm">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Gadai Aktif
                </span>
            </div>

            <ol class="space-y-5">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Buka menu <strong>Gadai</strong> di navigasi</p>
                        <p class="text-sm text-gray-600 mt-0.5">Masuk ke Dashboard Gadai. Anda akan melihat daftar barang yang bisa digadaikan, dikelompokkan per kategori: <strong>Emas, Elektronik, Kendaraan</strong>. Gunakan fitur pencarian untuk menemukan barang spesifik.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih barang yang ingin Anda gadaikan</p>
                        <p class="text-sm text-gray-600 mt-0.5">Klik barang untuk melihat detail: foto, deskripsi, dan <strong>nominal taksiran maksimum</strong> yang bisa Anda pinjam. Nominal deal final ditetapkan setelah admin menilai kondisi barang Anda secara langsung.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih metode pembayaran administrasi</p>
                        <p class="text-sm text-gray-600 mt-0.5">Sistem menampilkan rincian biaya yang harus dibayar (biaya jasa admin). Pilih metode:<br>
                        <span class="font-medium text-gray-700">• Tunai (Janji Temu)</span> — datang ke kantor, bayar langsung<br>
                        <span class="font-medium text-gray-700">• Transfer Bank</span> — upload bukti transfer ke rekening koperasi</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">4</span>
                    <div>
                        <p class="font-semibold text-gray-900">Konfirmasi dengan PIN & submit</p>
                        <p class="text-sm text-gray-600 mt-0.5">Setiap pengajuan memerlukan <strong>6 digit PIN</strong> keamanan sebagai otorisasi. Masukkan PIN dan kirim pengajuan. Pantau status di <strong>Status Pengajuan Gadai</strong>.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Komponen Biaya --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Komponen Biaya Gadai
            </h2>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-amber-50/60 border border-amber-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <span class="text-amber-700 font-black text-sm">P</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Pokok Pinjaman</p>
                        <p class="text-xs text-gray-600 mt-0.5">Nominal uang yang Anda terima. Ditetapkan admin berdasarkan penilaian kondisi barang (tidak melebihi taksiran maksimum).</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-[#674c1d]/5 border border-[#8b6f2f]/20">
                    <div class="w-8 h-8 rounded-lg bg-[#674c1d]/15 flex items-center justify-center shrink-0">
                        <span class="text-[#674c1d] font-black text-sm">J</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Biaya Jasa Admin</p>
                        <p class="text-xs text-gray-600 mt-0.5">Biaya administrasi dihitung sebagai persentase dari pokok pinjaman. Tarif berbeda per kategori (Emas, Elektronik, Kendaraan). Dibayar di awal saat pengajuan.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50/60 border border-blue-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                        <span class="text-blue-700 font-black text-sm">I</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Biaya Inap (Jika Ada)</p>
                        <p class="text-xs text-gray-600 mt-0.5">Biaya penyimpanan barang per hari/periode. Berlaku untuk kategori Kendaraan dan Elektronik. Bisa flat atau berdasarkan persentase taksiran.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-red-50/60 border border-red-100">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Denda Keterlambatan (Jika Terlambat)</p>
                        <p class="text-xs text-gray-600 mt-0.5">Denda berlaku jika gadai melewati tanggal jatuh tempo. Semakin lama terlambat, semakin besar denda. Bayar tepat waktu untuk menghindari denda!</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview iframe --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview Dashboard Gadai. Scroll di dalam frame untuk melihat seluruh halaman.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe src="{{ route('nasabah.gadai_baru.index') }}" title="Preview: Dashboard Gadai"
                    class="w-full border-0" style="height: 70vh; min-height: 420px;" loading="lazy"></iframe>
            </div>
            <a href="{{ route('nasabah.gadai_baru.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors">
                Buka Dashboard Gadai
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        {{-- Tips --}}
        <div class="rounded-2xl bg-[#674c1d]/5 border border-[#8b6f2f]/20 p-5">
            <h3 class="font-bold text-[#674c1d] mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                Tips Pengajuan Gadai
            </h3>
            <ul class="text-sm text-gray-700 space-y-1.5">
                <li>• Pastikan barang dalam kondisi <strong>baik dan berfungsi</strong> untuk mendapatkan nilai taksiran terbaik.</li>
                <li>• <strong>Nominal deal final</strong> ditentukan admin saat Anda datang ke kantor — bisa lebih rendah dari taksiran maksimum.</li>
                <li>• Pantau status pengajuan secara berkala di menu <strong>Status Pengajuan</strong>.</li>
                <li>• Setelah admin menyetujui, Anda akan mendapatkan <strong>uang tunai</strong> sesuai nominal deal.</li>
            </ul>
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                Dashboard Gadai <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#d4af37]/20 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#d4af37]/30 transition-colors">
                Status Pengajuan <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide.gadai-aktif') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-[#8b6f2f]/40 text-[#674c1d] rounded-xl text-sm font-medium hover:bg-[#674c1d]/5 transition-colors">
                Panduan: Gadai Aktif (Tebus & Perpanjang) <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                ← Kembali ke Guide
            </a>
        </div>
    </div>
</div>
@endsection
