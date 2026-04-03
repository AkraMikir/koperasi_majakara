@extends('layouts.admin')

@section('title', 'Detail Setoran Kantor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.petty-cash.setoran-approval.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Setoran</h1>
            <p class="text-gray-600 mt-1">ID: {{ $setoran->id }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif

    {{-- Info Card --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Admin</p>
                <p class="font-bold text-gray-900 mt-1">{{ $setoran->admin->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Setoran</p>
                <p class="font-bold text-[#674c1d] text-xl mt-1">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Jumlah Nasabah</p>
                <p class="font-bold text-gray-900 text-xl mt-1">{{ $setoran->jumlah_nasabah }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Status</p>
                <div class="mt-1">
                    @if($setoran->status === 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">Pending</span>
                    @elseif($setoran->status === 'approved_owner')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">✓ Disetujui</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">Ditolak</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5 mt-5 pt-5 border-t border-gray-100">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Tanggal Setoran</p>
                <p class="font-medium text-gray-900 mt-1">{{ $setoran->tgl_setoran->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Setor Fisik</p>
                <p class="font-medium text-gray-900 mt-1">{{ $setoran->sudah_setor_fisik ? '✅ Sudah disetor fisik' : '⏳ Belum disetor fisik' }}</p>
            </div>
            @if($setoran->keterangan_admin)
            <div class="col-span-2">
                <p class="text-xs text-gray-500 uppercase font-medium">Catatan Admin</p>
                <p class="text-gray-700 mt-1 text-sm">{{ $setoran->keterangan_admin }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Foto Setoran --}}
    @if($setoran->foto_setoran)
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-[#674c1d] mb-4 font-display">Foto Bukti Setoran</h2>
        <img src="{{ Storage::url($setoran->foto_setoran) }}" alt="Bukti Setoran"
            class="max-w-sm rounded-xl border border-gray-200 shadow-sm"/>
    </div>
    @endif

    {{-- Daftar Nasabah --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
            <h2 class="text-sm font-bold text-[#674c1d] uppercase">Rincian Nasabah</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase">Nasabah</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-600 uppercase">Nominal</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-600 uppercase">Transaksi</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-600 uppercase">Via</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-600 uppercase">Fitur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach((array) $setoran->data_potongan as $i => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-900">{{ $item['nama'] ?? '-' }}</p>
                            <p class="text-xs text-gray-500">ID: {{ $item['nasabah_id'] ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-[#674c1d]">Rp {{ number_format($item['nominal'] ?? 0, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-1 bg-[#674c1d]/10 text-[#674c1d] rounded text-xs font-medium">
                                {{ $item['transaksi'] ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-1 {{ in_array($item['via_kode'] ?? '', ['CS','TN']) ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} rounded text-xs font-medium">
                                {{ $item['via'] ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $item['fitur'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-[#674c1d]/20">
                    <tr>
                        <td colspan="2" class="px-5 py-3 font-bold text-gray-900 text-sm">TOTAL</td>
                        <td class="px-5 py-3 text-right font-bold text-[#674c1d]">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Action Buttons --}}
    @if($setoran->status === 'pending')
    <div class="flex gap-4">
        <form action="{{ route('admin.petty-cash.setoran-approval.approve', $setoran->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit"
                onclick="return confirm(`Setujui setoran Rp {{ number_format((float) $setoran->total_setor, 0, ',', '.') }}?`)"
                class="w-full py-3 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                APPROVE Setoran
            </button>
        </form>

        <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')"
            class="flex-1 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors">
            Tolak Setoran
        </button>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Setoran</h3>
            <form action="{{ route('admin.petty-cash.setoran-approval.reject', $setoran->id) }}" method="POST">
                @csrf
                <textarea name="keterangan_owner" rows="3" required
                    placeholder="Alasan penolakan..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] mb-4"></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold">Tolak</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
