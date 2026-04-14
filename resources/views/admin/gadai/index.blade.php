@extends('layouts.admin')

@section('title', 'Gadai - Segera Hadir')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Gadai</h1>
            <p class="text-gray-600 mt-1">Daftar gadai nasabah koperasi</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-10 border border-gray-100 text-center">
        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Modul Gadai Sedang Dalam Pengembangan</h2>
        <p class="text-gray-600 max-w-lg mx-auto">Fitur gadai saat ini belum tersedia dan masih dalam tahap pengembangan. Silakan kembali lagi nanti.</p>
        
        <div class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8d6b2c] text-white rounded-xl hover:from-[#573f17] hover:to-[#765d29] transition-all font-semibold shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
