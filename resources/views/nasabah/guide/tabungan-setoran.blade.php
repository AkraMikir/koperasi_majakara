@extends('layouts.nasabah')

@section('title', 'Panduan Setoran (Nabung)')

@section('content')
<div class="w-full pb-8">
    {{-- Breadcrumb --}}
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.tabungan.index') }}" class="hover:text-[#8b6f2f]">Tabungan</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Setoran (Nabung)</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        {{-- Judul --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-[#674c1d]/15 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Setoran (Nabung)</h1>
                <p class="text-gray-600 text-sm">Cara akses dari aplikasi dan contoh tampilan halaman</p>
            </div>
        </div>

        {{-- Cara akses dari navbar --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Cara akses dari aplikasi
            </h2>
            <ol class="space-y-4">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Tekan icon <strong>Tabungan</strong> di bottom navbar</p>
                        <p class="text-sm text-gray-600 mt-0.5">Icon berbentuk koin/uang. Di mobile: buka <strong>Menu</strong> (ikon burger) lalu pilih <strong>Tabungan</strong>.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Anda masuk ke <strong>Dashboard Tabungan</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Halaman ini menampilkan saldo, ringkasan transaksi, dan tombol aksi (Nabung Sekarang, Penarikan, dll.).</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Klik <strong>Nabung Sekarang</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Anda akan masuk ke halaman <strong>Nabung Sekarang</strong>: pilih setoran via Transfer atau Tunai (Janji Temu), isi nominal, upload bukti (untuk transfer), lalu submit.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Contoh tampilan (iframe) --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview halaman Nabung Sekarang. Anda dapat scroll di dalam frame untuk melihat seluruh form.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('nasabah.tabungan.nabung-sekarang') }}"
                    title="Contoh: Halaman Nabung Sekarang"
                    class="w-full border-0"
                    style="height: 70vh; min-height: 420px;"
                    loading="lazy"
                ></iframe>
            </div>
            <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" target="_blank" rel="noopener"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors">
                Buka halaman Nabung Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Ringkasan singkat --}}
        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-5">
            <h3 class="font-bold text-amber-900 mb-2">Perlu diingat</h3>
            <ul class="text-sm text-amber-900/90 space-y-1">
                <li>• Admin dapat mengubah nominal setoran jika berbeda dengan bukti transfer (mengikuti bukti TF).</li>
                <li>• Admin dapat menolak pengajuan jika bukti transfer tidak valid.</li>
                <li>• Pantau status di <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="underline font-medium">Status Pengajuan Setor</a>.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
