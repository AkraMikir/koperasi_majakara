@extends('layouts.admin')

@section('title', 'Pengajuan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Pengajuan</h1>
            <p class="text-gray-600 mt-1">Kelola semua pengajuan dari nasabah</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pengajuan Tabungan -->
        <a href="{{ route('admin.tabungan.pengajuan-setor') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#674c1d]/20 to-[#674c1d]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pengajuan Setor Tabungan</h3>
            <p class="text-xs text-gray-500">Kelola pengajuan setoran tabungan</p>
        </a>

        <!-- Pengajuan Penarikan Tabungan -->
        <a href="{{ route('admin.tabungan.pengajuan-tarik') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-[#8b6f2f]/20 to-[#8b6f2f]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pengajuan Penarikan Tabungan</h3>
            <p class="text-xs text-gray-500">Kelola pengajuan penarikan tabungan</p>
        </a>

        <!-- Pengajuan Pinjaman -->
        <a href="{{ route('admin.pinjaman.pengajuan') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-blue-500/20 to-blue-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Pengajuan Pinjaman</h3>
            <p class="text-xs text-gray-500">Kelola pengajuan pinjaman</p>
        </a>

        <!-- Janji Temu -->
        <a href="{{ route('admin.janji-temu.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-linear-to-br from-green-500/20 to-green-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">Janji Temu</h3>
            <p class="text-xs text-gray-500">Kelola janji temu nasabah</p>
        </a>
    </div>

    <!-- Info Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Halaman Pengajuan</h3>
                <p class="text-gray-600 text-sm mb-3">
                    Halaman ini menampilkan semua jenis pengajuan dari nasabah. Gunakan menu di atas untuk mengakses pengajuan spesifik:
                </p>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li><strong>Pengajuan Setor Tabungan:</strong> Kelola pengajuan setoran tabungan dari nasabah</li>
                    <li><strong>Pengajuan Penarikan Tabungan:</strong> Kelola pengajuan penarikan tabungan</li>
                    <li><strong>Pengajuan Pinjaman:</strong> Kelola pengajuan pinjaman dari nasabah</li>
                    <li><strong>Janji Temu:</strong> Kelola janji temu nasabah untuk berbagai keperluan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
