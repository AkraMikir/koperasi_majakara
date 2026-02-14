@extends('layouts.admin')

@section('title', 'Laporan Pengajuan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Pengajuan</h1>
            <p class="text-gray-600 mt-1">Ringkasan pengajuan setor, tarik, pinjaman, pembayaran per periode</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.pengajuan') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                <input type="date" name="tgl_dari" value="{{ $tgl_dari ?? now()->startOfMonth()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" value="{{ $tgl_sampai ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
                    <option value="">Semua</option>
                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ ($status ?? '') === '2' ? 'selected' : '' }}>Disetujui</option>
                    <option value="3" {{ ($status ?? '') === '3' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'pdf']) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a>
                <a href="{{ route('admin.laporan.pengajuan', ['tgl_dari' => $tgl_dari, 'tgl_sampai' => $tgl_sampai, 'status' => $status, 'export' => 'excel']) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-600">Setor Tabungan</p>
                    <p class="text-xl font-bold text-[#674c1d]">{{ $summary['setor']['count'] ?? 0 }} ajuan</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($summary['setor']['nominal'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-600">Tarik Tabungan</p>
                    <p class="text-xl font-bold text-[#674c1d]">{{ $summary['tarik']['count'] ?? 0 }} ajuan</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($summary['tarik']['nominal'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-600">Pinjaman</p>
                    <p class="text-xl font-bold text-[#674c1d]">{{ $summary['pinjaman']['count'] ?? 0 }} ajuan</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($summary['pinjaman']['nominal'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-600">Pembayaran Pinjaman</p>
                    <p class="text-xl font-bold text-[#674c1d]">{{ $summary['pembayaran']['count'] ?? 0 }} ajuan</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($summary['pembayaran']['nominal'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
