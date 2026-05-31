@extends('layouts.nasabah')

@section('title', 'Panduan Penarikan Tabungan')

@section('content')
<div class="w-full pb-8">
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f]">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.tabungan.index') }}" class="hover:text-[#8b6f2f]">Tabungan</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Penarikan</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-[#674c1d]/15 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Penarikan (Tarik)</h1>
                <p class="text-gray-600 text-sm">Cara akses dan contoh tampilan halaman penarikan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Cara akses dari aplikasi
            </h2>
            <ol class="space-y-4">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Tekan icon <strong>Tabungan</strong> di bottom navbar</p>
                        <p class="text-sm text-gray-600 mt-0.5">Di mobile: buka Menu (burger) lalu pilih Tabungan.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Di <strong>Dashboard Tabungan</strong>, klik <strong>Penarikan</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Anda masuk ke halaman Penarikan: pilih Transfer (ke rekening) atau Tunai (Janji Temu), isi nominal dan data rekening (jika transfer).</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold text-sm">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Setelah admin menyetujui</p>
                        <p class="text-sm text-gray-600 mt-0.5">Untuk transfer: biaya transfer akan dipotong dari saldo. Rincian biaya bisa dilihat di <strong>Detail Pengajuan Tarik</strong> setelah disetujui.</p>
                    </div>
                </li>
            </ol>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Contoh tampilan halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Preview halaman Penarikan. Scroll di dalam frame untuk melihat form lengkap.</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('nasabah.tabungan.penarikan') }}"
                    title="Contoh: Halaman Penarikan"
                    class="w-full border-0"
                    style="height: 70vh; min-height: 420px;"
                    loading="lazy"
                ></iframe>
            </div>
            <a href="{{ route('nasabah.tabungan.penarikan') }}" target="_blank" rel="noopener"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors">
                Buka halaman Penarikan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Detail Biaya Transfer & Riwayat --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Biaya Transaksi & Rekening Penarikan
            </h2>
            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Untuk penarikan dana dengan metode <strong>Transfer Rekening</strong>, sistem menerapkan ketentuan biaya transfer sebagai berikut:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50/60 rounded-xl p-4 border border-blue-100">
                        <h4 class="font-semibold text-blue-900 text-sm mb-1">Dihitung Dinamis</h4>
                        <p class="text-xs text-gray-600">
                            Biaya transfer ditentukan secara otomatis oleh sistem berdasarkan kecocokan <strong>Bank Pengirim Koperasi</strong> dengan <strong>Bank Tujuan (Nasabah)</strong> yang diatur oleh Admin.
                        </p>
                    </div>
                    <div class="bg-amber-50/60 rounded-xl p-4 border border-amber-100">
                        <h4 class="font-semibold text-amber-900 text-sm mb-1">Debet dari Saldo Tabungan</h4>
                        <p class="text-xs text-gray-600">
                            Biaya transfer ini <strong>memotong saldo tabungan Anda secara langsung</strong>. Saldo terpotong = Nominal Penarikan + Biaya Transfer. Dana yang masuk ke rekening Anda adalah utuh nominal penarikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-6 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-sm text-gray-500">Pantau status pencairan dana Anda atau lihat riwayat transaksi tabungan Anda.</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" 
                       class="px-4 py-2 text-sm font-semibold text-[#674c1d] bg-[#674c1d]/10 rounded-xl hover:bg-[#674c1d]/15 transition-colors">
                        Pantau Status Penarikan
                    </a>
                    <a href="{{ route('nasabah.tabungan.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-[#674c1d] border border-[#674c1d]/20 rounded-xl hover:bg-[#674c1d]/5 transition-colors">
                        Riwayat Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
