@extends('layouts.admin')

@section('title', 'Edit Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.pinjaman.index') }}" class="hover:text-gray-900">Pinjaman</a>
                <span>/</span>
                <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}" class="hover:text-gray-900">Pinjaman Aktif</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit Pinjaman</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Pinjaman</h1>
            <p class="text-gray-600 mt-1">ID Pinjaman: #{{ $pinjaman->id }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form action="{{ route('admin.pinjaman.update-pinjaman', $pinjaman->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Pilih Nasabah -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Nasabah *</label>
                <select name="id_anggota" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <option value="">Pilih Nasabah</option>
                    @foreach($nasabah as $item)
                        <option value="{{ $item->id }}" {{ old('id_anggota', $pinjaman->id_anggota) == $item->id ? 'selected' : '' }}>
                            {{ $item->user->nama ?? 'N/A' }} - {{ $item->user->email ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nominal -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="nominal" value="{{ old('nominal', $pinjaman->jumlah_pinjam) }}" required min="100000" step="10000"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>
            </div>

            <!-- Durasi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan) *</label>
                <select name="durasi" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <option value="">Pilih Durasi</option>
                    @for($i = 1; $i <= 24; $i++)
                        <option value="{{ $i }}" {{ old('durasi', $pinjaman->lama_pinjam) == $i ? 'selected' : '' }}>{{ $i }} bulan</option>
                    @endfor
                </select>
            </div>

            <!-- Tanggal Pinjam -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam *</label>
                <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', $pinjaman->tgl_pinjam->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all font-medium shadow-md">
                    Update Pinjaman
                </button>
                <a href="{{ route('admin.pinjaman.detail-pinjaman', $pinjaman->id) }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
