@extends('layouts.nasabah')

@section('title', 'Panduan Setoran (Nabung) - Koperasi Majakara')

@section('content')
<div class="w-full pb-8">
    {{-- Breadcrumb --}}
    <div class="mx-4 mt-4 mb-4">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('nasabah.guide') }}" class="hover:text-[#8b6f2f] transition-colors">Guide</a>
            <span>/</span>
            <a href="{{ route('nasabah.tabungan.index') }}" class="hover:text-[#8b6f2f] transition-colors">Tabungan</a>
            <span>/</span>
            <span class="text-[#674c1d] font-medium">Setoran (Nabung)</span>
        </nav>
    </div>

    <div class="mx-4 space-y-6">
        {{-- Judul --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] shadow-md">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Panduan Setoran (Nabung)</h1>
                <p class="text-gray-600 text-sm">Cara melakukan setoran dana, langkah-langkah di aplikasi, dan pemantauan verifikasi saldo</p>
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
                    <h2 class="font-bold text-amber-900 mb-1">Mengapa Menabung di Koperasi Majakara?</h2>
                    <p class="text-sm text-amber-900/85 leading-relaxed">
                        Menabung di Koperasi Majakara sangatlah aman, mudah, dan transparan. Anda dapat melakukan setoran melalui <strong>transfer bank</strong> (dengan mengunggah bukti transfer) atau secara <strong>tunai</strong> melalui janji temu langsung dengan staf koperasi. Saldo tabungan Anda akan langsung terupdate setelah pengajuan disetujui oleh admin.
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
                Langkah-Langkah Menabung
            </h2>

            {{-- Stepper Visual --}}
            <div class="flex flex-wrap items-center gap-2 text-xs mb-6 pb-4 border-b border-gray-100">
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">1</span>
                    Menu Tabungan
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">2</span>
                    Nabung Sekarang
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">3</span>
                    Isi Form & Bukti
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-[10px]">4</span>
                    Verifikasi Admin
                </span>
                <span class="text-gray-400">→</span>
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 rounded-lg border border-green-200 shadow-sm">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Saldo Bertambah
                </span>
            </div>

            <ol class="space-y-5">
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm shadow-md">1</span>
                    <div>
                        <p class="font-semibold text-gray-900">Masuk ke Menu <strong>Tabungan</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Tekan ikon <strong>Tabungan</strong> di bottom navbar (ikon koin). Jika Anda menggunakan perangkat mobile, buka terlebih dahulu <strong>Menu Utama</strong> (tombol burger) lalu pilih tab <strong>Tabungan</strong>.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm shadow-md">2</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih Aksi <strong>Nabung Sekarang</strong></p>
                        <p class="text-sm text-gray-600 mt-0.5">Pada dashboard tabungan, Anda akan melihat saldo saat ini dan riwayat transaksi. Klik tombol <strong class="text-[#674c1d]">Nabung Sekarang</strong> untuk memulai proses penyetoran.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm shadow-md">3</span>
                    <div>
                        <p class="font-semibold text-gray-900">Pilih Metode & Lengkapi Data</p>
                        <p class="text-sm text-gray-600 mt-0.5">Pilih metode <strong>Transfer Bank</strong> atau <strong>Setor Tunai</strong>. Masukkan nominal yang ingin disetor, dan jika memilih Transfer, pastikan untuk mengunggah foto bukti transfer bank yang valid.</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#d4af37] text-white flex items-center justify-center font-bold text-sm shadow-md">4</span>
                    <div>
                        <p class="font-semibold text-gray-900">Kirim Form & Tunggu Persetujuan</p>
                        <p class="text-sm text-gray-600 mt-0.5">Klik kirim untuk mengumpulkan pengajuan. Tim Admin Koperasi akan segera melakukan verifikasi bukti transfer Anda.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Contoh tampilan (iframe) --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-2">Simulasi & Preview Halaman</h2>
            <p class="text-sm text-gray-600 mb-4">Berikut adalah tampilan interaktif form Nabung Sekarang. Anda dapat mencoba melihat elemen form di dalam preview ini:</p>
            <div class="rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50">
                <iframe
                    src="{{ route('nasabah.tabungan.nabung-sekarang') }}"
                    title="Contoh: Halaman Nabung Sekarang"
                    class="w-full border-0"
                    style="height: 70vh; min-height: 420px;"
                    loading="lazy"
                ></iframe>
            </div>
            <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}"
               class="mt-4 inline-flex items-center gap-2 px-5 py-3 bg-[#674c1d] text-white rounded-xl font-semibold hover:bg-[#5a4018] transition-colors shadow-md">
                Buka Form Nabung Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Status Verifikasi & Riwayat --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Memahami Status Pengajuan Setoran
            </h2>
            <p class="text-sm text-gray-600 mb-5">
                Setiap pengajuan setoran dana Anda melewati tahap moderasi untuk mencegah kesalahan input. Kenali 3 status verifikasi berikut:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Proses -->
                <div class="bg-gradient-to-br from-amber-50/70 to-white rounded-xl p-5 border border-amber-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm">⏳</span>
                        <span class="text-sm font-bold text-amber-800">Menunggu Verifikasi</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pengajuan setoran Anda telah terekam di sistem dan sedang dalam antrean verifikasi bukti transfer oleh tim Admin Koperasi.
                    </p>
                </div>
                <!-- Disetujui -->
                <div class="bg-gradient-to-br from-green-50/70 to-white rounded-xl p-5 border border-green-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm">✓</span>
                        <span class="text-sm font-bold text-green-800">Disetujui Admin</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Bukti transfer Anda diverifikasi valid. Dana setoran langsung ditambahkan ke saldo tabungan utama Anda secara otomatis.
                    </p>
                </div>
                <!-- Ditolak -->
                <div class="bg-gradient-to-br from-red-50/70 to-white rounded-xl p-5 border border-red-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold text-sm">✗</span>
                        <span class="text-sm font-bold text-red-800">Ditolak Admin</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Bukti transfer tidak valid, buram, nominal salah, atau dana belum masuk rekening. Silakan ajukan ulang dengan bukti yang tepat.
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-sm text-gray-500">Gunakan tombol di samping untuk langsung melacak status pengajuan atau melihat riwayat saldo Anda.</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" 
                       class="px-4 py-2 text-sm font-semibold text-[#674c1d] bg-[#674c1d]/10 rounded-xl hover:bg-[#674c1d]/15 transition-colors">
                        Pantau Status Pengajuan
                    </a>
                    <a href="{{ route('nasabah.tabungan.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-[#674c1d] border border-[#674c1d]/20 rounded-xl hover:bg-[#674c1d]/5 transition-colors">
                        Riwayat Transaksi
                    </a>
                </div>
            </div>
        </div>

        {{-- Ringkasan singkat --}}
        <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-5">
            <h3 class="font-bold text-amber-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Catatan Penting untuk Setoran
            </h3>
            <ul class="text-sm text-amber-900/90 space-y-2">
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 select-none font-bold">•</span>
                    <span>Admin berhak menyesuaikan nominal setoran yang disetujui agar sesuai dengan data mutasi rekening bank koperasi yang sebenarnya.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 select-none font-bold">•</span>
                    <span>Pastikan bukti transfer mencantumkan tanggal, jam, nominal transfer, nomor rekening tujuan koperasi, serta nomor referensi yang jelas.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 select-none font-bold">•</span>
                    <span>Verifikasi bukti setoran oleh Admin dilakukan selama jam operasional kantor koperasi (maksimal 1x24 jam kerja).</span>
                </li>
            </ul>
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ route('nasabah.tabungan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                Dashboard Tabungan <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#d4af37]/20 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#d4af37]/30 transition-colors">
                Nabung Sekarang <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-[#8b6f2f]/40 text-[#674c1d] rounded-xl text-sm font-medium hover:bg-[#674c1d]/5 transition-colors">
                Status Pengajuan Setor <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('nasabah.guide') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                ← Kembali ke Guide
            </a>
        </div>
    </div>
</div>
@endsection

