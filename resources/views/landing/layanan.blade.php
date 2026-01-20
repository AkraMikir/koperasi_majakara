<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan - Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #674c1d;
            --primary-light: #8b6f2f;
            --primary-dark: #4a3514;
            --accent: #d4af37;
            --bg-light: #faf9f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%);
        }
        
        .text-primary {
            color: #674c1d;
        }
        
        .bg-primary {
            background-color: #674c1d;
        }
        
        .bg-primary-dark {
            background-color: #4a3514;
        }
        
        .border-primary {
            border-color: #674c1d;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(103, 76, 29, 0.15);
        }
    </style>
</head>
<body class="bg-[#faf9f6] text-gray-800 antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                    </a>
                    <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('welcome') }}#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a>
                    <a href="{{ route('landing.layanan') }}" class="text-primary font-semibold">Layanan</a>
                    <a href="{{ route('landing.keuntungan') }}" class="text-gray-700 hover:text-primary transition">Keuntungan</a>
                    <a href="{{ route('landing.testimoni') }}" class="text-gray-700 hover:text-primary transition">Testimoni</a>
                    <a href="{{ route('landing.faq') }}" class="text-gray-700 hover:text-primary transition">FAQ</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @if (auth()->check())
                            @php
                                $user = auth()->user();
                                $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                            @endphp
                            <a href="{{ $dashboardUrl }}" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all shadow-md hover:shadow-lg font-medium flex items-center space-x-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition shadow-md font-medium">
                                    Register
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('welcome') }}#beranda" class="block text-gray-700 hover:text-primary">Beranda</a>
                <a href="{{ route('landing.layanan') }}" class="block text-primary font-semibold">Layanan</a>
                <a href="{{ route('landing.keuntungan') }}" class="block text-gray-700 hover:text-primary">Keuntungan</a>
                <a href="{{ route('landing.testimoni') }}" class="block text-gray-700 hover:text-primary">Testimoni</a>
                <a href="{{ route('landing.faq') }}" class="block text-gray-700 hover:text-primary">FAQ</a>
                <div class="pt-4 border-t space-y-2">
                    @if (Route::has('login'))
                        @if (auth()->check())
                            @php
                                $user = auth()->user();
                                $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                            @endphp
                            <a href="{{ $dashboardUrl }}" class="block px-4 py-2.5 text-center bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium shadow-md flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2.5 text-center text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-4 py-2.5 text-center bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium shadow-md">
                                    Register
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="font-display text-5xl md:text-6xl font-bold text-primary mb-6">
                Layanan Koperasi Majakara
            </h1>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                Kami menyediakan berbagai layanan keuangan yang lengkap dan terpercaya untuk membantu mewujudkan impian finansial Anda
            </p>
        </div>
    </section>
    
    <!-- Services Detail Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <!-- Tabungan -->
            <div class="mb-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="w-20 h-20 gradient-primary rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-primary mb-4">Tabungan</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Program tabungan yang dirancang untuk membantu Anda menabung dengan aman dan nyaman. Dengan bunga kompetitif, tabungan Anda akan terus berkembang seiring waktu.
                        </p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Bunga kompetitif yang dihitung harian</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Setoran dan penarikan fleksibel</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Sistem keamanan berlapis untuk melindungi dana Anda</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Layanan janji temu untuk setoran tunai</p>
                            </div>
                        </div>
                        @if (auth()->check() && auth()->user()->role === 'nasabah')
                            <a href="{{ route('nasabah.tabungan.index') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Mulai Menabung
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="bg-gradient-to-br from-primary/10 to-accent/10 rounded-3xl p-8">
                            <div class="bg-white rounded-2xl p-8 shadow-xl">
                                <h3 class="text-xl font-bold text-primary mb-6">Cara Kerja Tabungan</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Daftar & Verifikasi</h4>
                                            <p class="text-gray-600 text-sm">Lengkapi data diri dan tunggu verifikasi dari admin</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Ajukan Setoran</h4>
                                            <p class="text-gray-600 text-sm">Pilih metode setoran: transfer atau tunai dengan janji temu</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Admin Approve</h4>
                                            <p class="text-gray-600 text-sm">Admin akan memverifikasi dan menyetujui pengajuan Anda</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Saldo Bertambah</h4>
                                            <p class="text-gray-600 text-sm">Setoran Anda masuk dan saldo tabungan bertambah</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pinjaman -->
            <div class="mb-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 lg:order-1">
                        <div class="bg-gradient-to-br from-primary/10 to-accent/10 rounded-3xl p-8">
                            <div class="bg-white rounded-2xl p-8 shadow-xl">
                                <h3 class="text-xl font-bold text-primary mb-6">Fitur Pinjaman</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Pilih Jenis Angsuran</h4>
                                            <p class="text-gray-600 text-sm">Bulanan atau mingguan sesuai kebutuhan Anda</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Ajukan Pinjaman</h4>
                                            <p class="text-gray-600 text-sm">Isi form pengajuan dengan nominal dan durasi pinjaman</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Review & Approve</h4>
                                            <p class="text-gray-600 text-sm">Admin akan review dan approve pengajuan Anda</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Cair & Bayar Angsuran</h4>
                                            <p class="text-gray-600 text-sm">Dana dicairkan dan bayar angsuran sesuai jadwal</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 order-1 lg:order-2">
                        <div class="w-20 h-20 gradient-primary rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-primary mb-4">Pinjaman</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Solusi pinjaman dengan proses cepat, bunga ringan, dan syarat yang mudah. Tersedia pilihan angsuran bulanan atau mingguan sesuai kebutuhan Anda.
                        </p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Bunga ringan dan kompetitif</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Pilihan angsuran bulanan atau mingguan</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Proses pengajuan cepat dan mudah</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Tracking angsuran real-time</p>
                            </div>
                        </div>
                        @if (auth()->check() && auth()->user()->role === 'nasabah')
                            <a href="{{ route('nasabah.pinjaman.index') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Ajukan Pinjaman
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Deposito -->
            <div class="mb-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="w-20 h-20 gradient-primary rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-primary mb-4">Deposito</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Investasi aman dengan return yang menarik untuk jangka waktu tertentu. Pilih tenor yang sesuai dengan kebutuhan investasi Anda.
                        </p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Return investasi yang menarik</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Multiple pilihan tenor sesuai kebutuhan</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Perhitungan bunga harian</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Pencairan deposito yang mudah</p>
                            </div>
                        </div>
                        @if (auth()->check() && auth()->user()->role === 'nasabah')
                            <a href="#" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Ajukan Deposito
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="bg-gradient-to-br from-primary/10 to-accent/10 rounded-3xl p-8">
                            <div class="bg-white rounded-2xl p-8 shadow-xl">
                                <h3 class="text-xl font-bold text-primary mb-6">Keuntungan Deposito</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Pilih Tenor</h4>
                                            <p class="text-gray-600 text-sm">Pilih jangka waktu deposito sesuai kebutuhan investasi</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Ajukan Deposito</h4>
                                            <p class="text-gray-600 text-sm">Isi form pengajuan dengan nominal dan tenor yang diinginkan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Approve & Setor</h4>
                                            <p class="text-gray-600 text-sm">Setelah approve, setor dana untuk memulai deposito</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Dapatkan Bunga</h4>
                                            <p class="text-gray-600 text-sm">Bunga dihitung harian dan dapat dicairkan saat jatuh tempo</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gadai -->
            <div class="mb-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 lg:order-1">
                        <div class="bg-gradient-to-br from-primary/10 to-accent/10 rounded-3xl p-8">
                            <div class="bg-white rounded-2xl p-8 shadow-xl">
                                <h3 class="text-xl font-bold text-primary mb-6">Proses Gadai</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Pilih Barang</h4>
                                            <p class="text-gray-600 text-sm">Pilih jenis barang berharga yang akan digadaikan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Ajukan Gadai</h4>
                                            <p class="text-gray-600 text-sm">Isi form dengan detail barang dan nominal yang diinginkan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Penilaian & Approve</h4>
                                            <p class="text-gray-600 text-sm">Admin akan menilai barang dan approve pengajuan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Terima Dana</h4>
                                            <p class="text-gray-600 text-sm">Dana dicairkan dan bayar bunga sesuai jadwal</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 order-1 lg:order-2">
                        <div class="w-20 h-20 gradient-primary rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-primary mb-4">Gadai</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Solusi cepat untuk kebutuhan dana dengan jaminan barang berharga yang Anda miliki. Proses cepat, bunga terjangkau, dan barang aman tersimpan.
                        </p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Proses cepat dan mudah</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Bunga terjangkau dan transparan</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Barang aman tersimpan di tempat kami</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-primary mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Fleksibel dalam pembayaran bunga</p>
                            </div>
                        </div>
                        @if (auth()->check() && auth()->user()->role === 'nasabah')
                            <a href="#" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Ajukan Gadai
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 relative" style="background: linear-gradient(135deg, #4a3514 0%, #674c1d 100%);">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Siap Memulai Perjalanan Finansial Anda?
            </h2>
            <p class="text-xl text-white mb-8">
                Bergabunglah dengan ribuan anggota yang telah mempercayakan kebutuhan keuangan mereka kepada Koperasi Majakara
            </p>
            @if (!auth()->check())
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white/20 transition">
                        Masuk ke Akun
                    </a>
                </div>
            @else
                @php
                    $user = auth()->user();
                    $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                @endphp
                <a href="{{ $dashboardUrl }}" class="inline-block px-8 py-4 bg-white text-primary rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                    Masuk ke Dashboard
                </a>
            @endif
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-primary text-white py-16 px-4 sm:px-6 lg:px-8 relative z-10" style="background-color: #4a3514;">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Logo & Description -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                        <span class="text-xl font-bold">Koperasi Majakara</span>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Solusi keuangan terpercaya untuk mewujudkan impian finansial Anda.
                    </p>
                </div>
                
                <!-- Syarat dan Ketentuan -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Syarat dan Ketentuan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#" class="hover:text-white transition">Ketentuan Umum</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat Keanggotaan</a></li>
                        <li><a href="#" class="hover:text-white transition">Ketentuan Layanan</a></li>
                    </ul>
                </div>
                
                <!-- Info Seabank -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Info Seabank</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Berita & Update</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Laporan Tahunan</a></li>
                    </ul>
                </div>
                
                <!-- Hubungi Kami -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#" class="hover:text-white transition">Customer Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Email Support</a></li>
                        <li><a href="#" class="hover:text-white transition">Lokasi Kantor</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak Darurat</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/20 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-white/80 text-sm mb-4 md:mb-0">
                    Copyright © 2025 Koperasi Majakara. All rights reserved.
                </p>
                <div class="flex items-center space-x-4">
                    <span class="text-white/80 text-sm">Ikuti Kami:</span>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.246 1.805-.413 2.227-.217.562-.677.96-.896 1.382-.42.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.413-.569-.224-.96-.479-1.379-.896-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
