@extends('layouts.admin')

@section('title', 'Laporan Petty Cash')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Laporan Petty Cash</h1>
            <p class="text-gray-600 mt-1">Riwayat lengkap aliran dana operasional</p>
        </div>
        <a href="{{ route('admin.petty-cash.dashboard') }}"
           class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            ← Dashboard
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
            <p class="text-xs font-bold text-green-700 uppercase mb-1">Total Penerimaan (Owner → Admin)</p>
            <p class="text-3xl font-bold text-green-800">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1">Semua penerimaan yang sudah ACC</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
            <p class="text-xs font-bold text-blue-700 uppercase mb-1">Total Setoran (Admin → Owner)</p>
            <p class="text-3xl font-bold text-blue-800">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</p>
            <p class="text-xs text-blue-600 mt-1">Sudah diverifikasi Owner</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Admin</label>
                <select name="admin_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]">
                    <option value="">Semua Admin</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                <select name="status" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved_owner" {{ request('status') === 'approved_owner' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-[#674c1d] text-white rounded-xl text-sm font-semibold hover:bg-[#4a3514] transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['admin_id', 'tanggal_dari', 'tanggal_sampai', 'status']))
            <a href="{{ route('admin.petty-cash.laporan') }}"
               class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm hover:bg-gray-50 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Admin</th>
                        <th class="px-5 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Setor Fisik</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporan as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 text-sm text-gray-700">{{ $item->tgl_setoran->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-900 text-sm">{{ $item->admin->nama ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-[#674c1d]">Rp {{ number_format($item->total_setor, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-center text-sm">{{ $item->jumlah_nasabah }} orang</td>
                        <td class="px-5 py-4 text-center">
                            <span class="{{ $item->sudah_setor_fisik ? 'text-green-600' : 'text-yellow-600' }} text-sm">
                                {{ $item->sudah_setor_fisik ? '✅' : '⏳' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($item->status === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                            @elseif($item->status === 'approved_owner')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">ACC</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button onclick="document.getElementById('detail-lap-{{ $item->id }}').classList.toggle('hidden')"
                                class="text-xs text-[#674c1d] hover:underline font-medium">
                                Detail
                            </button>
                        </td>
                    </tr>
                    {{-- Detail Row --}}
                    <tr id="detail-lap-{{ $item->id }}" class="hidden bg-gray-50">
                        <td colspan="7" class="px-5 py-4">
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-gray-600">Nasabah</th>
                                            <th class="px-3 py-2 text-right text-gray-600">Nominal</th>
                                            <th class="px-3 py-2 text-center text-gray-600">Via</th>
                                            <th class="px-3 py-2 text-center text-gray-600">Fitur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach((array) $item->data_potongan as $d)
                                        <tr>
                                            <td class="px-3 py-2">{{ $d['nama'] ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right font-semibold">Rp {{ number_format($d['nominal'] ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-center">{{ $d['via'] ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center">{{ $d['fitur'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-gray-500">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Tidak ada data laporan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporan->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $laporan->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
