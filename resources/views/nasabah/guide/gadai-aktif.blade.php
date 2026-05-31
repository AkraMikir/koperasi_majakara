@extends('layouts.nasabah')

@section('title', 'Panduan Gadai Aktif — Tebus & Perpanjang')

@section('content')
<div class="w-full pb-8">
    {{-- Breadcrumb --}}
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.gadai_baru.index') }}" class="hover:text-[#8b6f2f]">Gadai</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Gadai Aktif</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        {{-- Judul --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#674c1d] to-[#d4af37]">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Gadai Aktif</h1>
                <p class="text-gray-600 text-sm">Cara menebus, memperpanjang, dan memantau status gadai Anda</p>
            </div>
        </div>

        {{-- Status Gadai --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Mengenal Status Gadai
            </h2>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-yellow-50 border border-yellow-200">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-yellow-800 text-sm">Menunggu</p>
                        <p class="text-xs text-yellow-700 mt-0.5">Pengajuan sudah dikirim, admin sedang meninjau. Pantau secara berkala.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50 border border-blue-200">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-blue-800 text-sm">Aktif</p>
                        <p class="text-xs text-blue-700 mt-0.5">Gadai berjalan normal. Barang disimpan koperasi, Anda sudah menerima dana pinjaman.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-orange-50 border border-orange-300">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-orange-800 text-sm">Tenggang ⚠️</p>
                        <p class="text-xs text-orange-700 mt-0.5">Gadai sudah <strong>melewati jatuh tempo</strong> namun masih dalam masa toleransi. <strong>Denda terus berjalan</strong>. Segera tebus atau perpanjang!</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-green-50 border border-green-200">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-green-800 text-sm">Lunas / Ditebus</p>
                        <p class="text-xs text-green-700 mt-0.5">Pinjaman sudah dilunasi. Barang telah/siap dikembalikan ke Anda.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-red-50 border border-red-200">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-500 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800 text-sm">Lelang</p>
                        <p class="text-xs text-red-700 mt-0.5">Barang dilelang karena tidak ditebus dalam batas waktu yang ditentukan. Status ini tidak dapat diubah.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cara Tebus --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
                Cara Menebus Gadai (Melunasi)
            </h2>
            <ol class="space-y-5">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Buka <strong>Gadai Aktif</strong> saya</p>
                        <p class="text-sm text-gray-600 mt-0.5">Di Dashboard Gadai, buka tab "Aktif" atau "Gadai Saya". Pilih gadai yang ingin ditebus untuk melihat detail tagihan.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Cek total yang harus dibayar</p>
                        <p class="text-sm text-gray-600 mt-0.5">Sistem menampilkan rincian: <strong>Pokok + Biaya Inap + Denda</strong> (jika ada). Total inilah yang harus dibayar untuk menebus barang Anda.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih metode & lakukan pembayaran</p>
                        <p class="text-sm text-gray-600 mt-0.5">
                            <strong>Tunai:</strong> Datang ke kantor koperasi, bayar langsung ke kasir.<br>
                            <strong>Transfer:</strong> Transfer ke rekening koperasi dan upload bukti transfer di aplikasi.
                        </p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">4</span>
                    <div>
                        <p class="font-semibold text-gray-900">Konfirmasi PIN & ajukan tebus</p>
                        <p class="text-sm text-gray-600 mt-0.5">Masukkan <strong>6 digit PIN</strong> untuk mengkonfirmasi pengajuan tebus. Admin akan memverifikasi dan menyiapkan barang Anda.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Cara Perpanjang --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Cara Memperpanjang Gadai
            </h2>
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-4 mb-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-blue-800"><strong>Kapan Perpanjang?</strong> Saat Anda belum bisa menebus barang, tapi ingin menghindari denda/lelang. Perpanjangan memberikan tambahan waktu dengan biaya perpanjangan.</p>
                </div>
            </div>
            <ol class="space-y-4">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Buka detail gadai aktif & klik <strong>Perpanjang</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Tombol perpanjang tersedia di halaman detail gadai aktif Anda.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih durasi perpanjangan & cek biaya</p>
                        <p class="text-sm text-gray-600 mt-0.5">Sistem menampilkan pilihan durasi dan biaya perpanjangan. Biaya ini harus dibayar di awal sebelum perpanjangan disetujui.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Bayar biaya perpanjangan & konfirmasi PIN</p>
                        <p class="text-sm text-gray-600 mt-0.5">Pilih metode pembayaran, lakukan pembayaran, dan konfirmasi dengan PIN. Jatuh tempo akan diperbarui setelah admin menyetujui.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Preview iframe --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview halaman Gadai Aktif Anda.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe src="{{ route('nasabah.gadai_baru.index') }}" title="Preview: Gadai Aktif"
                    class="w-full border-0" style="height: 70vh; min-height: 420px;" loading="lazy"></iframe>
            </div>
            <a href="{{ route('nasabah.gadai_baru.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors">
                Buka Dashboard Gadai
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        {{-- Peringatan Tenggang --}}
        <div class="rounded-2xl bg-orange-50 border-2 border-orange-300 p-5">
            <h3 class="font-bold text-orange-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Perhatian: Status Tenggang
            </h3>
            <ul class="text-sm text-orange-900 space-y-2">
                <li>• Status <strong>Tenggang</strong> berarti gadai sudah melewati jatuh tempo tetapi <em>belum</em> dilelang.</li>
                <li>• <strong>Denda terus berjalan</strong> setiap hari selama status Tenggang. Semakin lama, semakin besar tagihan Anda.</li>
                <li>• Segera <strong>Tebus</strong> atau <strong>Perpanjang</strong> untuk menghentikan akumulasi denda.</li>
                <li>• Jika tidak ada tindakan hingga batas waktu habis, barang akan berpindah ke status <strong>Lelang</strong>.</li>
            </ul>
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                Gadai Aktif Saya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide.gadai-pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#d4af37]/20 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#d4af37]/30 transition-colors">
                Panduan Pengajuan Gadai <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                ← Kembali ke Guide
            </a>
        </div>
    </div>
</div>
@endsection
