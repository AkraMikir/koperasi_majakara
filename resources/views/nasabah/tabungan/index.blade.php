@extends('layouts.nasabah')

@section('title', 'Dashboard Tabungan')

@push('styles')
<style>
    /* ===== TAB NAVIGATOR ===== */
    .tab-btn {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #6b7280;
        border-radius: 0.625rem;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: none;
        background: transparent;
        cursor: pointer;
        flex-shrink: 0;
    }
    .tab-btn:hover {
        color: #674c1d;
        background: rgba(103, 76, 29, 0.06);
    }
    .tab-btn.active {
        color: #674c1d;
        background: rgba(103, 76, 29, 0.08);
        font-weight: 700;
    }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0.5rem;
        right: 0.5rem;
        height: 2.5px;
        background: linear-gradient(90deg, #674c1d, #d4af37);
        border-radius: 2px;
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== BADGE ===== */
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.1rem;
        height: 1.1rem;
        padding: 0 0.3rem;
        font-size: 0.6rem;
        font-weight: 800;
        border-radius: 9999px;
        background: linear-gradient(135deg, #d4af37, #8b6f2f);
        color: #fff;
        line-height: 1;
    }

    /* ===== HERO ===== */
    .hero-tabungan {
        background: linear-gradient(135deg, #3a2800 0%, #674c1d 40%, #8b6f2f 70%, #d4af37 100%);
    }
</style>
@endpush

@section('content')
<div class="w-full pb-6">

    {{-- ===== HERO CARD: SALDO ===== --}}
    <div class="hero-tabungan mx-4 mt-4 rounded-2xl px-5 pt-5 pb-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-56 h-56 bg-white/5 rounded-full -mr-24 -mt-24 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full -ml-16 -mb-16 pointer-events-none"></div>

        <div class="relative z-10">
            {{-- Label + Icon --}}
            <div class="flex items-start justify-between mb-2">
                <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Saldo Tabungan</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Nominal --}}
            <h1 class="text-white font-black text-2xl sm:text-4xl tracking-tight mb-1 font-display">
                Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}
            </h1>

            {{-- Saldo Hold --}}
            @if(($tabunganInfo->saldo_hold ?? 0) > 0)
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-0.5 bg-amber-400/90 text-amber-900 rounded text-[10px] font-black uppercase tracking-wider">Tertahan</span>
                    <span class="text-white/80 text-sm font-semibold">Rp {{ number_format($tabunganInfo->saldo_hold, 0, ',', '.') }}</span>
                </div>
            @endif

            <p class="text-white/60 text-xs mb-5">Status: <span class="text-white/90 font-semibold">{{ $tabunganInfo->status ?? 'Aktif' }}</span></p>

            {{-- Quick Actions: Nabung & Tarik --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}"
                   class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-none">Nabung</p>
                        <p class="text-white/60 text-xs mt-0.5">Tambah Tabungan</p>
                    </div>
                </a>
                <a href="{{ route('nasabah.tabungan.penarikan') }}"
                   class="flex items-center gap-3 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/25 rounded-2xl px-4 py-3 transition-all active:scale-95">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-none">Tarik</p>
                        <p class="text-white/60 text-xs mt-0.5">Penarikan Tabungan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- ===== UNIFIED TAB AREA ===== --}}
    <div class="mx-4 mt-5 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- === TAB BAR === --}}
            <div class="border-b border-gray-100 px-3 pt-1 overflow-x-auto">
                <div class="flex gap-1 min-w-max pb-px">
                    {{-- Tab: Riwayat Transaksi --}}
                    <button type="button" class="tab-btn active" onclick="switchTab('trans', this)" id="tab-btn-trans">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Transaksi
                    </button>

                    {{-- Tab: Setoran --}}
                    <button type="button" class="tab-btn" onclick="switchTab('setor', this)" id="tab-btn-setor">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Setoran
                        @if($pengajuanSetors->total() > 0)
                            <span class="tab-badge">{{ $pengajuanSetors->total() }}</span>
                        @endif
                    </button>

                    {{-- Tab: Penarikan --}}
                    <button type="button" class="tab-btn" onclick="switchTab('tarik', this)" id="tab-btn-tarik">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Penarikan
                        @if($pengajuanTariks->total() > 0)
                            <span class="tab-badge">{{ $pengajuanTariks->total() }}</span>
                        @endif
                    </button>

                    {{-- Tab: Janji Temu --}}
                    <button type="button" class="tab-btn" onclick="switchTab('jt', this)" id="tab-btn-jt">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Janji Temu
                        @if($janjiTemuTabungans->total() > 0)
                            <span class="tab-badge">{{ $janjiTemuTabungans->total() }}</span>
                        @endif
                    </button>
                </div>
            </div>

            {{-- === TAB CONTENT AREA === --}}

            {{-- PANE: Riwayat Transaksi --}}
            <div id="pane-trans" class="tab-pane active" data-container="trans-container">
                <div class="flex items-center justify-between px-5 pt-4 pb-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#674c1d] font-display">Riwayat Transaksi</p>
                        <p class="text-xs text-gray-400 mt-0.5">Semua setoran & penarikan tercatat</p>
                    </div>
                    <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}"
                       class="text-xs font-bold text-[#674c1d] hover:text-[#8b6f2f] flex items-center gap-1 transition-colors">
                        Nabung
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div id="trans-container">
                    <div class="ajax-content">
                        @include('nasabah.tabungan.partials._table_trans')
                    </div>
                </div>
            </div>

            {{-- PANE: Status Setoran --}}
            <div id="pane-setor" class="tab-pane" data-container="setor-container">
                <div class="flex items-center justify-between px-5 pt-4 pb-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#674c1d] font-display">Status Pengajuan Setoran</p>
                        <p class="text-xs text-gray-400 mt-0.5">Riwayat transfer & setoran yang diajukan</p>
                    </div>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}"
                       class="text-xs font-bold text-[#674c1d] hover:text-[#8b6f2f] flex items-center gap-1 transition-colors">
                        Lihat Semua
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div id="setor-container">
                    <div class="ajax-content">
                        @include('nasabah.tabungan.partials._table_setor')
                    </div>
                </div>
            </div>

            {{-- PANE: Status Penarikan --}}
            <div id="pane-tarik" class="tab-pane" data-container="tarik-container">
                <div class="flex items-center justify-between px-5 pt-4 pb-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#674c1d] font-display">Status Pengajuan Penarikan</p>
                        <p class="text-xs text-gray-400 mt-0.5">Riwayat penarikan yang diajukan</p>
                    </div>
                    <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}"
                       class="text-xs font-bold text-[#674c1d] hover:text-[#8b6f2f] flex items-center gap-1 transition-colors">
                        Lihat Semua
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div id="tarik-container">
                    <div class="ajax-content">
                        @include('nasabah.tabungan.partials._table_tarik')
                    </div>
                </div>
            </div>

            {{-- PANE: Janji Temu --}}
            <div id="pane-jt" class="tab-pane" data-container="jt-container">
                <div class="flex items-center justify-between px-5 pt-4 pb-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#674c1d] font-display">Riwayat Janji Temu</p>
                        <p class="text-xs text-gray-400 mt-0.5">Jadwal kunjungan ke koperasi</p>
                    </div>
                    <a href="{{ route('nasabah.tabungan.janji-temu') }}"
                       class="text-xs font-bold text-[#674c1d] hover:text-[#8b6f2f] flex items-center gap-1 transition-colors">
                        Buat Baru
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div id="jt-container">
                    <div class="ajax-content">
                        @include('nasabah.tabungan.partials._table_jt')
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== TAB SWITCHER =====
    function switchTab(tabKey, btnEl) {
        // Hide all panes
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

        // Show target pane
        document.getElementById('pane-' + tabKey)?.classList.add('active');
        btnEl.classList.add('active');
    }

    // Expose globally so onclick can find it
    window.switchTab = switchTab;

    // ===== AJAX PAGINATION =====
    function handleAjaxPagination(containerId, sectionName) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.addEventListener('click', function (e) {
            const link = e.target.closest('nav.flex a');
            if (link && !link.hasAttribute('data-no-ajax')) {
                e.preventDefault();
                loadSection(containerId, sectionName, link.href);
            }
        });
    }

    function loadSection(containerId, sectionName, url) {
        const container = document.getElementById(containerId);
        const contentArea = container.querySelector('.ajax-content');

        container.style.opacity = '0.6';
        container.style.pointerEvents = 'none';

        const ajaxUrl = new URL(url);
        ajaxUrl.searchParams.append('section', sectionName);

        fetch(ajaxUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error(); return r.text(); })
            .then(html => { contentArea.innerHTML = html; })
            .catch(() => { window.location.href = url; })
            .finally(() => {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            });
    }

    handleAjaxPagination('trans-container', 'trans');
    handleAjaxPagination('setor-container', 'setor');
    handleAjaxPagination('tarik-container', 'tarik');
    handleAjaxPagination('jt-container', 'jt');
});
</script>
@endsection
