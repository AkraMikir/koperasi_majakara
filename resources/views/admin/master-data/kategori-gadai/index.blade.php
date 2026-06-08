@extends('layouts.admin')

@section('title', 'Master Kategori Gadai')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kategori Gadai</h1>
            <p class="text-gray-500 text-sm mt-1">Atur persentase bunga, denda, dan biaya inap per kategori</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm">
        <p class="font-bold">Berhasil!</p>
        <p class="text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nama Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Jasa (%)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Denda (%)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Inap (%)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Masa (Hari)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data as $kat)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $kat->nama_kategori }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ strtoupper($kat->kode_kategori) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-bold">{{ $kat->rate_jasa }}%</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-red-50 text-red-700 rounded font-bold">{{ $kat->rate_denda }}%</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($kat->kode_kategori === 'vehicle')
                                <div class="inline-block border border-indigo-100 rounded-xl overflow-hidden shadow-sm max-w-xs mx-auto text-left">
                                    <table class="w-full text-[11px]">
                                        <thead class="bg-indigo-50/70 font-bold text-indigo-700">
                                            <tr>
                                                <th class="px-2.5 py-1 text-[8px] uppercase tracking-wider">Gol</th>
                                                <th class="px-2.5 py-1 text-[8px] uppercase tracking-wider text-right">Tarif Inap</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-indigo-50">
                                            @foreach($inapKendaraans as $inap)
                                                <tr class="bg-white hover:bg-indigo-50/20 transition-colors">
                                                    <td class="px-2.5 py-1 font-bold text-gray-700 uppercase">Gol {{ $inap->golongan }}</td>
                                                    <td class="px-2.5 py-1 text-right font-black text-indigo-600">Rp {{ number_format($inap->nominal_inap, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded font-bold">{{ $kat->rate_inap_persen }}%</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm font-medium text-gray-700">{{ $kat->masa_gadai_hari }} Hari</p>
                            <p class="text-[10px] text-gray-400">+{{ $kat->masa_tenggang_hari }} Hari Tenggang</p>
                            <p class="text-[10px] text-amber-600 font-bold mt-0.5">Batas Ambil: {{ $kat->countdown_ambil_hari }} Hari</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @isAdminUtama
                            <a href="{{ route('admin.master-data.kategori-gadai.edit', $kat->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Aturan
                            </a>
                            @else
                            <span class="text-xs text-gray-400 italic">Read Only</span>
                            @endisAdminUtama
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-6 bg-amber-50 border border-amber-100 rounded-2xl">
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h3 class="font-bold text-amber-900">Informasi Penting</h3>
                <ul class="text-sm text-amber-800 mt-1 space-y-1 list-disc ml-4">
                    <li><strong>Biaya Jasa & Denda:</strong> Menggunakan persentase dari Nominal Deal.</li>
                    <li><strong>Biaya Inap:</strong> Untuk kategori <strong>Emas/Elektronik</strong> biasanya menggunakan persentase. Untuk <strong>Kendaraan</strong>, biaya inap biasanya diatur per-item barang (Motor/Mobil) di menu <a href="{{ route('admin.master-data.item-gadai.index') }}" class="font-bold underline">Item Gadai</a>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
