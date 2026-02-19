@extends('layouts.nasabah')

@section('title', 'Panduan Layanan')

@section('content')
<div class="w-full pb-8">
    {{-- Hero --}}
    <div class="mx-4 mt-4 mb-8">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-xl p-8 border border-[#d4af37]/30 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            <div class="relative z-10 flex items-center gap-5">
                <div class="w-20 h-20 bg-white/25 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg shrink-0">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white font-display mb-1">Panduan Layanan</h1>
                    <p class="text-white/90 text-sm md:text-base">Kenali fitur Tabungan, Pinjaman, dan cara mengajukan layanan dengan mudah.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-4 space-y-8">
        {{-- ========== TABUNGAN ========== --}}
        <section class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#674c1d]/10 to-[#8b6f2f]/10 px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#674c1d]/15 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 font-display">Tabungan</h2>
                        <p class="text-gray-600 text-sm">Setor dan tarik dana dengan transfer atau tunai (janji temu)</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                {{-- Setoran (Nabung) - Expandable --}}
                <details class="group rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 hover:bg-[#674c1d]/5 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#674c1d]/15 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Setoran (Nabung)</h3>
                                <p class="text-sm text-gray-600">Transfer atau tunai via janji temu</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="px-5 pb-5 pt-0 space-y-4">
                        {{-- Visualisasi alur setoran --}}
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                                <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold">1</span>
                                Isi nominal & upload bukti
                            </span>
                            <span class="text-gray-400">→</span>
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                                <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold">2</span>
                                Admin verifikasi bukti TF
                            </span>
                            <span class="text-gray-400">→</span>
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-green-200 shadow-sm">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Saldo bertambah
                            </span>
                        </div>
                        <ul class="space-y-2 text-gray-700 text-sm">
                            <li class="flex items-start gap-2">
                                <span class="text-[#8b6f2f] mt-0.5 shrink-0">•</span>
                                <span><strong>Transfer:</strong> Isi nominal, upload bukti transfer, lalu cek <strong>Status Pengajuan Setor</strong>. Admin akan memverifikasi bukti dan menyetujui; saldo Anda bertambah.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#8b6f2f] mt-0.5 shrink-0">•</span>
                                <span><strong>Tunai (Janji Temu):</strong> Pilih lokasi, tanggal & waktu temu. Datang ke lokasi, admin akan memproses dan saldo bertambah. Pantau di <strong>Status Janji Temu</strong>.</span>
                            </li>
                        </ul>
                        {{-- Perlu Anda tahu: admin edit nominal & tolak --}}
                        <div class="rounded-xl bg-amber-50 border border-amber-200/80 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-1">Perlu Anda tahu (setoran transfer)</h4>
                                    <ul class="text-sm text-amber-900/90 space-y-1">
                                        <li>• <strong>Nominal bisa diubah admin.</strong> Jika nominal yang Anda isi berbeda dengan bukti transfer, admin akan mengikuti <strong>nominal di bukti transfer</strong>. Jadi jangan kaget jika saldo yang masuk sesuai bukti TF, bukan nominal yang Anda tulis.</li>
                                        <li>• <strong>Admin bisa menolak</strong> pengajuan jika bukti transfer tidak valid (data atau nominal tidak sesuai). Jika ditolak, pengajuan tidak diproses; Anda bisa mengajukan ulang dengan bukti yang benar.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                                Nabung Sekarang
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('nasabah.guide.tabungan-setoran') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-[#8b6f2f]/40 text-[#674c1d] rounded-xl text-sm font-medium hover:bg-[#674c1d]/5 transition-colors">
                                Panduan detail & cara akses
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </details>

                {{-- Penarikan (Tarik) - Expandable --}}
                <details class="group rounded-2xl border border-gray-100 bg-gray-50/50 overflow-hidden">
                    <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 hover:bg-[#674c1d]/5 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#674c1d]/15 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Penarikan (Tarik)</h3>
                                <p class="text-sm text-gray-600">Transfer ke rekening atau tunai di lokasi</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="px-5 pb-5 pt-0 space-y-4">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                                <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold">1</span>
                                Ajukan nominal & rekening
                            </span>
                            <span class="text-gray-400">→</span>
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-[#8b6f2f]/30 shadow-sm">
                                <span class="w-5 h-5 rounded-full bg-[#674c1d] text-white flex items-center justify-center font-bold">2</span>
                                Admin setujui & transfer
                            </span>
                            <span class="text-gray-400">→</span>
                            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-lg border border-green-200 shadow-sm">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Dana ke rekening Anda
                            </span>
                        </div>
                        <ul class="space-y-2 text-gray-700 text-sm">
                            <li class="flex items-start gap-2">
                                <span class="text-[#8b6f2f] mt-0.5 shrink-0">•</span>
                                <span><strong>Transfer:</strong> Isi nominal & rekening tujuan. Setelah admin menyetujui, dana akan ditransfer ke rekening Anda. <strong>Biaya transfer ditanggung nasabah</strong> (dipotong dari saldo).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#8b6f2f] mt-0.5 shrink-0">•</span>
                                <span><strong>Tunai (Janji Temu):</strong> Pilih lokasi & jadwal. Datang ke lokasi untuk terima tunai. Pantau di <strong>Status Janji Temu</strong>.</span>
                            </li>
                        </ul>
                        {{-- Biaya transfer saat admin setujui --}}
                        <div class="rounded-xl bg-blue-50 border border-blue-200/80 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-1">Biaya transfer (penarikan)</h4>
                                    <p class="text-sm text-blue-900/90">Ketika admin menyetujui penarikan transfer, <strong>biaya transfer akan dihitung</strong> (sesuai bank pengirim koperasi dan bank tujuan Anda). Biaya ini <strong>dipotong dari saldo Anda</strong>. Total yang didebet = nominal penarikan + biaya transfer. Nominal yang Anda terima di rekening = nominal penarikan saja. Rincian biaya bisa Anda lihat di <strong>Detail Pengajuan Tarik</strong> setelah status Disetujui.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('nasabah.tabungan.penarikan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d]/10 text-[#674c1d] rounded-xl text-sm font-semibold hover:bg-[#674c1d]/20 transition-colors">
                                Penarikan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('nasabah.guide.tabungan-penarikan') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-[#8b6f2f]/40 text-[#674c1d] rounded-xl text-sm font-medium hover:bg-[#674c1d]/5 transition-colors">
                                Panduan detail & cara akses
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </details>

                <div class="pt-2 flex flex-wrap gap-3 border-t border-gray-100">
                    <a href="{{ route('nasabah.tabungan.index') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Dashboard Tabungan</a>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Pengajuan Setor</a>
                    <a href="{{ route('nasabah.tabungan.status-janji-temu') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Janji Temu</a>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="text-sm font-medium text-[#8b6f2f] hover:underline">Status Pengajuan Tarik</a>
                </div>
            </div>
        </section>

        {{-- ========== PINJAMAN ========== --}}
        <section class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50/80 px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 font-display">Pinjaman</h2>
                        <p class="text-gray-600 text-sm">Ajukan pinjaman, cairkan via transfer/janji temu, bayar angsuran</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                {{-- Pengajuan & Pencairan --}}
                <details class="group rounded-2xl border border-gray-100 bg-amber-50/30 overflow-hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 hover:bg-amber-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Pengajuan & Pencairan</h3>
                                <p class="text-sm text-gray-600">Ajukan nominal, pilih transfer atau janji temu</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="px-5 pb-5 pt-0 space-y-4">
                        <ul class="space-y-2 text-gray-700 text-sm">
                            <li class="flex items-start gap-2">
                                <span class="text-amber-600 mt-0.5 shrink-0">•</span>
                                <span>Ajukan pinjaman dengan nominal & durasi. Gunakan <strong>Simulasi Angsuran</strong> untuk melihat estimasi angsuran per bulan.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-amber-600 mt-0.5 shrink-0">•</span>
                                <span>Pilih <strong>Transfer</strong> atau <strong>Janji Temu</strong>. Jika janji temu, tentukan lokasi & jadwal. Setelah admin menyetujui dan mencairkan, dana masuk sesuai pilihan Anda.</span>
                            </li>
                        </ul>
                        <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-800 rounded-xl text-sm font-semibold hover:bg-amber-200 transition-colors">
                            Pengajuan Pinjaman
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </details>

                {{-- Pembayaran Angsuran --}}
                <details class="group rounded-2xl border border-gray-100 bg-amber-50/30 overflow-hidden">
                    <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 hover:bg-amber-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Pembayaran Angsuran</h3>
                                <p class="text-sm text-gray-600">Bayar via transfer atau janji temu</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="px-5 pb-5 pt-0 space-y-4">
                        <ul class="space-y-2 text-gray-700 text-sm">
                            <li class="flex items-start gap-2">
                                <span class="text-amber-600 mt-0.5 shrink-0">•</span>
                                <span><strong>Transfer:</strong> Upload bukti transfer pembayaran. Admin verifikasi lalu angsuran tercatat.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-amber-600 mt-0.5 shrink-0">•</span>
                                <span><strong>Janji Temu:</strong> Buat janji bayar tunai di lokasi. Setelah proses, status pembayaran diperbarui.</span>
                            </li>
                        </ul>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('nasabah.pinjaman.pembayaran') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-800 rounded-xl text-sm font-semibold hover:bg-amber-200 transition-colors">
                                Pembayaran
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('nasabah.guide.pinjaman-pembayaran') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-amber-300 text-amber-800 rounded-xl text-sm font-medium hover:bg-amber-50 transition-colors">
                                Panduan detail & cara akses
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </details>

                {{-- Bunga, Denda & Suku Bunga --}}
                <details class="group rounded-2xl border border-amber-200 bg-amber-50/50 overflow-hidden">
                    <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 hover:bg-amber-100/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-200 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Bunga, Denda & Suku Bunga</h3>
                                <p class="text-sm text-gray-600">Informasi penting tentang biaya pinjaman</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="px-5 pb-5 pt-0 space-y-4">
                        <div class="space-y-4 text-sm text-gray-700">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                    <span class="text-amber-700 font-bold">%</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-0.5">Bunga pinjaman</h4>
                                    <p>Pinjaman dikenakan bunga sesuai ketentuan koperasi. Besar bunga mempengaruhi total angsuran bulanan Anda. Gunakan <strong>Simulasi Angsuran</strong> saat pengajuan untuk melihat perkiraan.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-0.5">Denda keterlambatan</h4>
                                    <p>Jika angsuran dibayar <strong>terlambat</strong> dari jadwal, akan dikenakan <strong>denda</strong> sesuai kebijakan koperasi. Besar denda dapat dilihat di detail angsuran. Usahakan bayar tepat waktu agar tidak terkena denda.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-200 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-0.5">Suku bunga per periode (mis. per 3 bulan)</h4>
                                    <p><strong>Suku bunga pinjaman dapat naik per periode</strong> (misalnya per 3 bulan) sesuai kebijakan koperasi. Artinya, bunga yang berlaku untuk pinjaman Anda mengikuti periode/tenor saat pengajuan. Untuk informasi suku bunga terkini, Anda dapat mengecek saat melakukan simulasi atau pengajuan pinjaman.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('nasabah.pinjaman.pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-800 rounded-xl text-sm font-semibold hover:bg-amber-200 transition-colors">
                                Simulasi & Pengajuan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('nasabah.guide.pinjaman-pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-amber-300 text-amber-800 rounded-xl text-sm font-medium hover:bg-amber-50 transition-colors">
                                Lihat bunga, denda & sandbox simulasi
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </details>

                <div class="pt-2 flex flex-wrap gap-3 border-t border-gray-100">
                    <a href="{{ route('nasabah.pinjaman.index') }}" class="text-sm font-medium text-amber-700 hover:underline">Dashboard Pinjaman</a>
                    <a href="{{ route('nasabah.pinjaman.status-pengajuan') }}" class="text-sm font-medium text-amber-700 hover:underline">Status Pengajuan</a>
                    <a href="{{ route('nasabah.pinjaman.angsuran') }}" class="text-sm font-medium text-amber-700 hover:underline">Angsuran</a>
                    <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" class="text-sm font-medium text-amber-700 hover:underline">Status Pembayaran</a>
                </div>
            </div>
        </section>

        {{-- Gadai & Deposito (Coming Soon) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 text-center opacity-90">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 font-display mb-1">Gadai</h3>
                <p class="text-gray-500 text-sm">Segera hadir. Layanan gadai akan dapat diakses di sini.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 text-center opacity-90">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 19l10-10 4 4 6-6m0 0v6m0-6h6"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 font-display mb-1">Deposito</h3>
                <p class="text-gray-500 text-sm">Segera hadir. Layanan deposito akan dapat diakses di sini.</p>
            </div>
        </div>

        {{-- Tips --}}
        <div class="bg-gradient-to-r from-[#674c1d]/5 to-[#d4af37]/10 rounded-2xl p-6 border border-[#8b6f2f]/20">
            <h3 class="font-bold text-[#674c1d] mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                Tips
            </h3>
            <ul class="text-gray-700 text-sm space-y-2">
                <li>• Semua pengajuan dan janji temu bisa Anda pantau di menu <strong>Status</strong> masing-masing fitur.</li>
                <li>• Pastikan PIN Anda aman dan tidak dibagikan ke siapapun.</li>
                <li>• Untuk penarikan transfer, biaya transfer admin akan dipotong dari saldo Anda; nominal yang diterima ke rekening sesuai yang Anda ajukan.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
