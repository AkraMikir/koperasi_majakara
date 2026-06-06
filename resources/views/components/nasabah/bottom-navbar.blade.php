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
    $navActive = function($route) use ($currentRoute, $isActive) {
        if ($route === 'dashboard') return $currentRoute === 'nasabah.dashboard';
        if ($route === 'deposito') return str_starts_with($currentRoute, 'nasabah.deposito');
        return $isActive($route);
    };
    // Icon: Tabungan=dollar, Pinjaman=tangan+duit, Guide=buku terbuka, Deposito=chart naik, Gadai=gembok
    $allNavItems = [
        ['key' => 'dashboard', 'route' => 'nasabah.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['key' => 'tabungan', 'route' => 'nasabah.tabungan.index', 'label' => 'Tabungan', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['key' => 'pinjaman', 'route' => 'nasabah.pinjaman.index', 'label' => 'Pinjaman', 'icon' => 'M2 10a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zm0 0V6a2 2 0 012-2h14a2 2 0 012 2v4M6 12h.01M10 12h.01M14 12h.01M18 12h.01'],
        ['key' => 'guide', 'route' => 'nasabah.guide', 'label' => 'Guide', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['key' => 'deposito', 'route' => 'nasabah.deposito.index', 'label' => 'Deposito', 'icon' => 'M2 19l10-10 4 4 6-6m0 0v6m0-6h6'],
        ['key' => 'gadai', 'route' => 'nasabah.gadai_baru.index', 'label' => 'Gadai', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
        ['key' => 'setting', 'route' => 'nasabah.setting.index', 'label' => 'Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ];
    $arcItems = array_values(array_filter($allNavItems, fn($i) => !in_array($i['key'], ['dashboard', 'setting'])));
    // Posisi arc: dari kiri ke kanan = Tabungan, Pinjaman, Guide, Deposito, Gadai (sama seperti navbar desktop)
    $arcPositions = [
        ['x' => -69, 'y' => -12],  // 0 Tabungan (paling kiri)
        ['x' => -45, 'y' => -54],  // 1 Pinjaman
        ['x' => 0, 'y' => -70],    // 2 Guide (tengah atas)
        ['x' => 45, 'y' => -54],   // 3 Deposito
        ['x' => 69, 'y' => -12],   // 4 Gadai (paling kanan)
    ];
    $nasabah = auth()->user() ? auth()->user()->nasabah : null;
    $bankInfo = $nasabah ? app(\App\Services\BankAccessService::class)->checkPremiumAccess($nasabah->id) : null;
    $isRestricted = $bankInfo && !$bankInfo['allowed'];
    $isVerified = auth()->check() && auth()->user()->verified !== null;
@endphp

<nav id="bottomNavbar" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-t border-gray-200/80 shadow-[0_-4px_20px_rgba(103,76,29,0.08)] z-50 transition-transform duration-300">
    <div class="w-full px-2">
        {{-- Desktop: full 7-item nav dengan sliding indicator --}}
        <div id="navBarInner" class="hidden md:flex relative items-end justify-around py-2">
            <div id="navSlider" class="absolute rounded-2xl bg-[#8b6f2f]/14 shadow-[0_4px_14px_rgba(139,111,47,0.2)] pointer-events-none z-0"
                 style="height: 44px; width: 48px; left: 0; bottom: 2rem; transition: left 0.4s cubic-bezier(0.34, 1.2, 0.64, 1), width 0.4s cubic-bezier(0.34, 1.2, 0.64, 1);"></div>
            @foreach($allNavItems as $item)
            @php 
                $active = $navActive($item['key']); 
                $isRestrictedFeature = in_array($item['key'], ['tabungan', 'pinjaman', 'deposito', 'gadai']);
                
                $isLocked = false;
                $lockReason = '';
                if ($isRestrictedFeature) {
                    if (!$isVerified) {
                        $isLocked = true;
                        $lockReason = 'Akun Anda belum diverifikasi oleh admin.';
                    } elseif (in_array($item['key'], ['pinjaman', 'deposito', 'gadai']) && $isRestricted) {
                        $isLocked = true;
                        $lockReason = $bankInfo['reason'];
                    }
                }
            @endphp
            @if($isLocked)
            <div class="nav-item group relative z-10 flex flex-col items-center justify-end min-w-[48px] px-1 py-2 rounded-2xl text-gray-400 opacity-40 cursor-not-allowed"
                 data-nav-key="{{ $item['key'] }}"
                 title="{{ $lockReason }}">
                <span class="flex items-center justify-center w-12 h-10 rounded-2xl relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"></path></svg>
                    <!-- Small lock overlay -->
                    <span class="absolute -top-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </span>
                </span>
                <span class="text-xs font-medium mt-1.5 block text-gray-400">{{ $item['label'] }}</span>
            </div>
            @else
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
               class="nav-item group relative z-10 flex flex-col items-center justify-end min-w-[48px] px-1 py-2 rounded-2xl transition-colors duration-200 {{ $active ? 'text-[#8b6f2f]' : 'text-gray-500 hover:text-gray-700' }}"
               data-nav-key="{{ $item['key'] }}"
               @if($active) data-nav-active="1" @endif>
                <span class="flex items-center justify-center w-12 h-10 rounded-2xl transition-all duration-300 ease-out {{ $active ? 'scale-110 -translate-y-1.5' : 'group-hover:scale-105 group-hover:-translate-y-0.5' }}">
                    <svg class="w-6 h-6 transition-transform duration-300 {{ $active ? 'scale-105' : '' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"></path></svg>
                </span>
                <span class="text-xs font-medium mt-1.5 block transition-colors duration-200 {{ $active ? 'text-[#8b6f2f] font-semibold' : '' }}">{{ $item['label'] }}</span>
            </a>
            @endif
            @endforeach
        </div>

        {{-- Mobile: 3 item (Dashboard | Burger | Settings) --}}
        <div class="md:hidden flex items-center justify-around py-3">
            <a href="{{ route('nasabah.dashboard') }}" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-[#8b6f2f] transition-colors {{ $currentRoute === 'nasabah.dashboard' ? 'text-[#8b6f2f]' : '' }}">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-xs font-medium">Dashboard</span>
            </a>
            <button type="button" id="burgerMenuBtn" class="flex flex-col items-center gap-0.5 text-[#8b6f2f] focus:outline-none focus:ring-2 focus:ring-[#8b6f2f]/30 rounded-2xl px-2 py-1 transition-transform active:scale-95" aria-expanded="false" aria-label="Menu layanan">
                <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-[#8b6f2f]/15 shadow-[0_4px_14px_rgba(139,111,47,0.25)]">
                    <svg id="burgerIconOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg id="burgerIconClose" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </span>
                <span class="text-xs font-medium mt-1">Menu</span>
            </button>
            <a href="{{ route('nasabah.setting.index') }}" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-[#8b6f2f] transition-colors {{ $isActive('setting') ? 'text-[#8b6f2f]' : '' }}">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-xs font-medium">Settings</span>
            </a>
        </div>
    </div>
</nav>

{{-- Mobile: floating arc menu (setengah lingkaran di atas burger) --}}
<div id="burgerArcBackdrop" class="md:hidden fixed inset-0 z-40 bg-black/15 opacity-0 pointer-events-none transition-opacity duration-300" aria-hidden="true"></div>
<div id="burgerArcMenu" class="md:hidden fixed left-1/2 bottom-[4.5rem] z-50 w-[220px] h-[95px] pointer-events-none" style="transform: translate(calc(-50% + 10px), 0);" aria-hidden="true">
    @foreach($arcItems as $idx => $arcItem)
    @php 
        $pos = $arcPositions[$idx] ?? ['x' => 0, 'y' => -70];
        $isRestrictedFeature = in_array($arcItem['key'], ['tabungan', 'pinjaman', 'deposito', 'gadai']);
        
        $isLocked = false;
        $lockReason = '';
        if ($isRestrictedFeature) {
            if (!$isVerified) {
                $isLocked = true;
                $lockReason = 'Akun Anda belum diverifikasi oleh admin.';
            } elseif (in_array($arcItem['key'], ['pinjaman', 'deposito', 'gadai']) && $isRestricted) {
                $isLocked = true;
                $lockReason = $bankInfo['reason'];
            }
        }
    @endphp
    @if($isLocked)
    <div class="arc-item absolute left-1/2 top-full flex items-center justify-center w-12 h-12 rounded-full bg-gray-300 text-gray-500 border-2 border-gray-400 opacity-40 cursor-not-allowed pointer-events-none"
       style="margin-left: -24px; margin-top: -24px; transition: transform 0.3s ease-out, opacity 0.3s ease-out;"
       data-arc-x="{{ $pos['x'] ?? 0 }}"
       data-arc-y="{{ $pos['y'] ?? -70 }}"
       data-arc-delay="{{ $idx * 40 }}"
       title="{{ $arcItem['label'] }} (Terkunci: {{ $lockReason }})">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $arcItem['icon'] }}"></path></svg>
        <span class="absolute -top-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
            <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
        </span>
    </div>
    @else
    <a href="{{ $arcItem['route'] === '#' ? '#' : route($arcItem['route']) }}"
       class="arc-item absolute left-1/2 top-full flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-[#a67c52]/90 to-[#8b6f2f]/95 text-white border-2 border-[#674c1d]/30 shadow-[0_4px_16px_rgba(139,111,47,0.35)] hover:scale-110 hover:shadow-[0_8px_24px_rgba(103,76,29,0.45)] hover:border-[#d4af37]/60 hover:from-[#8b6f2f] hover:to-[#674c1d] hover:ring-2 hover:ring-[#d4af37]/40 active:scale-95 transition-all duration-200 ease-out"
       style="margin-left: -24px; margin-top: -24px; transition: transform 0.3s ease-out, opacity 0.3s ease-out, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;"
       data-arc-x="{{ $pos['x'] ?? 0 }}"
       data-arc-y="{{ $pos['y'] ?? -70 }}"
       data-arc-delay="{{ $idx * 40 }}"
       title="{{ $arcItem['label'] }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $arcItem['icon'] }}"></path></svg>
    </a>
    @endif
    @endforeach
</div>
<style>
#burgerArcMenu .arc-item { transform: translate(var(--arc-x), var(--arc-y)) scale(0); opacity: 0; }
#burgerArcMenu.open .arc-item { transform: translate(var(--arc-x), var(--arc-y)) scale(1); opacity: 1; }
#burgerArcMenu.open { pointer-events: auto; }
</style>

<script>
(function() {
    // Apply arc position and delay from data attributes (avoids Blade in style attribute for linter)
    document.querySelectorAll('#burgerArcMenu .arc-item').forEach(function(el) {
        var x = el.getAttribute('data-arc-x');
        var y = el.getAttribute('data-arc-y');
        var d = el.getAttribute('data-arc-delay');
        if (x != null) el.style.setProperty('--arc-x', x + 'px');
        if (y != null) el.style.setProperty('--arc-y', y + 'px');
        if (d != null) el.style.transitionDelay = d + 'ms';
    });

    // --- Mobile: burger toggle & arc menu ---
    var burgerBtn = document.getElementById('burgerMenuBtn');
    var burgerArcMenu = document.getElementById('burgerArcMenu');
    var burgerArcBackdrop = document.getElementById('burgerArcBackdrop');
    var burgerIconOpen = document.getElementById('burgerIconOpen');
    var burgerIconClose = document.getElementById('burgerIconClose');

    function openArc() {
        if (!burgerArcMenu || !burgerArcBackdrop) return;
        burgerArcMenu.classList.add('open');
        burgerArcMenu.setAttribute('aria-hidden', 'false');
        burgerArcBackdrop.classList.remove('pointer-events-none', 'opacity-0');
        burgerArcBackdrop.classList.add('opacity-100');
        if (burgerIconOpen) burgerIconOpen.classList.add('hidden');
        if (burgerIconClose) burgerIconClose.classList.remove('hidden');
        if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'true');
    }

    function closeArc() {
        if (!burgerArcMenu || !burgerArcBackdrop) return;
        burgerArcMenu.classList.remove('open');
        burgerArcMenu.setAttribute('aria-hidden', 'true');
        burgerArcBackdrop.classList.add('pointer-events-none', 'opacity-0');
        burgerArcBackdrop.classList.remove('opacity-100');
        if (burgerIconOpen) burgerIconOpen.classList.remove('hidden');
        if (burgerIconClose) burgerIconClose.classList.add('hidden');
        if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'false');
    }

    if (burgerBtn) {
        burgerBtn.addEventListener('click', function() {
            if (burgerArcMenu && burgerArcMenu.classList.contains('open')) closeArc();
            else openArc();
        });
    }
    if (burgerArcBackdrop) {
        burgerArcBackdrop.addEventListener('click', closeArc);
    }
    if (burgerArcMenu) {
        burgerArcMenu.querySelectorAll('.arc-item').forEach(function(link) {
            link.addEventListener('click', function() { closeArc(); });
        });
    }

    // --- Desktop: sliding background (slide setelah click) ---
    var navInner = document.getElementById('navBarInner');
    var navSlider = document.getElementById('navSlider');
    var STORAGE_KEY = 'navPrevKey';

    function positionSlider(el) {
        if (!navSlider || !el) return;
        var innerRect = navInner.getBoundingClientRect();
        var linkRect = el.getBoundingClientRect();
        var left = linkRect.left - innerRect.left + (el.offsetWidth - 48) / 2;
        left = Math.max(0, left);
        navSlider.style.left = left + 'px';
        navSlider.style.width = '48px';
    }

    function getLinkByKey(key) {
        if (!navInner) return null;
        return navInner.querySelector('.nav-item[data-nav-key="' + key + '"]');
    }

    function initSlider() {
        if (!navInner || !navSlider) return;
        var activeLink = navInner.querySelector('[data-nav-active="1"]');
        var currentKey = activeLink ? activeLink.getAttribute('data-nav-key') : null;
        var prevKey = null;
        try { prevKey = sessionStorage.getItem(STORAGE_KEY); } catch (e) {}

        navSlider.style.transition = 'none';

        if (prevKey && prevKey !== currentKey) {
            var prevLink = getLinkByKey(prevKey);
            if (prevLink) {
                positionSlider(prevLink);
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        navSlider.style.transition = 'left 0.4s cubic-bezier(0.34, 1.2, 0.64, 1), width 0.4s cubic-bezier(0.34, 1.2, 0.64, 1)';
                        if (activeLink) positionSlider(activeLink);
                    });
                });
            } else {
                if (activeLink) positionSlider(activeLink);
                navSlider.style.transition = 'left 0.4s cubic-bezier(0.34, 1.2, 0.64, 1), width 0.4s cubic-bezier(0.34, 1.2, 0.64, 1)';
            }
        } else {
            if (activeLink) positionSlider(activeLink);
            navSlider.style.transition = 'left 0.4s cubic-bezier(0.34, 1.2, 0.64, 1), width 0.4s cubic-bezier(0.34, 1.2, 0.64, 1)';
        }

        if (currentKey) {
            try { sessionStorage.setItem(STORAGE_KEY, currentKey); } catch (e) {}
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSlider);
    } else {
        initSlider();
    }

    // Hide/show bottom navbar on scroll
    var lastScrollTop = 0;
    var scrollTimeout;
    var bottomNavbar = document.getElementById('bottomNavbar');

    if (bottomNavbar) {
        window.addEventListener('scroll', function() {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            clearTimeout(scrollTimeout);
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                bottomNavbar.style.transform = 'translateY(100%)';
            } else {
                bottomNavbar.style.transform = 'translateY(0)';
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            scrollTimeout = setTimeout(function() {
                bottomNavbar.style.transform = 'translateY(0)';
            }, 150);
        });
    }
})();
</script>