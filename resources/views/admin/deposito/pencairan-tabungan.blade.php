@extends('layouts.admin')

@section('title', 'Pencairan Deposito via Tabungan')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Pencairan Deposito – Saldo Tabungan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar permintaan pencairan dana langsung ke tabungan nasabah</p>
        </div>
        <a href="{{ route('admin.deposito.index') }}" class="text-sm text-gray-500 hover:text-[#674c1d]">← Dashboard Deposito</a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-blue-700">{{ $pendingCount }}</p>
            <p class="text-xs text-blue-600 font-semibold mt-1">Menunggu Proses</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-gray-700">{{ $pencairans->total() }}</p>
            <p class="text-xs text-gray-500 font-semibold mt-1">Total Request</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-green-700">{{ $pencairans->where('status','selesai')->count() }}</p>
            <p class="text-xs text-green-600 font-semibold mt-1">Selesai (halaman ini)</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. deposito…"
            class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] outline-none">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="selesai" @selected(request('status')=='selesai')>Selesai</option>
        </select>
        <button type="submit" class="bg-[#674c1d] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#8b6f2f] transition">Filter</button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nasabah</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">No. Deposito</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal Akhir</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Jatuh Tempo</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pencairans as $p)
                @php
                    $dep = $p->deposito;
                    $bungaKotor = $dep ? ($dep->nominal_awal * $dep->bunga * ($dep->tenor?->tenor_hari ?? 365) / 365) : 0;
                    $pajak = $bungaKotor * 0.2;
                    $estimasiCair = $p->nominal_akhir;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $p->nasabah?->user?->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $p->nasabah?->user?->email ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $dep?->nomor_deposito ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <p class="font-bold text-[#674c1d]">Rp {{ number_format($p->nominal_akhir, 0, ',', '.') }}</p>
                        @if($p->is_cancel)
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-0.5">Pembatalan (Pokok Saja)</p>
                        @else
                            <p class="text-xs text-gray-400">Est: Rp {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        {{ $dep?->tgl_jatuh_tempo?->format('d M Y') ?? '-' }}
                        @if($dep?->tgl_jatuh_tempo?->isPast())
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded font-semibold">Jatuh Tempo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($p->status === 'pending')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Pending</span>
                        @elseif($p->status === 'diproses')
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Diproses (Menunggu Admin)</span>
                        @elseif($p->status === 'selesai')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($p->status === 'pending')
                            {{-- NEW: DIRECT APPROVAL (For Admin Operasional) --}}
                            @if(auth()->user()->role === 'admin_operasional')
                            <button type="button"
                                onclick="document.getElementById('modal-finish-{{ $p->id }}').classList.remove('hidden')"
                                class="inline-flex items-center gap-1 bg-[#674c1d] text-white text-xs px-3 py-1.5 rounded-lg hover:bg-[#8b6f2f] transition font-semibold">
                                Proses Langsung
                            </button>
                            @endif

                            {{-- OWNER FLOW: Send Dana to Admin --}}
                            @if(auth()->user()->role === 'admin_utama')
                            <button type="button"
                                onclick="document.getElementById('modal-proses-{{ $p->id }}').classList.remove('hidden')"
                                class="inline-flex items-center gap-1 bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-amber-700 transition font-semibold">
                                Kirim Dana
                            </button>
                            @endif
                        @elseif($p->status === 'diproses')
                            @if(auth()->user()->role === 'admin_operasional')
                            <button type="button"
                                onclick="document.getElementById('modal-finish-{{ $p->id }}').classList.remove('hidden')"
                                class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-blue-700 transition font-semibold">
                                Selesaikan
                            </button>
                            @endif
                        @else
                            <a href="{{ route('admin.deposito.deposito-detail', $p->deposito_id) }}"
                                class="text-xs text-[#674c1d] hover:underline">Lihat Detail</a>
                        @endif

                        {{-- Modal Proses (Owner Only) --}}
                        @if($p->status === 'pending' && auth()->user()->role === 'admin_utama')
                        <div id="modal-proses-{{ $p->id }}" class="hidden fixed inset-0 bg-gray-800/60 z-50 flex items-center justify-center px-4">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left">
                                <h3 class="font-bold text-gray-800 text-lg mb-4 text-center">Kirim Dana ke Admin</h3>

                                <form method="POST" action="{{ route('admin.deposito.pencairan-tabungan.proses', $p->id) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Pilih Admin Penerima</label>
                                        <select name="admin_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                                            <option value="">-- Pilih Admin --</option>
                                            @foreach($admins as $adm)
                                                <option value="{{ $adm->id }}">{{ $adm->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nominal Akhir</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                            <input type="text" readonly value="{{ number_format($p->nominal_akhir, 0, ',', '.') }}"
                                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm font-bold text-gray-500 outline-none">
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan</label>
                                        <textarea name="catatan" rows="3" placeholder="Masukkan catatan..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-[#674c1d]/20"></textarea>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="submit" class="flex-1 bg-amber-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-amber-700 transition shadow-lg">
                                            ✓ Kirim Dana
                                        </button>
                                        <button type="button" onclick="document.getElementById('modal-proses-{{ $p->id }}').classList.add('hidden')"
                                            class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- Modal Finish (Shared) --}}
                        @if($p->status === 'pending' || $p->status === 'diproses')
                        <div id="modal-finish-{{ $p->id }}" class="hidden fixed inset-0 bg-gray-800/60 z-50 flex items-center justify-center px-4">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left">
                                <h3 class="font-bold text-gray-800 text-lg mb-4 text-center">Finalisasi Tabungan</h3>

                                @if($p->status === 'pending')
                                <div class="bg-amber-50 text-amber-800 text-[10px] p-3 rounded-xl mb-4 border border-amber-200 uppercase font-bold tracking-tighter">
                                    Proses Langsung (Gunakan Modal Awal)
                                </div>
                                @endif

                                <!-- Petty Cash Info Card -->
                                @if(auth()->user()->role === 'admin_operasional')
                                <div class="p-4 rounded-xl border-2 border-[#674c1d] bg-[#674c1d]/5 transition-all mb-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">
                                                Saldo Petty Cash (Transfer) Anda</p>
                                            <p class="text-lg font-black text-[#674c1d]">Rp {{ number_format($adminSaldoTransfer, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div id="badge-petty-cash-{{ $p->id }}" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold">
                                                <!-- Dynamic badge content via JS -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="warning-petty-{{ $p->id }}" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl mb-4">
                                    <p class="text-xs text-red-600 font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Saldo Petty Cash Anda tidak mencukupi!
                                    </p>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('admin.deposito.pencairan-tabungan.finish', $p->id) }}" enctype="multipart/form-data" id="form-finish-{{ $p->id }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nominal Akhir</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                            <input type="text" id="nominal_akhir_{{ $p->id }}" name="nominal_akhir" value="{{ round($p->nominal_akhir) }}" oninput="formatCurrency(this); checkPettyCash({{ $p->id }})"
                                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-bold text-[#674c1d] outline-none">
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Upload Bukti</label>
                                        <input type="file" name="foto_bukti_tf" accept="image/*"
                                            class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="submit" id="btn-submit-{{ $p->id }}" class="flex-1 bg-green-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-lg">
                                            ✓ Konfirmasi Selesai
                                        </button>
                                        <button type="button" onclick="document.getElementById('modal-finish-{{ $p->id }}').classList.add('hidden')"
                                            class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <p class="text-sm font-semibold">Belum ada request pencairan ke tabungan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pencairans->hasPages())
    <div class="mt-4">{{ $pencairans->links() }}</div>
    @endif
</div>

<script>
    const adminSaldoTransfer = {{ $adminSaldoTransfer ?? 0 }};

    function formatCurrency(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
    }

    function checkPettyCash(id) {
        const inputNominal = document.getElementById('nominal_akhir_' + id);
        const warningPetty = document.getElementById('warning-petty-' + id);
        const badgePetty = document.getElementById('badge-petty-cash-' + id);
        const btnSubmit = document.getElementById('btn-submit-' + id);

        if (!inputNominal) return;

        const rawVal = inputNominal.value.replace(/[^0-9]/g, '');
        const nominal = parseFloat(rawVal) || 0;
        const isInsufficient = (adminSaldoTransfer < nominal);

        if (badgePetty) {
            if (isInsufficient) {
                badgePetty.className = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700";
                badgePetty.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>Saldo Kurang';
            } else {
                badgePetty.className = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700";
                badgePetty.innerHTML = '<span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>Saldo Cukup';
            }
        }

        if (isInsufficient) {
            if (warningPetty) warningPetty.classList.remove('hidden');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                btnSubmit.innerText = 'Saldo Admin Kurang';
            }
        } else {
            if (warningPetty) warningPetty.classList.add('hidden');
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                btnSubmit.innerText = '✓ Konfirmasi Selesai';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        @foreach($pencairans as $p)
            @if($p->status === 'pending' || $p->status === 'diproses')
                const input = document.getElementById('nominal_akhir_{{ $p->id }}');
                if (input) {
                    formatCurrency(input);
                }
                checkPettyCash({{ $p->id }});
            @endif
        @endforeach
    });

    document.querySelectorAll('[id^="form-finish-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const id = this.id.replace('form-finish-', '');
            const input = document.getElementById('nominal_akhir_' + id);
            if (input) {
                input.value = input.value.replace(/[^0-9]/g, '');
            }
        });
    });
</script>
@endsection
