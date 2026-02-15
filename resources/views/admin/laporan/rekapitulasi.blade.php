@extends('layouts.admin')

@section('title', 'Laporan Rekapitulasi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Rekapitulasi</h1>
            <p class="text-gray-600 mt-1">Ringkasan transaksi tabungan dan pinjaman per hari atau bulan</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">← Daftar Laporan</a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.laporan.rekapitulasi') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select name="tipe" id="tipeRekap" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d]">
                    <option value="harian" {{ ($tipe ?? '') === 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ ($tipe ?? 'bulanan') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            <div id="wrapTgl" class="{{ ($tipe ?? 'bulanan') === 'bulanan' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tgl" value="{{ $tgl ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <div id="wrapBulan" class="{{ ($tipe ?? 'bulanan') === 'harian' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] font-medium">Tampilkan</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Periode: {{ $dari }} s/d {{ $sampai }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200">Export PDF</a>
                <a href="{{ route('admin.laporan.rekapitulasi', array_merge(request()->query(), ['export' => 'excel'])) }}" class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">Export Excel</a>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border border-gray-200 rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-[#674c1d] mb-4">Tabungan</h3>
                    <table class="w-full text-sm">
                        <tr><td class="py-2 text-gray-600">Total Setoran</td><td class="py-2 text-right font-semibold">Rp {{ number_format($setoran_tabungan ?? 0, 0, ',', '.') }}</td></tr>
                        <tr><td class="py-2 text-gray-600">Total Penarikan</td><td class="py-2 text-right font-semibold">Rp {{ number_format($penarikan_tabungan ?? 0, 0, ',', '.') }}</td></tr>
                        <tr class="border-t border-gray-200"><td class="py-2 font-medium">Net Tabungan</td><td class="py-2 text-right font-bold">Rp {{ number_format($net_tabungan ?? 0, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
                <div class="border border-gray-200 rounded-xl p-5">
                    <h3 class="text-lg font-semibold text-[#674c1d] mb-4">Pinjaman</h3>
                    <table class="w-full text-sm">
                        <tr><td class="py-2 text-gray-600">Pencairan (periode)</td><td class="py-2 text-right font-semibold">Rp {{ number_format($pencairan_pinjaman ?? 0, 0, ',', '.') }}</td></tr>
                        <tr><td class="py-2 text-gray-600">Angsuran Masuk (periode)</td><td class="py-2 text-right font-semibold">Rp {{ number_format($angsuran_masuk ?? 0, 0, ',', '.') }}</td></tr>
                        <tr class="border-t border-gray-200"><td class="py-2 font-medium">Outstanding</td><td class="py-2 text-right font-bold">Rp {{ number_format($outstanding ?? 0, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('tipeRekap').addEventListener('change', function() {
    var v = this.value;
    document.getElementById('wrapTgl').classList.toggle('hidden', v !== 'harian');
    document.getElementById('wrapBulan').classList.toggle('hidden', v === 'harian');
});
</script>
@endsection
