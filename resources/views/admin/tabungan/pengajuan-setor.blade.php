@extends('layouts.admin')

@section('title', 'Pengajuan Setor Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Pengajuan Setor Tabungan</h1>
            <p class="text-gray-600 mt-1">Kelola pengajuan setoran tabungan dari nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.tabungan.pengajuan-setor') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[[#674c1d]] focus:border-[[#674c1d]] outline-none">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[[#674c1d]] focus:border-[[#674c1d]] outline-none">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Disetujui</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-linear-to-r from-[[#674c1d]] to-[[#8b6f2f]] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all shadow-md font-medium">
                Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[[#674c1d]]/20 bg-linear-to-r from-[[#674c1d]]/5 to-[[#8b6f2f]]/5">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Bukti Foto</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[[#674c1d]] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $item)
                    @php
                        // Nominal dari pengajuan (PengajuanTabungan.nominal)
                        $totalNominal = (float) ($item->nominal ?? 0);
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-linear-to-r hover:from-[[#674c1d]]/5 hover:to-[[#8b6f2f]]/5 transition-all">
                        <td class="px-6 py-4 text-sm font-medium">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($totalNominal > 0)
                                <p class="font-semibold text-[[#674c1d]]">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                {{ $item->buktiFoto->count() ?? 0 }} foto
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                    '2' => ['label' => 'Disetujui', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                    '3' => ['label' => 'Ditolak', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                ];
                                $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                            @endphp
                            <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-xs font-semibold">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tabungan.detail-pengajuan-setor', $item->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-linear-to-r from-[[#674c1d]] to-[[#8b6f2f]] text-white rounded-lg hover:from-[[#4a3514]] hover:to-[[#674c1d]] transition-all text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada pengajuan setor</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pengajuan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


