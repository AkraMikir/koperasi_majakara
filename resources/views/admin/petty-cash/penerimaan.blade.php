@extends('layouts.admin')

@section('title', 'Terima Dana dari Owner')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Penerimaan Dana</h1>
            <p class="text-gray-600 mt-1">Dana yang dikirim Owner untuk operasional petty cash</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Saldo Saat Ini --}}
            <div class="bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl px-5 py-3 text-white text-center">
                <p class="text-xs text-white/70">Saldo Anda Saat Ini</p>
                <p class="text-xl font-bold">Rp {{ number_format($saldoAdmin, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    {{-- Daftar Penerimaan --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-[#674c1d] font-display">Daftar Dana Masuk</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Dari Owner</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Transfer</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Cash</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaan as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item->tgl_penerimaan->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $item->owner->nama ?? '-' }}</p>
                            @if($item->keterangan)
                                <p class="text-[10px] text-gray-500 italic mt-1 bg-gray-50 p-1 rounded border border-gray-100 line-clamp-2" title="{{ $item->keterangan }}">
                                    Owner: {{ $item->keterangan }}
                                </p>
                            @endif
                            @if($item->keterangan_admin && $item->status !== 'pending')
                                <p class="text-[10px] text-[#674c1d] italic mt-1 bg-amber-50 p-1 rounded border border-amber-100 line-clamp-2" title="{{ $item->keterangan_admin }}">
                                    Admin: {{ $item->keterangan_admin }}
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">Rp {{ number_format($item->nominal_tf, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm">Rp {{ number_format($item->nominal_cash, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-[#674c1d]">Rp {{ number_format($item->nominal_tf + $item->nominal_cash, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                            @elseif($item->status === 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">ACC</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'pending')
                            <div class="flex items-center justify-center gap-2">
                                {{-- Lihat Bukti --}}
                                @if($item->bukti_tf)
                                <a href="{{ Storage::url($item->bukti_tf) }}" target="_blank"
                                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Bukti TF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                @endif
                                {{-- ACC Modal Trigger --}}
                                <button type="button"
                                        onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.remove('hidden')"
                                        class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition-colors">
                                    ACC
                                </button>
                                {{-- Reject --}}
                                <button type="button"
                                        onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.remove('hidden')"
                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors">
                                    Tolak
                                </button>
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- ACC Modal --}}
                    @if($item->status === 'pending')
                    <div id="approve-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Terima Dana</h3>
                            <p class="text-sm text-gray-600 mb-4">Total: Rp {{ number_format($item->nominal_tf + $item->nominal_cash, 0, ',', '.') }}</p>
                            <form action="{{ route('admin.petty-cash.penerimaan.approve', $item->id) }}" method="POST">
                                @csrf
                                <textarea name="keterangan_admin" rows="3"
                                    placeholder="Catatan penerimaan (Opsional)..."
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent mb-4"></textarea>
                                <div class="flex gap-3">
                                    <button type="button"
                                            onclick="document.getElementById('approve-modal-{{ $item->id }}').classList.add('hidden')"
                                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700">
                                        SETUJUI
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Reject Modal --}}
                    @if($item->status === 'pending')
                    <div id="reject-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Penerimaan</h3>
                            <form action="{{ route('admin.petty-cash.penerimaan.reject', $item->id) }}" method="POST">
                                @csrf
                                <textarea name="keterangan_admin" rows="3" required
                                    placeholder="Alasan penolakan..."
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] focus:border-transparent mb-4"></textarea>
                                <div class="flex gap-3">
                                    <button type="button"
                                            onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.add('hidden')"
                                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700">
                                        TOLAK
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500">Belum ada dana yang dikirim Owner</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penerimaan->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $penerimaan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
