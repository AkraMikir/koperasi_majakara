@php
    $currentRoute = request()->route()->getName() ?? '';
    $isActive = function ($route) use ($currentRoute) {
        return str_starts_with($currentRoute, $route) ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' :
            'text-gray-700 hover:bg-gray-100';
    };
    $isPinjamanActive = str_starts_with($currentRoute, 'admin.pinjaman');
    $isTabunganActive = str_starts_with($currentRoute, 'admin.tabungan');
    $isLaporanActive = str_starts_with($currentRoute, 'admin.laporan');
    $isActivityLogActive = str_starts_with($currentRoute, 'admin.activity-log');
    $isPettyCashActive = str_starts_with($currentRoute, 'admin.petty-cash');
    $isDepositoActive = str_starts_with($currentRoute, 'admin.deposito');

    // Deposito pencairan badge counts
    $pendingTf = \App\Models\PencairanDeposito::where('jenis_pencairan', 'rek_nasabah')->where('status', 'pending')->count();
    $pendingTab = \App\Models\PencairanDeposito::where('jenis_pencairan', 'saldo_tabungan')->where('status', 'pending')->count();

    // Gunakan variabel dari SidebarComposer yang sudah dicache
    $janjiTemuUnreadCount = $sidebarStats['janjiTemuCount'] ?? 0;
    $pettyCashPendingCount = $sidebarStats['pettyCashCount'] ?? 0;
@endphp

<aside id="adminSidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full">
    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div
            class="flex items-center justify-center h-20 px-6 border-b border-gray-200 bg-linear-to-r from-[#674c1d] to-[#8b6f2f]">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <span class="text-white font-bold text-2xl">K</span>
                </div>
                <div class="text-white">
                    <h1 class="text-lg font-bold font-display">Koperasi Majakara</h1>
                    <p class="text-xs text-white/80">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.dashboard') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Tabungan (expandable) -->
            <div x-data="{ open: {{ $isTabunganActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isTabunganActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Tabungan</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.tabungan.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.tabungan.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.tabungan.pengajuan-setor') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.tabungan.pengajuan-setor') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pengajuan Setoran
                    </a>
                    <a href="{{ route('admin.tabungan.pengajuan-tarik') }}"
                        class="flex items-center px-1 pl-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.tabungan.pengajuan-tarik') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pengajuan Penarikan
                    </a>
                    <a href="{{ route('admin.tabungan.transaksi') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.tabungan.transaksi') && !request('filter') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Semua Transaksi
                    </a>
                    <a href="{{ route('admin.tabungan.transaksi', ['filter' => 'saya']) }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.tabungan.transaksi') && request('filter') == 'saya' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Riwayat Saya
                    </a>
                    <a href="{{ route('admin.tabungan.saldo-nasabah') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.tabungan.saldo-nasabah') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Saldo Nasabah
                    </a>
                </div>
            </div>

            <!-- Pinjaman (expandable) -->
            <div x-data="{ open: {{ $isPinjamanActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPinjamanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Pinjaman</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.pinjaman.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.pinjaman.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pinjaman.pengajuan') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ in_array($currentRoute, ['admin.pinjaman.pengajuan']) || str_contains($currentRoute, 'admin.pinjaman.detail-pengajuan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pengajuan Pinjaman
                    </a>
                    <a href="{{ route('admin.pinjaman.pinjaman-aktif') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.pinjaman.pinjaman-aktif') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pinjaman Aktif
                    </a>
                    <a href="{{ route('admin.pinjaman.pinjaman-lunas') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.pinjaman.pinjaman-lunas') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pinjaman Lunas
                    </a>
                    <a href="{{ route('admin.pinjaman.angsuran') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.pinjaman.angsuran') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Angsuran
                    </a>
                    <a href="{{ route('admin.pinjaman.pembayaran') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.pinjaman.pembayaran') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pembayaran
                    </a>
                    <a href="{{ route('admin.pinjaman.limit.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.pinjaman.limit') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Limit Pinjaman
                    </a>
                </div>
            </div>

            <!-- Deposito (expandable) -->
            <div x-data="{ open: {{ $isDepositoActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isDepositoActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Deposito</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.deposito.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.deposito.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.deposito.pengajuan-list') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.pengajuan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Pengajuan</span>
                        @php $pendingDeposito = \App\Models\PengajuanDeposito::where('status', '1')->count(); @endphp
                        @if($pendingDeposito > 0)
                            <span
                                class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">{{ $pendingDeposito }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.deposito.deposito-list') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.deposito') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Daftar Deposito
                    </a>
                    @isAdminUtama
                    <a href="{{ route('admin.deposito.paket.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.paket') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Paket Deposito
                    </a>
                    @endisAdminUtama
                    <a href="{{ route('admin.deposito.pencairan-tf.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.pencairan-tf') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Pencairan TF</span>
                        @if($pendingTf > 0)
                            <span
                                class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">{{ $pendingTf }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.deposito.pencairan-tabungan.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.pencairan-tabungan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Pencairan Tabungan</span>
                        @if($pendingTab > 0)
                            <span
                                class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">{{ $pendingTab }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.deposito.peringatan.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.deposito.peringatan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <span>Peringatan JT</span>
                        </div>
                        @php 
                            $peringatanCount = \App\Models\DepositoPersiapanCair::whereIn('status', ['tentatif', 'diproses'])->count();
                        @endphp
                        @if($peringatanCount > 0)
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs font-bold rounded-full">{{ $peringatanCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Gadai (expandable) -->
            @php $isGadaiActive = str_starts_with($currentRoute, 'admin.gadai_baru'); @endphp
            <div x-data="{ open: {{ $isGadaiActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isGadaiActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Gadai</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.gadai_baru.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.gadai_baru.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard Gadai
                    </a>
                    <a href="{{ route('admin.gadai_baru.create') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.gadai_baru.create' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Tambah Gadai</span>
                    </a>
                    <a href="{{ route('admin.gadai_baru.storage') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.gadai_baru.storage' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Penyimpanan Gadai</span>
                    </a>
                    <a href="{{ route('admin.gadai_baru.pengajuan.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.gadai_baru.pengajuan.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>Verifikasi Pengajuan</span>
                        @php $pendingGadai = \App\Models\GadaiPengajuan::where('status', 'pending')->count(); @endphp
                        @if($pendingGadai > 0)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">{{ $pendingGadai }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Janji Temu (badge = data baru sejak terakhir dibuka) -->
            <a href="{{ route('admin.janji-temu.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.janji-temu') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg
                 >      
                    @if($janjiTemuUnreadCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">{{ $janjiTemuUnreadCount > 99 ? '99+' : $janjiTemuUnreadCount }}</span>
                        </span>
                    @endif
                </div>
                <span class="font-medium">Janji Temu</span>
            </a>

            {{-- ── PETTY CASH (expandable, role-aware) ── --}}
            <div x-data="{ open: {{ $isPettyCashActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isPettyCashActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3 relative">
                            {{-- Wallet icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            {{-- 
         B                      adge pending --}}
                            @if($pettyCashPendingCount > 0)
                                <span class="absolute -top-1 -right-1 min-w-4 h-4 px-1 bg-amber-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-white font-bold leading-none">{{ $pettyCashPendingCount > 9 ? '9+' : $pettyCashPendingCount }}</span>
                                </span>
                            @endif
                        </div>
                        <span class="font-medium">Petty Cash</span>
                    </div>
                    <div clas
          s                 ="flex items-center gap-2">
                        @if($pettyCashPendingCount > 0)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full {{ $isPettyCashActive ? 'bg-white/20 text-white' : '' }}">
                                {{ $pettyCashPendingCount }}
                            </span>

                           @endif
                         <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">

                    {{-- Owner: Dashboard + Verifikasi + Laporan --}}
                    @isAdminUtama
                    <a href="{{ route('admin.petty-cash.dashboard') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $currentRoute === 'admin.petty-cash.dashboard' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                         <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.petty-cash.penerimaan.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                        {{ str_starts_with($currentRoute, 'admin.petty-cash.penerimaan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Dana ke Admin
                    </a>
                    <a href="{{ route('admin.petty-cash.setoran-approval.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                        {{ str_starts_with($currentRoute, 'admin.petty-cash.setoran-approval') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">

                                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verifikasi Setoran
                        </div
                           >
                        @if($pettyCashPendingCount > 0)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">{{ $pettyCashPendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.petty-cash.laporan') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $currentRoute === 'admin.petty-cash.laporan' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                         <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Laporan
                    </a>
                    @endisAdminUtama

                    {{-- Admin Operasional: Penerimaan + Input Setoran + Setor Kantor --}}
                    @isAdminOperasional
                    <a href="{{ route('admin.petty-cash.dashboard') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $currentRoute === 'admin.petty-cash.dashboard' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                         <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.petty-cash.penerimaan.index') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors
                        {{ str_starts_with($currentRoute, 'admin.petty-cash.penerimaan') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                     
                                           <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Penerimaan Dana
                        </div
 >                          
                        @if($pettyCashPendingCount > 0)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">{{ $pettyCashPendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.petty-cash.setoran-kantor.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
                        {{ str_starts_with($currentRoute, 'admin.petty-cash.setoran-kantor') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                         <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Setor ke Kantor
                    </a>
                    @endisAdminOperasional
                </div>
            </div>

            {{-- ── BUNGA ── --}}
            @php
                $isBungaActive = str_starts_with($currentRoute, 'admin.bunga');
            @endphp
            <div x-data="{ open: {{ $isBungaActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isBungaActive ? 'bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Bunga</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.bunga.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.bunga.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Ringkasan Bunga
                    </a>
                    <a href="{{ route('admin.bunga.pinjaman') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.bunga.pinjaman' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Bunga Pinjaman
                    </a>
                    <a href="{{ route('admin.bunga.deposito') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.bunga.deposito' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Bunga Deposito
                    </a>
                    <a href="{{ route('admin.bunga.gadai') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.bunga.gadai' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Biaya Gadai
                    </a>
                </div>
            </div>

            {{-- ── LAPORAN KEUANGAN ── --}}

            <div x-data="{ open: {{ $isLaporanActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isLaporanActive ? 'bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Laporan Keuangan</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.index' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Daftar Laporan
                    </a>
                    <a href="{{ route('admin.laporan.rekapitulasi') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.rekapitulasi' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Rekapitulasi
                    </a>
                    <a href="{{ route('admin.laporan.tabungan') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.tabungan' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Laporan Tabungan
                    </a>
                    <a href="{{ route('admin.laporan.saldo-tabungan') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.saldo-tabungan' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Saldo Tabungan
                    </a>
                    <a href="{{ route('admin.laporan.pinjaman-aktif') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.pinjaman-aktif' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pinjaman Aktif
                    </a>
                    <a href="{{ route('admin.laporan.angsuran-pinjaman') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.angsuran-pinjaman' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Angsuran Pinjaman
                    </a>
                    <a href="{{ route('admin.laporan.jatuh-tempo') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.jatuh-tempo' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Jatuh Tempo
                    </a>
                    <a href="{{ route('admin.laporan.pengajuan') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ $currentRoute === 'admin.laporan.pengajuan' ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Laporan Pengajuan
                    </a>
                </div>
            </div>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-200"></div>

            <!-- Nasabah -->
            <a href="{{ route('admin.nasabah.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.index') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <span class="font-medium">Nasabah</span>
            </a>

            <!-- Pengajuan Perubahan Data -->
            @php
                $pendingCount = $sidebarStats['perubahanDataCount'] ?? 0;
            @endphp
            <a href="{{ route('admin.nasabah.pending-changes') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.nasabah.pending-changes') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    @if($pendingCount > 0)
                        <span
                            class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">{{ $pendingCount }}</span>
                        </span>
                    @endif
                </div>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Perubahan Data</span>
                    @if($pendingCount > 0)
                        <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </div>
            </a>

            <!-- Master Data -->
            <a href="{{ route('admin.master-data.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.master-data') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                        </path>
                    </svg>
                </div>
                <span class="font-medium">Master Data</span>
            </a>

            <!-- Settings Struk -->
            <a href="{{ route('admin.settings.struk') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive('admin.settings.struk') }}">
                <div class="w-10 h-10 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.
         5                  73 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="font-medium">Settings Struk</span>
            </a>

            <!-- Log Aktivitas (hanya Admin Utama) -->
            @isAdminUtama
            <div x-data="{ open: {{ $isActivityLogActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isActivityLogActive ? 'bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium">Log Aktivitas</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-4 pr-2 pb-2 space-y-1 border-l-2 border-[#8b6f2f]/30 ml-6">
                    <a href="{{ route('admin.activity-log.nasabah') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.activity-log.nasabah') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Log Nasabah
                    </a>
                    <a href="{{ route('admin.activity-log.admin-operasional') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors {{ str_starts_with($currentRoute, 'admin.activity-log.admin-operasional') ? 'bg-[#674c1d]/10 text-[#674c1d] font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Log Admin
                    </a>
                </div>
            </div>
            @endisAdminUtama
        </nav>

        <!-- User Profile Section -->
        <div class="px-4 py-4 border-t border-gray-200">
            <div class="flex items-center px-4 py-3 rounded-xl bg-gray-50">
                <div
                    class="w-10 h-10 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->nama ?? 'A', 0, 1) }}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->nama ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Admin' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>


   <!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-600/50 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>