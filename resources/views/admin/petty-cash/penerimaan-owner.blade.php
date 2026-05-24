@extends('layouts.admin')

@section('title', 'Kirim Dana ke Admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display transition-all">Kirim Dana ke Admin</h1>
            <p class="text-gray-600 mt-1">Kelola distribusi modal operasional teller</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.petty-cash.dashboard') }}"
               class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                ← Dashboard
            </a>
            <button onclick="openKirimModal()" 
                    class="px-6 py-2 bg-[#674c1d] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#674c1d]/20 hover:bg-[#4a3514] transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                Kirim Dana Baru
            </button>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" id="stats-container">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-600">
                <i class="fas fa-history text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Kirim</p>
                <p class="text-2xl font-bold text-gray-900" id="stat-total">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-gray-900" id="stat-pending">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved</p>
                <p class="text-2xl font-bold text-gray-900" id="stat-approved">{{ $stats['approved'] }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 text-red-600">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rejected</p>
                <p class="text-2xl font-bold text-gray-900" id="stat-rejected">{{ $stats['rejected'] }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#674c1d]/5 rounded-xl flex items-center justify-center text-[#674c1d]">
                <i class="fas fa-wallet text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Nominal</p>
                <p class="text-xl font-bold text-gray-900" id="stat-nominal">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Admin</label>
                <select name="admin_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#674c1d]">
                    <option value="">Semua Admin</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-[#674c1d]">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved (ACC)</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Range Tanggal</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="tgl_dari" class="w-full border-gray-200 rounded-xl text-xs focus:ring-[#674c1d]">
                    <input type="date" name="tgl_sampai" class="w-full border-gray-200 rounded-xl text-xs focus:ring-[#674c1d]">
                </div>
            </div>
            <div>
                <button type="button" onclick="resetFilters()" class="w-full py-2.5 text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors border border-gray-200">
                    <i class="fas fa-sync-alt mr-2 text-xs"></i>
                    Reset Filter
                </button>
            </div>
        </form>
    </div>

    {{-- History Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden relative">
        {{-- Loading Overlay --}}
        <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 hidden flex items-center justify-center transition-all">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 border-4 border-[#674c1d] border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-bold text-[#674c1d] mt-3 uppercase tracking-widest">Memuat Data...</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Admin / ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#674c1d] uppercase">Via</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[#674c1d] uppercase">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="penerimaan-body">
                    @include('admin.petty-cash.partials._penerimaan_table', ['pengiriman' => $pengiriman])
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100" id="pagination-container">
            {{ $pengiriman->links() }}
        </div>
    </div>
</div>

{{-- MODAL CREATE - FIXED GLASSMORPHISM & CONTRAST --}}
<div id="modalKirim" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- High-quality Backdrop --}}
    <div onclick="closeKirimModal()" class="fixed inset-0 bg-gray-900/40 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Helper for vertical centering --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.2)] transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/50 z-[101]">
            {{-- Premium Header --}}
            <div class="bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] px-10 py-8 relative overflow-hidden">
                {{-- Decorative light --}}
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-white font-display tracking-tight">Kirim Dana Operasional</h3>
                        <p class="text-white/80 text-sm mt-1.5 font-medium tracking-wide">Pindahkan modal kerja ke Admin Teller</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/10 rounded-xl p-2 backdrop-blur-sm border border-white/20">
                            <p class="text-[10px] font-bold text-white/60 uppercase">Dompet Utama Anda</p>
                            <p class="text-lg font-black text-white">Rp {{ number_format($saldoCash + $saldoTf, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.petty-cash.penerimaan.store') }}" method="POST" enctype="multipart/form-data" class="px-10 py-10 space-y-8" id="formKirimDana">
                @csrf
                
                <div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-layer-group text-lg"></i>
                        </div>
                        @php
                            $detModal = (object)($sourceDetails['other'] ?? ['total_cash' => 0, 'total_tf' => 0]);
                            $totalModal = $detModal->total_cash + $detModal->total_tf;
                        @endphp
                        <input type="text" readonly 
                               value="Modal Awal (Tersedia: Rp {{ number_format($totalModal, 0, ',', '.') }})"
                               class="block w-full pl-12 pr-4 py-4 text-base font-bold text-[#674c1d] border-gray-200 rounded-[1.25rem] bg-gray-50 cursor-not-allowed">
                        <input type="hidden" name="sumber" id="select_sumber" value="other" 
                               data-cash="{{ (float)$detModal->total_cash }}" 
                               data-tf="{{ (float)$detModal->total_tf }}">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-800 uppercase tracking-[0.15em] mb-3">Admin Penerima <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#674c1d] transition-colors">
                            <i class="fas fa-user-circle text-lg"></i>
                        </div>
                        <select name="admin_id" required class="block w-full pl-12 pr-4 py-4 text-base font-semibold border-gray-300 rounded-[1.25rem] focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] transition-all bg-gray-50/50">
                            <option value="">Pilih Admin Operasional...</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">
                                    {{ $admin->nama }} ({{ $admin->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-2">
                    <div>
                        <label class="block text-xs font-black text-gray-800 uppercase tracking-[0.15em] mb-3 flex items-center justify-between">
                            <span>Saldo Transfer (Bank)</span>
                            <span class="text-[10px] text-blue-600 font-bold" id="label_max_tf">Max: 0</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-500 font-black text-xs">RP</div>
                            <input type="number" name="nominal_tf" id="input_tf" oninput="calculateTotal()"
                                   max="0"
                                   class="block w-full pl-12 pr-4 py-4 text-lg font-black text-gray-900 border-gray-300 rounded-[1.25rem] focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] transition-all bg-gray-50/50"
                                   placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-800 uppercase tracking-[0.15em] mb-3 flex items-center justify-between">
                            <span>Saldo Tunai (Cash)</span>
                            <span class="text-[10px] text-green-600 font-bold" id="label_max_cash">Max: 0</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-500 font-black text-xs">RP</div>
                            <input type="number" name="nominal_cash" id="input_cash" oninput="calculateTotal()"
                                   max="0"
                                   class="block w-full pl-12 pr-4 py-4 text-lg font-black text-gray-900 border-gray-300 rounded-[1.25rem] focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] transition-all bg-gray-50/50"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-[#674c1d]/5 to-transparent border border-[#674c1d]/10 p-6 rounded-[1.5rem] flex items-center justify-between">
                    <div>
                        <p class="text-[0.6rem] font-black text-[#674c1d] uppercase tracking-[0.2em]">Total yang Dikirimkan</p>
                        <p class="text-3xl font-black text-[#674c1d] mt-1" id="display_total">Rp 0</p>
                    </div>
                    <div class="w-12 h-12 bg-[#674c1d] rounded-2xl flex items-center justify-center text-white shadow-xl shadow-[#674c1d]/30">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Bukti Transfer</label>
                        <label class="flex flex-col items-center justify-center w-full px-4 py-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-[1.25rem] cursor-pointer hover:bg-gray-100 transition-all group">
                            <i class="fas fa-cloud-upload-alt text-gray-400 group-hover:text-[#674c1d] mb-1"></i>
                            <span class="text-[10px] font-bold text-gray-500 group-hover:text-gray-700">Pilih Screenshot</span>
                            <input type="file" name="bukti_tf" accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div>
                        <label class="block text-[0.65rem] font-black text-gray-500 uppercase tracking-widest mb-2">Upload Foto Cash</label>
                        <label class="flex flex-col items-center justify-center w-full px-4 py-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-[1.25rem] cursor-pointer hover:bg-gray-100 transition-all group">
                            <i class="fas fa-camera text-gray-400 group-hover:text-[#674c1d] mb-1"></i>
                            <span class="text-[10px] font-bold text-gray-500 group-hover:text-gray-700">Pilih Foto Uang</span>
                            <input type="file" name="foto_cash" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-black text-gray-800 uppercase tracking-[0.15em] mb-3">Catatan (Opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Tambahkan catatan jika diperlukan..."
                              class="block w-full px-5 py-4 text-sm font-medium border-gray-300 rounded-[1.25rem] focus:ring-4 focus:ring-[#674c1d]/10 focus:border-[#674c1d] transition-all bg-gray-50/50 resize-none"></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-[3] px-8 py-4.5 bg-[#674c1d] text-white rounded-[1.25rem] text-sm font-black shadow-2xl shadow-[#674c1d]/30 hover:bg-[#4a3514] transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-paper-plane mr-2"></i>
                        PROSES PENGIRIMAN
                    </button>
                    <button type="button" onclick="closeKirimModal()" class="flex-[1] px-4 py-4.5 bg-gray-100 text-gray-700 rounded-[1.25rem] text-xs font-bold hover:bg-gray-200 transition-all uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const filterForm = document.getElementById('filter-form');
    const tableBody = document.getElementById('penerimaan-body');
    const statsContainer = document.getElementById('stats-container');
    const paginationContainer = document.getElementById('pagination-container');
    const loadingOverlay = document.getElementById('loading-overlay');

    // 🔄 Instant Filter Logic
    filterForm.querySelectorAll('select, input').forEach(input => {
        input.addEventListener('change', fetchFilteredData);
    });

    function fetchFilteredData() {
        showLoading(true);
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        const url = `{{ route('admin.petty-cash.penerimaan.create') }}?${params}`;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            updateStats(data.stats);
            // Re-bind pagination links
            bindPaginationLinks();
        })
        .catch(error => console.error('Error:', error))
        .finally(() => showLoading(false));
    }

    function updateStats(stats) {
        document.getElementById('stat-total').innerText = stats.total;
        document.getElementById('stat-pending').innerText = stats.pending;
        document.getElementById('stat-approved').innerText = stats.approved;
        document.getElementById('stat-rejected').innerText = stats.rejected;
        document.getElementById('stat-nominal').innerText = 'Rp ' + parseInt(stats.total_nominal).toLocaleString('id-ID');
    }

    function bindPaginationLinks() {
        paginationContainer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                showLoading(true);
                const url = this.getAttribute('href');
                
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    updateStats(data.stats);
                    bindPaginationLinks();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .finally(() => showLoading(false));
            });
        });
    }

    function resetFilters() {
        filterForm.reset();
        fetchFilteredData();
    }

    function showLoading(show) {
        if (show) {
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        } else {
            loadingOverlay.classList.add('hidden');
            loadingOverlay.classList.remove('flex');
        }
    }

    // 🟢 Modal Logic
    function openKirimModal() {
        const modal = document.getElementById('modalKirim');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateMaxValues(); // Auto initialize max values for Modal Awal
    }

    function closeKirimModal() {
        const modal = document.getElementById('modalKirim');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function calculateTotal() {
        const tf = parseInt(document.getElementById('input_tf').value) || 0;
        const cash = parseInt(document.getElementById('input_cash').value) || 0;
        const total = tf + cash;
        document.getElementById('display_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function updateMaxValues() {
        const sourceInput = document.getElementById('select_sumber');
        
        const maxCash = parseFloat(sourceInput.getAttribute('data-cash')) || 0;
        const maxTf = parseFloat(sourceInput.getAttribute('data-tf')) || 0;
        
        const inputCash = document.getElementById('input_cash');
        const inputTf = document.getElementById('input_tf');
        const labelMaxCash = document.getElementById('label_max_cash');
        const labelMaxTf = document.getElementById('label_max_tf');
        
        inputCash.max = maxCash;
        inputTf.max = maxTf;
        
        labelMaxCash.innerText = 'Max: ' + maxCash.toLocaleString('id-ID');
        labelMaxTf.innerText = 'Max: ' + maxTf.toLocaleString('id-ID');
        
        // Reset values if they exceed new max
        if (parseFloat(inputCash.value) > maxCash) inputCash.value = maxCash;
        if (parseFloat(inputTf.value) > maxTf) inputTf.value = maxTf;
        
        calculateTotal();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeKirimModal();
        }
    });

    // Anti Double Submit
    document.getElementById('formKirimDana').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) {
            e.preventDefault();
            return;
        }
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...';
    });

    // Image Upload Feedback
    document.querySelector('input[name="bukti_tf"]').addEventListener('change', function(e) {
        const span = this.parentElement.querySelector('span');
        if(this.files && this.files[0]) {
            span.textContent = this.files[0].name;
            span.classList.add('text-[#674c1d]');
        } else {
            span.textContent = 'Pilih Screenshot';
            span.classList.remove('text-[#674c1d]');
        }
    });

    document.querySelector('input[name="foto_cash"]').addEventListener('change', function(e) {
        const span = this.parentElement.querySelector('span');
        if(this.files && this.files[0]) {
            span.textContent = this.files[0].name;
            span.classList.add('text-[#674c1d]');
        } else {
            span.textContent = 'Pilih Foto Uang';
            span.classList.remove('text-[#674c1d]');
        }
    });

    // Initialize pagination binding
    document.addEventListener('DOMContentLoaded', bindPaginationLinks);
</script>
@endpush
@endsection
