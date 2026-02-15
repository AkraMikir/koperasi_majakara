@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Keuangan</h1>
        <p class="text-gray-600 mt-1">Pilih jenis laporan untuk melihat data dan export PDF/Excel</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.laporan.rekapitulasi') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-[#674c1d]/10 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Rekapitulasi Harian/Bulanan</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan setoran, penarikan, pencairan pinjaman, dan angsuran masuk</p>
        </a>

        <a href="{{ route('admin.laporan.tabungan') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Laporan Tabungan</h2>
            <p class="text-sm text-gray-500 mt-1">Mutasi transaksi setor dan penarikan per periode</p>
        </a>

        <a href="{{ route('admin.laporan.saldo-tabungan') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Saldo Tabungan</h2>
            <p class="text-sm text-gray-500 mt-1">Saldo tabungan per nasabah pada tanggal cutoff</p>
        </a>

        <a href="{{ route('admin.laporan.pinjaman-aktif') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Pinjaman Aktif</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar pinjaman belum lunas dan sisa pokok</p>
        </a>

        <a href="{{ route('admin.laporan.angsuran-pinjaman') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Angsuran Pinjaman</h2>
            <p class="text-sm text-gray-500 mt-1">Realisasi pembayaran angsuran per periode</p>
        </a>

        <a href="{{ route('admin.laporan.jatuh-tempo') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Jatuh Tempo</h2>
            <p class="text-sm text-gray-500 mt-1">Angsuran jatuh tempo dalam periode</p>
        </a>

        <a href="{{ route('admin.laporan.pengajuan') }}" class="block p-6 bg-white rounded-2xl shadow-md border border-gray-100 hover:border-[#674c1d]/30 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Laporan Pengajuan</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan pengajuan setor, tarik, pinjaman, pembayaran</p>
        </a>
    </div>
</div>
@endsection
