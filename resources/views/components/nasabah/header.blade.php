@php
    // Frontend only - no auth required
    $currentDate = now()->locale('id')->isoFormat('D MMMM YYYY');
    $currentTime = now()->format('H.i.s');
    $currentRoute = request()->route()->getName() ?? '';
    $isTabunganPage = str_contains($currentRoute, 'tabungan');
    $user = auth()->user();
    $needsEmergencyContact = false;
    if ($user && $user->role === 'nasabah') {
        $nasabah = $user->nasabah;
        if (!$nasabah || !$nasabah->darurat) {
            $needsEmergencyContact = true;
        }
    }
@endphp

<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="w-full px-4 py-2 max-w-full">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3 shrink-0">
                <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo" class="h-12 sm:h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                <span class="text-lg sm:text-xl font-bold text-primary hidden md:block">Kospin Majakara</span>
            </div>
            
            <!-- Welcome Text -->
            <div class="flex-1 min-w-0 overflow-hidden flex flex-col justify-center items-center text-center mx-2 md:mx-4">
                <h1 class="text-sm sm:text-lg md:text-xl font-black text-gray-900 uppercase tracking-widest leading-tight">
                    KOPERASI MAJAKARA
                </h1>
                <div class="hidden sm:flex items-center gap-2 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37]"></span>
                    <p class="text-[9px] sm:text-xs text-gray-500 font-bold tracking-widest uppercase">Layanan Nasabah</p>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37]"></span>
                </div>
            </div>
            
            <!-- Right Side: Icons and Time -->
            <div class="flex items-center space-x-1 sm:space-x-3 shrink-0">
                <!-- Notification (dropdown seperti admin + hover effect) -->
                @php
                    $nasabahNotificationsUnreadCount = $nasabahNotificationsUnreadCount ?? 0;
                    $nasabahNotificationsRecent = $nasabahNotificationsRecent ?? collect([]);
                @endphp
                <div class="relative group" id="notif-dropdown-wrap">
                    <a href="{{ route('nasabah.notifications.index') }}" class="relative inline-flex p-2.5 text-gray-500 hover:text-[#674c1d] transition-colors rounded-xl hover:bg-amber-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($nasabahNotificationsUnreadCount > 0)
                            <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-[9px] font-black rounded-full border-2 border-white shadow-sm">
                                {{ $nasabahNotificationsUnreadCount > 99 ? '99+' : $nasabahNotificationsUnreadCount }}
                            </span>
                        @endif
                    </a>
                    <!-- Dropdown preview on hover -->
                    <div class="absolute right-0 mt-2 w-[260px] sm:w-[320px] bg-white rounded-3xl shadow-2xl border border-gray-100 py-2 sm:py-3 z-[100] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2 origin-top-right">
                        <div class="px-4 sm:px-5 py-2 sm:py-3 border-b border-gray-100 flex items-center justify-between">
                            <span class="font-black text-xs sm:text-sm text-gray-900 tracking-tight">Notifikasi</span>
                            @if($nasabahNotificationsUnreadCount > 0)
                                <form method="POST" action="{{ route('nasabah.notifications.mark-all-read') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[9px] sm:text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Tandai Dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-[50vh] sm:max-h-[60vh] overflow-y-auto custom-scrollbar">
                            @forelse($nasabahNotificationsRecent as $notif)
                                @php $targetUrl = $notif->link ?: route('nasabah.notifications.index'); @endphp
                                <form method="POST" action="{{ route('nasabah.notifications.mark-read', $notif->id) }}" class="block border-b border-gray-50 last:border-0 {{ $notif->read_at ? '' : 'bg-amber-50/50' }}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $targetUrl }}">
                                    <button type="submit" class="w-full text-left px-4 sm:px-5 py-3 hover:bg-gray-50 transition-colors rounded-none group/item">
                                        <div class="flex gap-2 sm:gap-3">
                                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 mt-1 sm:mt-1.5 rounded-full {{ $notif->read_at ? 'bg-gray-200' : 'bg-red-500' }} shrink-0"></div>
                                            <div>
                                                <p class="text-xs sm:text-sm font-bold text-gray-900 line-clamp-1 group-hover/item:text-[#674c1d] transition-colors">{{ $notif->title }}</p>
                                                @if($notif->message)
                                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                                                @endif
                                                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 mt-1.5 sm:mt-2 tracking-widest uppercase">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            @empty
                                <div class="px-5 py-8 flex flex-col items-center justify-center text-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    </div>
                                    <p class="text-xs font-black text-gray-400 tracking-widest uppercase">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="px-5 py-3 border-t border-gray-100 text-center">
                            <a href="{{ route('nasabah.notifications.index') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="relative" id="profile-dropdown-wrap">
                    <button onclick="toggleDropdown('profile-menu')" class="p-2.5 text-gray-500 hover:text-[#674c1d] transition-colors focus:outline-none rounded-xl hover:bg-amber-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="profile-menu" class="absolute right-0 mt-2 w-56 bg-white rounded-3xl shadow-2xl border border-gray-100 py-2 hidden z-50 origin-top-right">
                        <div class="py-2 px-2">
                            <!-- Profile Link -->
                            <a href="{{ route('nasabah.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 rounded-2xl hover:text-[#674c1d] transition-all font-bold">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span>Profil Saya</span>
                            </a>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-2 mx-4"></div>
                            
                            <!-- Logout Button -->
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-2xl transition-all font-bold">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-500 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Date and Time -->
                <div class="hidden lg:flex flex-col items-end text-right ml-4">
                    <span class="text-[11px] font-black text-gray-900 tracking-widest uppercase" id="currentDate">{{ $currentDate }}</span>
                    <span class="text-[10px] font-bold text-[#d4af37] tracking-widest time-display">{{ $currentTime }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($needsEmergencyContact)
    <div class="bg-amber-50 border-t border-amber-200/60 py-2.5 px-4 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <span class="flex h-2.5 w-2.5 relative shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-xs sm:text-sm font-medium text-amber-800 text-center sm:text-left">
                    Anda belum melengkapi data <strong class="font-bold">Kontak Darurat</strong>. Harap lengkapi untuk dapat mengakses fitur pinjaman dan gadai.
                </p>
            </div>
            <a href="{{ route('nasabah.profile', ['focus' => 'kontak-darurat']) }}" class="text-xs sm:text-sm font-bold text-amber-700 hover:text-amber-900 transition-colors flex items-center gap-1 whitespace-nowrap">
                Lengkapi Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
    @endif
</header>

<script>
    // Toggle dropdown open/close — works on both mouse click and touchscreen tap
    function toggleDropdown(menuId, btnId) {
        const menu = document.getElementById(menuId);
        const allMenus = ['notif-menu', 'profile-menu'];

        // Close other dropdowns first
        allMenus.forEach(function(id) {
            if (id !== menuId) {
                var el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            }
        });

        // Toggle target
        menu.classList.toggle('hidden');
    }

    // Close dropdowns when tapping/clicking outside
    document.addEventListener('click', function(e) {
        var wraps = ['notif-dropdown-wrap', 'profile-dropdown-wrap'];
        var clickedInside = wraps.some(function(id) {
            var el = document.getElementById(id);
            return el && el.contains(e.target);
        });

        if (!clickedInside) {
            ['notif-menu', 'profile-menu'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
        }
    });

    // Also support touchstart for faster response on mobile
    document.addEventListener('touchstart', function(e) {
        var wraps = ['notif-dropdown-wrap', 'profile-dropdown-wrap'];
        var touchedInside = wraps.some(function(id) {
            var el = document.getElementById(id);
            return el && el.contains(e.target);
        });

        if (!touchedInside) {
            ['notif-menu', 'profile-menu'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
        }
    }, { passive: true });
</script>
