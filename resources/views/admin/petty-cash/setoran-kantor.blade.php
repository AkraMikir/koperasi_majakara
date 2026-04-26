@extends('layouts.admin')

@section('title', 'Setor Cash ke Kantor')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Setor Cash ke Kantor</h1>
            <p class="text-gray-600 mt-1">Kirim rekap setoran nasabah hari ini ke Owner untuk diverifikasi</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl px-5 py-3 text-white shadow-sm border border-green-500/30">
                <p class="text-[10px] text-white/70 uppercase font-bold tracking-wider">💵 Saldo Cash Saku</p>
                <p class="text-xl font-bold">Rp {{ number_format($saldoCash, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl px-5 py-3 text-white shadow-sm border border-blue-500/30">
                <p class="text-[10px] text-white/70 uppercase font-bold tracking-wider">🏦 Saldo TF Rekening</p>
                <p class="text-xl font-bold">Rp {{ number_format($saldoTransfer, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Ringkasan + Form (2/3) --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs text-green-700 font-medium uppercase">Total Cash Hari Ini</p>
                    <p class="text-2xl font-bold text-green-800 mt-1">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">Uang fisik di tangan</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs text-blue-700 font-medium uppercase">Total Transfer</p>
                    <p class="text-2xl font-bold text-blue-800 mt-1">Rp {{ number_format($totalTf, 0, ',', '.') }}</p>
                    <p class="text-xs text-blue-600 mt-1">Via transfer bank</p>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex space-x-2 border-b border-gray-200 mb-4">
                <button type="button" onclick="switchTab('transaksi')" id="tab-transaksi" class="px-4 py-2 font-semibold text-[#674c1d] border-b-2 border-[#674c1d]">Dari Transaksi Pending</button>
                <button type="button" onclick="switchTab('manual')" id="tab-manual" class="px-4 py-2 font-semibold text-gray-500 border-b-2 border-transparent hover:text-[#674c1d]">Setor Manual</button>
            </div>

            <form action="{{ route('admin.petty-cash.setoran-kantor.store') }}" method="POST" enctype="multipart/form-data" id="form-setor">
                @csrf
                <input type="hidden" name="tipe_setoran" id="tipe_setoran" value="transaksi">

                {{-- Mode Transaksi --}}
                <div id="mode-transaksi" class="space-y-5">
                    {{-- Tabel Potongan Data --}}
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-bold text-[#674c1d] uppercase">Pilih Data Potongan</h2>
                                <p class="text-xs text-gray-500 mt-0.5"><span id="count-checked">{{ $transaksiPending->count() }}</span> dari {{ $transaksiPending->count() }} dipilih</p>
                            </div>
                            <span class="font-bold text-[#674c1d]" id="total-checked">Total: Rp {{ number_format($transaksiPending->sum('nominal'), 0, ',', '.') }}</span>
                        </div>

                        @if($transaksiPending->isEmpty())
                        <div class="p-12 text-center text-gray-500">
                            <p class="font-medium">Tidak ada transaksi yang perlu disetor</p>
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-center"><input type="checkbox" id="check-all" checked class="rounded text-[#674c1d]"></th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Nasabah</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Nominal</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Via</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($transaksiPending as $t)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="transaksi_ids[]" value="{{ $t->id }}" data-nominal="{{ $t->nominal }}" checked class="transaksi-check rounded text-[#674c1d]">
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-gray-900">{{ $t->nasabah->user->nama ?? '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-[#674c1d]">
                                            Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-1 bg-gray-100 rounded-lg text-xs">{{ $t->jnsVia?->nama ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Mode Manual --}}
                <div id="mode-manual" class="space-y-5 hidden">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                        <h2 class="text-sm font-bold text-[#674c1d] uppercase mb-4">Input Setor Manual</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Setor Cash (Rp)</label>
                                <input type="number" name="manual_cash" value="0" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]" min="0">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Setor Transfer (Rp)</label>
                                <input type="number" name="manual_tf" value="0" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]" min="0">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Masukkan nominal manual jika tidak ada data potongan atau ingin menyetor sisa saldo saku.</p>
                    </div>
                </div>

                {{-- Form Konfirmasi Bersama --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mt-5">
                    <div class="space-y-5">
                        {{-- Upload Foto --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Bukti Setoran ke Kantor</label>
                            <input type="file" name="foto_setoran" accept="image/*" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"/>
                        </div>

                        {{-- Checkbox Sudah Setor Fisik --}}
                        <div>
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer">
                                <input type="checkbox" name="sudah_setor_fisik" value="1" class="mt-0.5 text-[#674c1d] rounded" required>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Sudah Disetor Fisik ke Kantor</p>
                                    <p class="text-xs text-gray-500">Centang ini jika sudah menyerahkan fisik</p>
                                </div>
                            </label>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="keterangan_admin" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-[#674c1d] text-white rounded-xl font-bold text-sm hover:bg-[#4a3514] transition-colors">
                            Kirim Setoran ke Owner
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Riwayat Setoran (1/3) --}}
        <div>
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <h2 class="text-sm font-bold text-[#674c1d] uppercase">Riwayat Setoran</h2>
                </div>
                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($riwayatSetoran as $s)
                    <div class="p-4">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <p class="font-semibold text-gray-900 text-sm">{{ $s->tgl_setoran->format('d M Y') }}</p>
                            @if($s->status === 'pending')
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs">Menunggu Owner</span>
                            @elseif($s->status === 'approved_owner')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">✓ Disetujui Owner</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs">Ditolak Owner</span>
                            @endif
                        </div>
                        <p class="font-bold text-[#674c1d] text-sm">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $s->jumlah_nasabah }} nasabah</p>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        <p>Belum ada riwayat setoran</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        document.getElementById('tipe_setoran').value = tab;
        
        const tabTransaksi = document.getElementById('tab-transaksi');
        const tabManual = document.getElementById('tab-manual');
        const modeTransaksi = document.getElementById('mode-transaksi');
        const modeManual = document.getElementById('mode-manual');
        
        if (tab === 'transaksi') {
            tabTransaksi.className = 'px-4 py-2 font-semibold text-[#674c1d] border-b-2 border-[#674c1d]';
            tabManual.className = 'px-4 py-2 font-semibold text-gray-500 border-b-2 border-transparent hover:text-[#674c1d]';
            modeTransaksi.classList.remove('hidden');
            modeManual.classList.add('hidden');
        } else {
            tabManual.className = 'px-4 py-2 font-semibold text-[#674c1d] border-b-2 border-[#674c1d]';
            tabTransaksi.className = 'px-4 py-2 font-semibold text-gray-500 border-b-2 border-transparent hover:text-[#674c1d]';
            modeManual.classList.remove('hidden');
            modeTransaksi.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const checks = document.querySelectorAll('.transaksi-check');
        const totalChecked = document.getElementById('total-checked');
        const countChecked = document.getElementById('count-checked');

        function calculateTotal() {
            let total = 0;
            let count = 0;
            checks.forEach(check => {
                if (check.checked) {
                    total += parseFloat(check.dataset.nominal) || 0;
                    count++;
                }
            });
            
            if (totalChecked) {
                totalChecked.textContent = 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(total);
            }
            if (countChecked) {
                countChecked.textContent = count;
            }
            if (checkAll) {
                checkAll.checked = count === checks.length && checks.length > 0;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checks.forEach(check => {
                    check.checked = this.checked;
                });
                calculateTotal();
            });
        }

        checks.forEach(check => {
            check.addEventListener('change', calculateTotal);
        });

        const formSetor = document.getElementById('form-setor');
        if (formSetor) {
            formSetor.addEventListener('submit', function(e) {
                const fileInput = this.querySelector('input[name="foto_setoran"]');
                if (fileInput && fileInput.files && fileInput.files[0]) {
                    if (fileInput.files[0].size > 5 * 1024 * 1024) {
                        e.preventDefault();
                        alert('Ukuran file foto setoran terlalu besar. Maksimal 5MB.');
                        return;
                    }
                }
            });
        }
    });
</script>
@endpush
