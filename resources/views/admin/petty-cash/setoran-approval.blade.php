@extends('layouts.admin')

@section('title', 'Verifikasi Setoran Kantor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white/50 backdrop-blur-md p-4 rounded-[2rem] border border-white/20 shadow-sm">
        <div>
            <h1 class="text-3xl font-black text-gray-900 font-display tracking-tight">Verifikasi Setoran</h1>
            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-[#674c1d] rounded-full animate-pulse"></span>
                Antrean setoran cash dari admin operasional
            </p>
        </div>
        <div class="flex items-center gap-2 bg-gray-100/50 p-1.5 rounded-2xl border border-gray-200/50">
            @foreach(['pending' => 'Pending', 'approved_owner' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] transition-all
               {{ request('status', 'pending') === $val
                   ? 'bg-[#674c1d] text-white shadow-lg shadow-[#674c1d]/20 scale-[1.05]'
                   : 'text-gray-500 hover:text-[#674c1d] hover:bg-white' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-500 text-white px-6 py-4 rounded-2xl shadow-lg shadow-green-500/20 flex items-center gap-3 animate-bounce-short">
        <i class="fas fa-check-circle"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg shadow-red-500/20 flex items-center gap-3">
        <i class="fas fa-exclamation-triangle"></i>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="space-y-6">
        @forelse($setoran as $s)
        <div class="group bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden hover:shadow-2xl hover:shadow-gray-300/50 transition-all duration-500
            {{ $s->status === 'pending' ? 'ring-2 ring-amber-400 ring-offset-4' : '' }}">
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start gap-8">
                    {{-- Info Kiri --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-2xl flex items-center justify-center shadow-lg shadow-[#674c1d]/20 transform group-hover:rotate-6 transition-transform">
                                <span class="text-white font-black text-xl">{{ strtoupper(substr($s->admin->nama ?? 'A', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-black text-gray-900 text-lg">Admin {{ $s->admin->nama ?? '-' }}</h3>
                                    @if($s->status === 'pending')
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md text-[8px] font-black uppercase tracking-tighter">New Entry</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $s->tgl_setoran->format('d M Y · H:i') }}</p>
                            </div>
                            
                            {{-- Status Badge (Desktop) --}}
                            <div class="hidden lg:block text-right">
                                @if($s->status === 'pending')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-100">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        Pending
                                    </span>
                                @elseif($s->status === 'approved_owner')
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-100">
                                            <i class="fas fa-check-double text-[8px]"></i>
                                            Approved
                                        </span>
                                        @if($s->tgl_approval)
                                            <p class="text-[9px] text-gray-400 mt-1 font-bold">{{ $s->tgl_approval->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-100">
                                        <i class="fas fa-times-circle text-[8px]"></i>
                                        Rejected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100 mb-6">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Setor</p>
                                <p class="font-black text-[#674c1d] text-xl">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Metode</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-[9px] font-black">CASH</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[9px] font-black">TF</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nasabah</p>
                                <p class="font-black text-gray-800 text-xl">{{ $s->jumlah_nasabah }} <span class="text-[10px] text-gray-400">Pax</span></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Fisik</p>
                                <p class="font-black text-gray-800 text-sm mt-1">
                                    {!! $s->sudah_setor_fisik 
                                        ? '<span class="text-green-600 flex items-center gap-1"><i class="fas fa-check-square"></i> SUDAH</span>' 
                                        : '<span class="text-amber-500 flex items-center gap-1"><i class="fas fa-clock"></i> BELUM</span>' 
                                    !!}
                                </p>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        @if($s->keterangan_admin || ($s->keterangan_owner && $s->status !== 'pending'))
                        <div class="flex flex-col gap-3 mb-6">
                            @if($s->keterangan_admin)
                            <div class="relative pl-4 border-l-2 border-gray-200">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Catatan Admin</p>
                                <p class="text-sm text-gray-600 italic font-medium leading-relaxed">"{{ $s->keterangan_admin }}"</p>
                            </div>
                            @endif
                            @if($s->keterangan_owner && $s->status !== 'pending')
                            <div class="relative pl-4 border-l-2 border-[#674c1d]/30 bg-[#674c1d]/5 p-3 rounded-r-2xl">
                                <p class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest mb-1">Respon Owner (Anda)</p>
                                <p class="text-sm text-[#674c1d] italic font-bold leading-relaxed">"{{ $s->keterangan_owner }}"</p>
                            </div>
                            @endif
                        </div>
                        @endif

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
                    <div class="flex flex-row lg:flex-col gap-3 flex-shrink-0 lg:w-48">
                        {{-- Status Badge (Mobile focus/Re-emphasis) --}}
                        <div class="lg:hidden mb-2">
                            @if($s->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Pending</span>
                            @elseif($s->status === 'approved_owner')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Rejected</span>
                            @endif
                        </div>

                        {{-- Lihat Foto - Always show if exists --}}
                        @if($s->foto_setoran)
                        <a href="{{ Storage::url($s->foto_setoran) }}" target="_blank"
                           class="w-full px-4 py-2 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl text-xs font-bold hover:bg-blue-100 transition-all flex items-center justify-center gap-2 group">
                            <i class="fas fa-image text-blue-400 group-hover:scale-110 transition-transform"></i>
                            Lihat Bukti
                        </a>
                        @endif

                        @if($s->status === 'pending')
                            {{-- APPROVE Modal Trigger --}}
                            <button type="button"
                                onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.remove('hidden')"
                                class="w-full px-4 py-2 bg-[#674c1d] text-white rounded-xl text-xs font-bold hover:bg-[#4a3514] shadow-lg shadow-[#674c1d]/20 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                APPROVE
                            </button>

                            {{-- Reject Modal Trigger --}}
                            <button type="button"
                                onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.remove('hidden')"
                                class="w-full px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle"></i>
                                TOLAK
                            </button>
                        @endif

                        {{-- Print/Detail Link for History --}}
                        @if($s->status !== 'pending')
                            <a href="{{ route('admin.petty-cash.setoran-approval.detail', $s->id) }}" 
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-file-alt"></i>
                                Detail Slip
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Approve Modal --}}
        @if($s->status === 'pending')
        <div id="approve-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-[2rem] p-8 w-full max-w-md mx-4 shadow-2xl transform transition-all">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 font-display">Setujui Setoran</h3>
                        <p class="text-xs text-gray-500">Konfirmasi penerimaan dana dari admin</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Total Setoran</span>
                        <span class="text-sm font-black text-[#674c1d]">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Admin</span>
                        <span class="text-xs font-bold text-gray-700">{{ $s->admin->nama ?? '-' }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.petty-cash.setoran-approval.approve', $s->id) }}" method="POST">
                    @csrf
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Catatan Persetujuan (Opsional)</label>
                    <textarea name="keterangan_owner" rows="3"
                        placeholder="Contoh: Dana fisik sudah diterima lengkap..."
                        class="w-full border-gray-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] bg-gray-50/50 mb-6 transition-all resize-none"></textarea>
                    
                    <div class="flex gap-3">
                        <button type="button"
                            onclick="document.getElementById('approve-modal-{{ $s->id }}').classList.add('hidden')"
                            class="flex-1 px-6 py-3.5 border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] px-6 py-3.5 bg-[#674c1d] text-white rounded-2xl text-sm font-black shadow-xl shadow-[#674c1d]/20 hover:bg-[#4a3514] transition-all">SETUJUI</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Reject Modal --}}
        @if($s->status === 'pending')
        <div id="reject-modal-{{ $s->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-[2rem] p-8 w-full max-w-md mx-4 shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 font-display">Tolak Setoran</h3>
                        <p class="text-xs text-gray-500">Dana akan dikembalikan ke saldo admin</p>
                    </div>
                </div>

                <form action="{{ route('admin.petty-cash.setoran-approval.reject', $s->id) }}" method="POST">
                    @csrf
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alasan Penolakan</label>
                    <textarea name="keterangan_owner" rows="3" required
                        placeholder="Contoh: Nominal cash tidak sesuai dengan fisik..."
                        class="w-full border-gray-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-red-100 focus:border-red-400 bg-gray-50/50 mb-6 transition-all resize-none"></textarea>
                    
                    <div class="flex gap-3">
                        <button type="button"
                            onclick="document.getElementById('reject-modal-{{ $s->id }}').classList.add('hidden')"
                            class="flex-1 px-6 py-3.5 border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] px-6 py-3.5 bg-red-600 text-white rounded-2xl text-sm font-black shadow-xl shadow-red-200 hover:bg-red-700 transition-all">TOLAK</button>
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
