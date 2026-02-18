<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keuntungan - Koperasi Majakara</title>
    
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
        
        .card-hover {
            transition: all 0.4s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px rgba(103, 76, 29, 0.25);
        }
        
        .counter {
            font-variant-numeric: tabular-nums;
        }
        
        .benefit-icon {
            transition: all 0.3s ease;
        }
        
        .benefit-icon:hover {
            transform: rotate(5deg) scale(1.1);
        }
    </style>
</head>
<body class="bg-[#faf9f6] text-gray-800 antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                    </a>
                    <a href="{{ route('welcome') }}" class="text-xl font-bold text-primary">Kospin Majakara</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('welcome') }}#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a>
                    <a href="{{ route('landing.layanan') }}" class="text-gray-700 hover:text-primary transition">Layanan</a>
                    <a href="{{ route('landing.keuntungan') }}" class="text-primary font-semibold border-b-2 border-primary">Keuntungan</a>
                    <a href="{{ route('landing.testimoni') }}" class="text-gray-700 hover:text-primary transition">Testimoni</a>
                    <a href="{{ route('landing.faq') }}" class="text-gray-700 hover:text-primary transition">FAQ</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @if (auth()->check())
                            @php
                                $user = auth()->user();
                                $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                            @endphp
                            <a href="{{ $dashboardUrl }}" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all shadow-md font-medium flex items-center space-x-2 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition shadow-md font-medium">Register</a>
                            @endif
                        @endif
                    @endif
                </div>
                
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
                <a href="{{ route('landing.layanan') }}" class="block text-gray-700 hover:text-primary">Layanan</a>
                <a href="{{ route('landing.keuntungan') }}" class="block text-primary font-semibold">Keuntungan</a>
                <a href="{{ route('landing.testimoni') }}" class="block text-gray-700 hover:text-primary">Testimoni</a>
                <a href="{{ route('landing.faq') }}" class="block text-gray-700 hover:text-primary">FAQ</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #faf9f6 0%, #ffffff 100%);">
        <!-- Background Decoration -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 left-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-6">
                    <span class="text-primary font-semibold text-sm">⭐ Mengapa Memilih Kami?</span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6">
                    Keuntungan Memaka<br>
                    <span style="background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Koperasi Majakara</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    Mengapa ribuan anggota mempercayakan kebutuhan keuangan mereka kepada kami? Temukan berbagai keuntungan yang bisa Anda dapatkan
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover border border-gray-100">
                    <div class="text-5xl md:text-6xl font-bold mb-2" style="background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">100%</div>
                    <p class="text-gray-700 font-semibold">Terpercaya</p>
                    <p class="text-sm text-gray-500 mt-1">Keamanan Dana</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover border border-gray-100">
                    <div class="text-5xl md:text-6xl font-bold mb-2" style="background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">24/7</div>
                    <p class="text-gray-700 font-semibold">Layanan</p>
                    <p class="text-sm text-gray-500 mt-1">Akses Kapan Saja</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover border border-gray-100">
                    <div class="text-5xl md:text-6xl font-bold mb-2" style="background: linear-gradient(135deg, #8b6f2f 0%, #d4af37 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">10K+</div>
                    <p class="text-gray-700 font-semibold">Anggota Aktif</p>
                    <p class="text-sm text-gray-500 mt-1">Mempercayai Kami</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover border border-gray-100">
                    <div class="text-5xl md:text-6xl font-bold mb-2" style="background: linear-gradient(135deg, #674c1d 0%, #d4af37 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">98%</div>
                    <p class="text-gray-700 font-semibold">Kepuasan</p>
                    <p class="text-sm text-gray-500 mt-1">Rating Anggota</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Benefit 1: Aman & Terpercaya -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block px-4 py-2 bg-green-100 rounded-full mb-6">
                        <span class="text-green-700 font-semibold text-sm">🛡️ Keamanan Terjamin</span>
                    </div>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-6">
                        Aman & Terpercaya
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Keamanan dana Anda adalah prioritas utama kami. Sistem keamanan berlapis dan telah terpercaya oleh ribuan anggota selama bertahun-tahun.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-xl">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Enkripsi Data</h4>
                                <p class="text-gray-600 text-sm">Semua data dienkripsi dengan standar keamanan tinggi SSL/TLS</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-xl">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Verifikasi Multi-Layer</h4>
                                <p class="text-gray-600 text-sm">PIN + OTP untuk transaksi penting, approval admin untuk setiap pengajuan</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-4 bg-purple-50 rounded-xl">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Monitoring 24/7</h4>
                                <p class="text-gray-600 text-sm">Sistem dipantau terus-menerus untuk mencegah aktivitas mencurigakan</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-3xl p-8 text-white shadow-2xl">
                        <h3 class="text-2xl font-bold mb-6">Standar Keamanan Kami</h3>
                        <div class="space-y-6">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-white/80">Enkripsi Data</span>
                                    <span class="text-white font-bold">256-bit SSL</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-[#d4af37] h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-white/80">Verifikasi Admin</span>
                                    <span class="text-white font-bold">100%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-[#d4af37] h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-white/80">Backup Data</span>
                                    <span class="text-white font-bold">Real-time</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-[#d4af37] h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Benefit 2: Proses Cepat -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-[#faf9f6] to-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-3xl p-10 shadow-2xl">
                        <h3 class="text-3xl font-bold text-primary mb-8 text-center">Efisiensi Waktu</h3>
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <div class="w-16 h-16 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">Pengajuan Online</h4>
                                    <p class="text-gray-600">Ajukan dari mana saja, kapan saja melalui dashboard</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-[#674c1d]">24/7</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <div class="w-16 h-16 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">Approval Cepat</h4>
                                    <p class="text-gray-600">Admin siap memproses pengajuan Anda dengan cepat</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-[#8b6f2f]">< 24j</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <div class="w-16 h-16 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">Notifikasi Real-time</h4>
                                    <p class="text-gray-600">Update status langsung ke dashboard Anda</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-[#d4af37]">Instant</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="order-1 lg:order-2">
                    <div class="inline-block px-4 py-2 bg-blue-100 rounded-full mb-6">
                        <span class="text-blue-700 font-semibold text-sm">⚡ Kecepatan & Efisiensi</span>
                    </div>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-6">
                        Proses Cepat & Efisien
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Tidak perlu antri lama atau datang ke kantor. Semua proses dapat dilakukan secara online dengan sistem yang terintegrasi dan otomatis.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-white rounded-xl p-6 shadow-lg text-center border border-gray-100">
                            <div class="text-4xl font-bold text-[#674c1d] mb-2">5 Menit</div>
                            <p class="text-sm text-gray-600">Pengajuan Online</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-lg text-center border border-gray-100">
                            <div class="text-4xl font-bold text-[#8b6f2f] mb-2">1 Hari</div>
                            <p class="text-sm text-gray-600">Proses Approval</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-gray-700 font-medium">Tanpa perlu datang ke kantor untuk pengajuan</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-gray-700 font-medium">Tracking status real-time di dashboard</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-gray-700 font-medium">Upload dokumen digital, tidak perlu fotokopi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Benefit 3: Bunga Kompetitif -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-[#d4af37]/10 rounded-full mb-6">
                    <span class="text-[#674c1d] font-semibold text-sm">💰 Keuntungan Finansial</span>
                </div>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Bunga Kompetitif
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Return terbaik untuk investasi dan tabungan Anda
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Tabungan Comparison -->
                <div class="bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-3xl p-8 text-white shadow-2xl card-hover">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Bunga Tabungan</h3>
                    <div class="text-5xl font-bold mb-4">Harian</div>
                    <p class="text-white/80 mb-6">Dihitung berdasarkan saldo rata-rata harian Anda</p>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Tanpa biaya admin bulanan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Gratis tarik tunai</span>
                        </div>
                    </div>
                </div>

                <!-- Deposito Comparison -->
                <div class="bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-3xl p-8 text-white shadow-2xl card-hover">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Bunga Deposito</h3>
                    <div class="text-5xl font-bold mb-4">Tinggi</div>
                    <p class="text-white/80 mb-6">Return lebih tinggi dengan pilihan tenor fleksibel</p>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Bunga lebih tinggi dari tabungan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Multiple pilihan tenor</span>
                        </div>
                    </div>
                </div>

                <!-- Pinjaman Comparison -->
                <div class="bg-linear-to-br from-[#8b6f2f] to-[#674c1d] rounded-3xl p-8 text-white shadow-2xl card-hover">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Bunga Pinjaman</h3>
                    <div class="text-5xl font-bold mb-4">10-24%</div>
                    <p class="text-white/80 mb-6">Bunga kompetitif berdasarkan durasi pinjaman</p>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Tidak dipotong di awal</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Denda hanya 0,3% per hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefit 4: Layanan Digital -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-white to-[#faf9f6]">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block px-4 py-2 bg-purple-100 rounded-full mb-6">
                        <span class="text-purple-700 font-semibold text-sm">🚀 Teknologi Modern</span>
                    </div>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-6">
                        Layanan Digital 24/7
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Akses seluruh layanan kapan saja, di mana saja melalui platform digital kami yang modern dan mudah digunakan.
                    </p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-linear-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Platform Web Responsif</h4>
                                <p class="text-gray-600 text-sm">Akses dari HP, tablet, atau komputer</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-linear-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Dashboard Interaktif</h4>
                                <p class="text-gray-600 text-sm">Monitor saldo dan transaksi real-time</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-linear-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Notifikasi Smart</h4>
                                <p class="text-gray-600 text-sm">Pengingat jatuh tempo & update status</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="bg-white rounded-3xl p-10 shadow-2xl border border-gray-100">
                        <h3 class="text-2xl font-bold text-primary mb-8 text-center">Fitur Dashboard</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-[#674c1d]/5 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#674c1d]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">Lihat Saldo Real-time</span>
                                </div>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-[#674c1d]/5 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#674c1d]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">Riwayat Transaksi Lengkap</span>
                                </div>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-[#674c1d]/5 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#674c1d]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">Jadwal Angsuran</span>
                                </div>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-[#674c1d]/5 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#674c1d]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">Pengingat Jatuh Tempo</span>
                                </div>
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Benefits Grid -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Semua Keuntungan untuk Anda
                </h2>
                <p class="text-xl text-gray-600">Bergabung sekarang dan nikmati semua benefit ini</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tanpa Biaya Tersembunyi</h3>
                    <p class="text-gray-600 leading-relaxed">Semua biaya transparan dan dijelaskan di awal. Tidak ada biaya mengejutkan di kemudian hari.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Customer Service Responsif</h3>
                    <p class="text-gray-600 leading-relaxed">Tim kami siap membantu menjawab pertanyaan dan menyelesaikan masalah Anda dengan cepat.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Fleksibilitas Maksimal</h3>
                    <p class="text-gray-600 leading-relaxed">Setor, tarik, ajukan pinjaman kapan saja. Pilih metode yang paling nyaman untuk Anda.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Return Maksimal</h3>
                    <p class="text-gray-600 leading-relaxed">Bunga tabungan dihitung harian, deposito dengan return tinggi, pinjaman dengan bunga ringan.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Terdaftar & Diawasi</h3>
                    <p class="text-gray-600 leading-relaxed">Koperasi resmi yang terdaftar dan diawasi oleh pemerintah untuk keamanan Anda.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 card-hover">
                    <div class="w-16 h-16 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 benefit-icon">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Proses Super Cepat</h3>
                    <p class="text-gray-600 leading-relaxed">Pengajuan hanya 5 menit, approval dalam 1 hari. Tidak perlu menunggu lama.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #4a3514 0%, #674c1d 50%, #8b6f2f 100%);">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, #d4af37 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Dapatkan Semua Keuntungan Ini Sekarang
            </h2>
            <p class="text-xl text-white/90 mb-10">
                Bergabunglah dengan Koperasi Majakara dan nikmati berbagai keuntungan yang telah dirasakan ribuan anggota
            </p>
            @if (!auth()->check())
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-10 py-5 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-2xl hover:shadow-3xl text-lg">
                        Daftar Sekarang - Gratis!
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-5 border-2 border-white text-white rounded-xl font-bold hover:bg-white/10 transition text-lg">
                        Sudah Punya Akun?
                    </a>
                </div>
            @else
                @php
                    $user = auth()->user();
                    $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                @endphp
                <a href="{{ $dashboardUrl }}" class="inline-block px-10 py-5 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-2xl text-lg">
                    Akses Dashboard Saya
                </a>
            @endif
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-primary text-white py-16 px-4 sm:px-6 lg:px-8" style="background-color: #4a3514;">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="h-16 w-auto object-contain">
                        <span class="text-xl font-bold">Koperasi Majakara</span>
                    </div>
                    <p class="text-white/80 text-sm">Solusi keuangan terpercaya untuk mewujudkan impian finansial Anda.</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Layanan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="{{ route('landing.layanan') }}" class="hover:text-white transition">Tabungan</a></li>
                        <li><a href="{{ route('landing.layanan') }}" class="hover:text-white transition">Pinjaman</a></li>
                        <li><a href="{{ route('landing.layanan') }}" class="hover:text-white transition">Deposito</a></li>
                        <li><a href="{{ route('landing.layanan') }}" class="hover:text-white transition">Gadai</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="{{ route('landing.keuntungan') }}" class="hover:text-white transition">Keuntungan</a></li>
                        <li><a href="{{ route('landing.testimoni') }}" class="hover:text-white transition">Testimoni</a></li>
                        <li><a href="{{ route('landing.faq') }}" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li>Email: info@majakara.com</li>
                        <li>Telp: (021) 1234-5678</li>
                        <li>WA: 0812-3456-7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/20 pt-8 text-center">
                <p class="text-white/80 text-sm">Copyright © 2026 Koperasi Majakara. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
