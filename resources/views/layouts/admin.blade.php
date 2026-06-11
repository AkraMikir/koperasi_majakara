<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#faf9f6]">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-admin.sidebar />
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-admin.header />
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Universal Photo Preview Modal -->
    <x-photo-preview-modal />
    
    @stack('scripts')
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Custom Majakara Styling for SweetAlert2
        const swalMajakara = Swal.mixin({
            customClass: {
                popup: 'rounded-2xl border border-gray-100 shadow-xl',
                title: 'text-gray-900 font-display font-bold',
                htmlContainer: 'text-gray-600',
                confirmButton: 'bg-majakara-brown hover:bg-majakara-dark-gold text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-sm',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-lg transition-colors border border-gray-200 shadow-sm'
            },
            buttonsStyling: false
        });
        // Make it default
        window.Swal = swalMajakara;
        // ─── UPDATE JAM ─────────────────────────────────────────────────────────────
        // Guard: if (!window.__clockInterval) pastikan setInterval hanya spawn SEKALI.
        // Tanpa guard ini, setiap Turbo page swap spawn interval baru.
        // 10x klik sidebar = 10 interval jalan bersamaan = BUG REPEAT!
        if (!window.__clockInterval) {
            function updateTime() {
                const now = new Date();
                const options = { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
                const dateString = now.toLocaleDateString('id-ID', options);
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                    hour12: false, timeZone: 'Asia/Jakarta'
                }).replace(/:/g, '.');

                const dateElement = document.getElementById('currentDate');
                const timeElement = document.querySelector('.time-display');
                if (dateElement) dateElement.textContent = dateString;
                if (timeElement) timeElement.textContent = timeString;
            }

            updateTime();
            window.__clockInterval = setInterval(updateTime, 1000);
        }

        // ─── MOBILE SIDEBAR TOGGLE ────────────────────────────────────────────────
        // Dideklarasikan di window agar bisa dipanggil dari atribut HTML onclick=""
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }
        }

        // ─── TURBO:LOAD EVENT ─────────────────────────────────────────────────────
        // Dijalankan setiap kali Turbo selesai swap konten halaman (bukan DOMContentLoaded)
        document.addEventListener('turbo:load', function() {
            // Auto-tutup sidebar mobile setelah pindah halaman
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        });

        // ─── GLOBAL DEBOUNCE UNTUK FILTER / SEARCH ────────────────────────────────
        let searchTimeout = null;
        document.addEventListener('input', function (e) {
            if (e.target.name === 'search' || e.target.classList.contains('debounce-search')) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    let form = e.target.closest('form');
                    if (form) {
                        form.requestSubmit();
                    }
                }, 500); // 500ms delay
            }
        });
    </script>
</body>
</html>

