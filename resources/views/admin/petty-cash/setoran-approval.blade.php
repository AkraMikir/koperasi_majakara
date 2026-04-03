@extends('layouts.admin')

@section('title', 'Verifikasi Setoran Kantor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Verifikasi Setoran</h1>
            <p class="text-gray-600 mt-1">Setoran cash dari admin yang perlu diverifikasi</p>
        </div>
        <div class="flex items-center gap-3">
            @foreach(['pending' => 'Pending', 'approved_owner' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
               {{ request('status', 'pending') === $val
                   ? 'bg-[#674c1d] text-white'
                   : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="space-y-4">
        @forelse($setoran as $s)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden
            {{ $s->status === 'pending' ? 'border-l-4 border-l-amber-500' : '' }}">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-start gap-5">
                    {{-- Info Kiri --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-[#674c1d]/10 rounded-full flex items-center justify-center">
                                <span class="text-[#674c1d] font-bold text-sm">{{ strtoupper(substr($s->admin->nama ?? 'A', 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Admin {{ $s->admin->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $s->tgl_setoran->format('d M Y, H:i') }}</p>
                            </div>
                            @if($s->status === 'pending')
                                <span class="ml-auto px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                            @elseif($s->status === 'approved_owner')
                                <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">✓ Disetujui</span>
                            @else
                                <span class="ml-auto px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <p class="text-xs text-gray-500">Total Setoran</p>
                                <p class="font-bold text-[#674c1d] text-xl mt-0.5">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jumlah Nasabah</p>
                                <p class="font-bold text-gray-900 text-xl mt-0.5">{{ $s->jumlah_nasabah }} orang</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Setor Fisik</p>
                                <p class="font-semibold text-gray-900 mt-0.5">
                                    {{ $s->sudah_setor_fisik ? '✅ Sudah' : '⏳ Belum' }}
                                </p>
                            </div>
                        </div>

                        {{-- Preview Potongan Data --}}
                        @if($s->data_potongan)
                        <div class="mt-4">
                            <button onclick="document.getElementById('detail-{{ $s->id }}').classList.toggle('hidden')"
                                class="text-sm text-[#674c1d] font-medium hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Lihat Detail {{ count($s->data_potongan) }} Nasabah
                            </button>
                            <div id="detail-{{ $s->id }}" class="hidden mt-3 overflow-x-auto">
                                <table class="w-full text-xs border border-gray-200 rounded-xl overflow-hidden">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-gray-600 font-semibold">Nasabah</th>
                                            <th class="px-3 py-2 text-right text-gray-600 font-semibold">Nominal</th>
                                            <th class="px-3 py-2 text-center text-gray-600 font-semibold">Via</th>
                                            <th class="px-3 py-2 text-center text-gray-600 font-semibold">Fitur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($s->data_potongan as $item)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $item['nama'] ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right font-semibold text-[#674c1d]">Rp {{ number_format($item['nominal'] ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span class="px-1.5 py-0.5 {{ in_array($item['via_kode'] ?? '', ['CS','TN']) ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }} rounded">
                                                    {{ $item['via'] ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-center text-gray-600">{{ $item['fitur'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Aksi Kanan --}}
                    @if($s->status === 'pending')
                    <div class="flex flex-row lg:flex-col gap-3 flex-shrink-0">
                        {{-- Lihat Foto --}}
                        @if($s->foto_setoran)
                        <a href="{{ Storage::url($s->foto_setoran) }}" target="_blank"
                           class="px-4 py-2 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl text-sm font-medium hover:bg-blue-100 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                            </svg>
                            Lihat Foto
                        </a>
                        @endif

                        {{-- ACC --}}
                        <form action="{{ route('admin.petty-cash.setoran-approval.approve', $s->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm(`Setujui setoran Rp {{ number_format((float) $s->total_setor, 0, ',', '.') }} dari {{ $s->admin->nama ?? '-' }}?`)"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                APPROVE
                            </button>
                        </form>

                        {{-- Reject --}}
                        <button type="button"
                            onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.remove('hidden')"
                            class="px-4 py-2 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors">
                            Tolak
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Reject Modal --}}
        @if($s->status === 'pending')
        <div id="reject-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Setoran</h3>
                <p class="text-sm text-gray-600 mb-4">Admin: {{ $s->admin->nama ?? '-' }} · Total: Rp {{ number_format($s->total_setor, 0, ',', '.') }}</p>
                <form action="{{ route('admin.petty-cash.setoran-approval.reject', $s->id) }}" method="POST">
                    @csrf
                    <textarea name="keterangan_owner" rows="3" required
                        placeholder="Alasan penolakan..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d] mb-4"></textarea>
                    <div class="flex gap-3">
                        <button type="button"
                            onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @empty
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-16 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada setoran yang perlu diverifikasi</p>
        </div>
        @endforelse
    </div>

    @if($setoran->hasPages())
    <div class="flex justify-center">
        {{ $setoran->links() }}
    </div>
    @endif
</div>
@endsection
