@php
    $currentRoute = request()->route()->getName() ?? '';
    $isActive = function($route) use ($currentRoute) {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (str_contains($currentRoute, $r)) {
                    return true;
                }
            }
            return false;
        }
        return str_contains($currentRoute, $route);
    };
    $mainNavItems = [
        ['label' => 'Dashboard', 'route' => 'nasabah.dashboard', 'active' => $currentRoute === 'nasabah.dashboard', 'href' => route('nasabah.dashboard'), 'icon' => 'dashboard'],
        ['label' => 'Tabungan', 'route' => 'nasabah.tabungan.index', 'active' => $isActive('tabungan'), 'href' => route('nasabah.tabungan.index'), 'icon' => 'tabungan'],
        ['label' => 'Pinjaman', 'route' => 'nasabah.pinjaman.index', 'active' => $isActive('pinjaman'), 'href' => route('nasabah.pinjaman.index'), 'icon' => 'pinjaman'],
        ['label' => 'Guide', 'route' => null, 'active' => false, 'href' => '#', 'icon' => 'guide'],
    ];
    $moreNavItems = [
        ['label' => 'Deposito', 'href' => '#', 'icon' => 'deposito', 'active' => false],
        ['label' => 'Gadai', 'href' => '#', 'icon' => 'gadai', 'active' => false],
        ['label' => 'Setting', 'href' => route('nasabah.setting.index'), 'icon' => 'setting', 'active' => $isActive('setting')],
    ];
    $hasMoreActive = collect($moreNavItems)->contains('active', true);
@endphp

<nav id="bottomNavbar" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] z-50 transition-transform duration-300 pb-[env(safe-area-inset-bottom)]">
    <div class="w-full max-w-6xl mx-auto">
        {{-- Mobile: 4 menu + tombol titik tiga (More) --}}
        <div class="flex md:hidden items-center justify-between px-2 py-2.5">
            @foreach ($mainNavItems as $item)
                <a href="{{ $item['href'] }}" title="{{ $item['label'] }}" class="flex flex-col items-center justify-center gap-0.5 flex-1 min-w-0 py-1 transition-colors duration-200 rounded-lg active:scale-95 {{ $item['active'] ? 'text-[#8b6f2f] bg-amber-50/80' : 'text-gray-600 hover:bg-gray-50' }}">
                    @include('components.nasabah.bottom-navbar-icon', ['icon' => $item['icon']])
                    <span class="text-[10px] sm:text-xs font-medium truncate w-full max-w-[64px] text-center">{{ $item['label'] }}</span>
                </a>
            @endforeach
            {{-- Tombol More (titik 3) --}}
            <div class="relative flex-1 min-w-0 flex justify-center" id="bottomNavMoreWrap">
                <button type="button" id="bottomNavMoreBtn" class="flex flex-col items-center justify-center gap-0.5 w-full py-1 rounded-lg transition-colors duration-200 hover:bg-gray-50 active:scale-95 {{ $hasMoreActive ? 'text-[#8b6f2f] bg-amber-50/80' : 'text-gray-600' }}" aria-expanded="false" aria-haspopup="true">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span class="text-[10px] sm:text-xs font-medium">Lainnya</span>
                </button>
                {{-- Dropdown: Deposito, Gadai, Setting --}}
                <div id="bottomNavMoreMenu" class="absolute bottom-full right-0 mb-1 w-44 rounded-xl bg-white border border-gray-200 shadow-lg py-1 z-50 hidden" role="menu">
                    @foreach ($moreNavItems as $moreItem)
                        <a href="{{ $moreItem['href'] }}" role="menuitem" class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors {{ $moreItem['active'] ? 'text-[#8b6f2f] bg-amber-50' : 'text-gray-700 hover:bg-gray-50' }}">
                            @include('components.nasabah.bottom-navbar-icon', ['icon' => $moreItem['icon'], 'size' => 'w-5 h-5'])
                            <span>{{ $moreItem['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tablet & desktop: satu baris, semua 7 menu --}}
        <div class="hidden md:flex items-center justify-around py-3 px-4">
            @foreach (array_merge($mainNavItems, $moreNavItems) as $item)
                @php $item = is_array($item) ? $item : []; $active = $item['active'] ?? $isActive($item['route'] ?? ''); @endphp
                <a href="{{ $item['href'] ?? '#' }}" title="{{ $item['label'] }}" class="flex flex-col items-center justify-center gap-1 py-2 px-3 transition-colors duration-200 rounded-xl min-w-16 {{ $active ? 'text-[#8b6f2f] bg-amber-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    @include('components.nasabah.bottom-navbar-icon', ['icon' => $item['icon']])
                    <span class="text-xs font-medium whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>

<script>
    (function() {
        var lastScrollTop = 0, scrollTimeout;
        var el = document.getElementById('bottomNavbar');
        if (el) {
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                clearTimeout(scrollTimeout);
                if (scrollTop > lastScrollTop && scrollTop > 100) el.style.transform = 'translateY(100%)';
                else el.style.transform = 'translateY(0)';
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                scrollTimeout = setTimeout(function() { el.style.transform = 'translateY(0)'; }, 150);
            });
        }
        // More menu (titik tiga): toggle + tutup saat klik di luar
        var moreWrap = document.getElementById('bottomNavMoreWrap');
        var moreBtn = document.getElementById('bottomNavMoreBtn');
        var moreMenu = document.getElementById('bottomNavMoreMenu');
        if (moreBtn && moreMenu) {
            moreBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var isOpen = !moreMenu.classList.contains('hidden');
                moreMenu.classList.toggle('hidden', isOpen);
                moreBtn.setAttribute('aria-expanded', !isOpen);
                moreBtn.classList.toggle('text-[#8b6f2f]', !isOpen);
                moreBtn.classList.toggle('bg-amber-50/80', !isOpen);
            });
            document.addEventListener('click', function() {
                moreMenu.classList.add('hidden');
                moreBtn.setAttribute('aria-expanded', 'false');
                moreBtn.classList.remove('text-[#8b6f2f]', 'bg-amber-50/80');
            });
            moreWrap && moreWrap.addEventListener('click', function(e) { e.stopPropagation(); });
        }
    })();
</script>
