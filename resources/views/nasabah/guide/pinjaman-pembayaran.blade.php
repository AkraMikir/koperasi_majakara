@extends('layouts.nasabah')

@section('title', 'Panduan Pembayaran Angsuran')

@section('content')
<div class="w-full pb-8">
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.pinjaman.index') }}" class="hover:text-[#8b6f2f]">Pinjaman</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Pembayaran Angsuran</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Pembayaran Angsuran</h1>
                <p class="text-gray-600 text-sm">Cara akses dan contoh tampilan halaman pembayaran</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Cara akses dari aplikasi
            </h2>
            <ol class="space-y-4">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Tekan icon <strong>Pinjaman</strong> di bottom navbar</p>
                        <p class="text-sm text-gray-600 mt-0.5">Di mobile: buka Menu lalu pilih Pinjaman.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Dari <strong>Dashboard Pinjaman</strong>, buka <strong>Angsuran</strong> atau <strong>Pembayaran</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Angsuran: daftar angsuran per pinjaman. Pembayaran: halaman untuk bayar angsuran (upload bukti transfer atau buat janji temu bayar tunai).</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Di halaman <strong>Pembayaran</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Pilih pinjaman & angsuran yang akan dibayar, pilih metode (Transfer atau Janji Temu), upload bukti atau jadwalkan janji. Pantau di <strong>Status Pembayaran</strong>.</p>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-amber-800 font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview halaman Pembayaran. Scroll di dalam frame untuk melihat form.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('nasabah.pinjaman.pembayaran') }}"
                    title="Contoh: Halaman Pembayaran Angsuran"
                    class="w-full border-0"
                    style="height: 70vh; min-height: 420px;"
                    loading="lazy"
                ></iframe>
            </div>
            <a href="{{ route('nasabah.pinjaman.pembayaran') }}" target="_blank" rel="noopener"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-colors">
                Buka halaman Pembayaran
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-5">
            <h3 class="font-bold text-amber-900 mb-2">Tips</h3>
            <ul class="text-sm text-amber-900/90 space-y-1">
                <li>• Bayar tepat waktu untuk menghindari denda keterlambatan.</li>
                <li>• Cek <a href="{{ route('nasabah.pinjaman.angsuran') }}" class="underline font-medium">Angsuran</a> untuk jadwal jatuh tempo.</li>
                <li>• Pantau <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="underline font-medium">Status Pembayaran</a> setelah submit.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
