@extends('layouts.admin')

@section('title', 'Angsuran Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Angsuran Pinjaman</h1>
            <p class="text-gray-600 mt-1">Kelola jadwal dan pembayaran angsuran per nasabah</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.pinjaman.angsuran') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="bulanan" {{ request('jenis', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="mingguan" {{ request('jenis') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                    <option value="">Semua Status</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari nasabah</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jatuh Tempo</label>
                <div class="flex gap-2">
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-[#674c1d]">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-[#674c1d]">
                </div>
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="px-6 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-medium">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table: per pinjaman (No, Id, Nasabah, Detail Angsuran, Status Pembayaran, Aksi) -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-linear-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#674c1d] uppercase w-14">No</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#674c1d] uppercase">Id Pinjaman</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase min-w-[400px]">Detail Angsuran</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase min-w-[200px]">Status Pembayaran</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#674c1d] uppercase min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjamanList as $no => $pinjaman)
                    @php
                        $tempos = $jenis === 'bulanan' ? $pinjaman->tempoBulanan : $pinjaman->tempoMingguan;
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-center align-middle text-sm font-semibold text-gray-700 w-14">{{ $pinjamanList->firstItem() + $no }}</td>
                        <td class="px-4 py-3 text-center align-middle">
                            <p class="text-sm font-medium text-gray-900">#{{ $pinjaman->id }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($pinjaman->jenis) }} · {{ $pinjaman->lama_pinjam }} {{ $pinjaman->jenis === 'bulanan' ? 'bulan' : 'minggu' }}</p>
                        </td>
                        <td class="px-4 py-3 text-center align-middle">
                            <p class="font-semibold text-gray-900">{{ $pinjaman->nasabah->user->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $pinjaman->nasabah->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="rounded-xl overflow-hidden shadow-sm border border-[#8b6f2f]/20 bg-linear-to-b from-amber-50/80 to-white">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-linear-to-r from-[#674c1d]/15 to-[#8b6f2f]/10">
                                            <th class="px-3 py-2 text-center font-semibold text-[#674c1d]">No</th>
                                            <th class="px-3 py-2 text-left font-semibold text-[#674c1d]">Jatuh Tempo</th>
                                            <th class="px-3 py-2 text-right font-semibold text-[#674c1d]">Tagihan</th>
                                            <th class="px-3 py-2 text-right font-semibold text-[#674c1d]">Denda</th>
                                            <th class="px-3 py-2 text-right font-semibold text-[#674c1d]">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tempos as $t)
                                        @php 
                                            $isTelat = $t->tgl_jatuh_tempo < now() && $t->status_bayar !== 'lunas'; 
                                            $computedDenda = $t->hitungDenda();
                                            $sisaHari = $t->hitungSisaHari();
                                        @endphp
                                        <tr class="{{ $isTelat ? 'bg-red-100 border-l-4 border-red-500' : 'border-t border-[#8b6f2f]/10 hover:bg-amber-50/50' }} transition-colors align-middle">
                                            <td class="px-3 py-2 text-center align-middle font-medium {{ $isTelat ? 'text-red-800' : 'text-gray-700' }}">#{{ $t->no_urut }}</td>
                                            <td class="px-3 py-2 align-middle {{ $isTelat ? 'text-red-800 font-medium' : 'text-gray-600' }}">
                                                <div>{{ $t->tgl_jatuh_tempo->format('d M Y') }}</div>
                                                @if($t->status_bayar !== 'lunas')
                                                    @if($sisaHari > 0)
                                                        <span class="text-[10px] text-gray-500 font-semibold">(Sisa {{ $sisaHari }} Hari)</span>
                                                    @elseif($sisaHari === 0)
                                                        <span class="text-[10px] text-yellow-600 font-bold">(Jatuh Tempo Hari Ini)</span>
                                                    @else
                                                        <span class="text-[10px] text-red-600 font-bold">(Telat {{ abs($sisaHari) }} Hari)</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right align-middle font-semibold {{ $isTelat ? 'text-red-800' : 'text-[#674c1d]' }}">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-right align-middle font-semibold text-red-600">
                                                {{ $computedDenda > 0 ? '+ Rp ' . number_format($computedDenda, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-right align-middle font-bold text-[#674c1d]">
                                                Rp {{ number_format($t->jumlah_tagihan + $computedDenda, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="rounded-xl overflow-hidden shadow-sm border border-[#8b6f2f]/20 bg-linear-to-b from-amber-50/80 to-white">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-linear-to-r from-[#674c1d]/15 to-[#8b6f2f]/10">
                                            <th class="px-3 py-2 text-right font-semibold text-[#674c1d]">Terbayar</th>
                                            <th class="px-3 py-2 text-center font-semibold text-[#674c1d]">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tempos as $t)
                                        @php
                                            $isTelatStatus = $t->tgl_jatuh_tempo < now() && $t->status_bayar !== 'lunas';
                                            $sc = [
                                                'belum' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Belum'],
                                                'lunas' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'label' => 'Lunas'],
                                                'telat' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'label' => 'Telat'],
                                            ];
                                            $st = $sc[$t->status_bayar] ?? $sc['belum'];
                                        @endphp
                                        <tr class="{{ $isTelatStatus ? 'bg-red-100 border-l-4 border-red-500' : 'border-t border-[#8b6f2f]/10 hover:bg-amber-50/50' }} transition-colors align-middle">
                                            <td class="px-3 py-2 text-right align-middle font-medium {{ $isTelatStatus ? 'text-red-800' : 'text-gray-700' }}">Rp {{ number_format($t->jumlah_terbayar ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-center align-middle">
                                                <span class="inline-flex px-2 py-0.5 rounded-full {{ $st['bg'] }} {{ $st['text'] }} text-xs font-medium">{{ $st['label'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center align-middle">
                            <div class="flex flex-col gap-1.5 items-center">
                                @foreach($tempos as $t)
                                <div class="flex items-center gap-1 w-full justify-center">
                                    <a href="{{ route('admin.pinjaman.detail-angsuran', $t->id) }}?jenis={{ $jenis }}"
                                        class="flex-1 inline-flex items-center justify-center px-2.5 py-1.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition-all text-xs font-medium shadow-sm">
                                        Detail #{{ $t->no_urut }}
                                    </a>
                                    @if($t->status_bayar !== 'lunas')
                                        <button type="button"
                                            onclick="kirimPengingatWa('{{ $jenis }}', '{{ $t->id }}', this)"
                                            class="inline-flex items-center justify-center p-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all text-xs font-medium shadow-sm hover:scale-105"
                                            title="Kirim WA Pengingat">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.717-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.63 1.97 14.16 .947 11.536.947c-5.445 0-9.87 4.37-9.874 9.799-.001 1.77.476 3.498 1.38 5.048l-.995 3.637 3.73-.974zm12.515-5.32c-.3-.15-1.772-.875-2.047-.975-.275-.1-.475-.15-.675.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.267-.467-2.413-1.485-.892-.793-1.493-1.773-1.668-2.073-.175-.3-.018-.463.13-.61.134-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.493-.51-.675-.52-.172-.007-.368-.009-.565-.009-.196 0-.517.074-.787.374-.27.3-1.03 1.01-1.03 2.463 0 1.453 1.056 2.859 1.203 3.059.148.2 2.08 3.175 5.038 4.453.704.304 1.254.485 1.681.62.707.224 1.35.193 1.859.118.568-.084 1.772-.725 2.022-1.425.25-.7.25-1.299.175-1.425-.075-.125-.275-.2-.575-.35z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada pinjaman aktif dengan angsuran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pinjamanList->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pinjamanList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function kirimPengingatWa(jenis, tempoId, btn) {
    if (!confirm('Buka WhatsApp untuk mengirim pesan pengingat jatuh tempo?')) return;
    
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    `;
    
    fetch(`/admin/pinjaman/angsuran/${jenis}/${tempoId}/buat-pengingat`)
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => { throw new Error(data.message || 'Gagal memproses request'); });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                let phone = data.phone;
                // Formating phone number to international standard (62) for wa.me
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('+')) {
                    phone = phone.substring(1);
                }
                
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(data.message)}`;
                window.open(url, '_blank');
            } else {
                alert(data.message || 'Terjadi kesalahan saat membuat pesan.');
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || 'Terjadi kesalahan sistem saat menghubungi server.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
}
</script>
@endpush
