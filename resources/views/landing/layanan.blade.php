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
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(103, 76, 29, 0.2);
        }
        
        .icon-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .service-number {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
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
                    <a href="{{ route('landing.layanan') }}" class="text-primary font-semibold border-b-2 border-primary">Layanan</a>
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
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #faf9f6 0%, #ffffff 100%);">
        <!-- Background Decoration -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 right-20 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-6">
                    <span class="text-primary font-semibold text-sm">✨ 4 Layanan Unggulan</span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6">
                    Layanan Koperasi<br>
                    <span style="background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Majakara</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    Kami menyediakan berbagai layanan keuangan yang lengkap dan terpercaya untuk membantu mewujudkan impian finansial Anda
                </p>
            </div>

            <!-- Quick Service Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="#tabungan" class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover text-center">
                    <div class="w-16 h-16 mx-auto gradient-primary rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-primary">Tabungan</h3>
                    <p class="text-sm text-gray-600 mt-2">Menabung aman dengan bunga kompetitif</p>
                </a>

                <a href="#pinjaman" class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover text-center">
                    <div class="w-16 h-16 mx-auto gradient-primary rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-primary">Pinjaman</h3>
                    <p class="text-sm text-gray-600 mt-2">Pinjaman cepat dengan bunga ringan</p>
                </a>

                <a href="#deposito" class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover text-center">
                    <div class="w-16 h-16 mx-auto gradient-primary rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-primary">Deposito</h3>
                    <p class="text-sm text-gray-600 mt-2">Investasi aman dengan return menarik</p>
                </a>

                <a href="#gadai" class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 card-hover text-center">
                    <div class="w-16 h-16 mx-auto gradient-primary rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-primary">Gadai</h3>
                    <p class="text-sm text-gray-600 mt-2">Solusi dana cepat dengan jaminan</p>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Tabungan Section -->
    <section id="tabungan" class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <div class="inline-block px-4 py-2 bg-[#674c1d]/10 rounded-full mb-6">
                        <span class="text-[#674c1d] font-semibold text-sm">💰 Layanan Tabungan</span>
                    </div>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-6">
                        Tabungan Aman & Berkembang
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Program tabungan yang dirancang untuk membantu Anda menabung dengan aman dan nyaman. Dengan bunga kompetitif, tabungan Anda akan terus berkembang seiring waktu.
                    </p>
                    
                    <!-- Features List -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Bunga Kompetitif Harian</h4>
                                <p class="text-gray-600 text-sm">Bunga dihitung setiap hari berdasarkan saldo rata-rata Anda</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Setoran & Penarikan Fleksibel</h4>
                                <p class="text-gray-600 text-sm">Mudah setor dan tarik kapan saja melalui transfer atau janji temu</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Keamanan Berlapis</h4>
                                <p class="text-gray-600 text-sm">Dana Anda dilindungi dengan sistem keamanan multi-layer</p>
                            </div>
                        </div>
                    </div>
                    
                    @if (auth()->check() && auth()->user()->role === 'nasabah')
                        <a href="{{ route('nasabah.tabungan.index') }}" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group">
                            Mulai Menabung
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group">
                            Daftar Sekarang
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                
                <!-- Tabungan Process -->
                <div class="order-1 lg:order-2">
                    <div class="bg-linear-to-br from-[#674c1d]/5 to-[#d4af37]/5 rounded-3xl p-8">
                        <h3 class="text-2xl font-bold text-primary mb-8 text-center">Cara Kerja Tabungan</h3>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">1</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Daftar & Verifikasi</h4>
                                    <p class="text-gray-600 text-sm">Lengkapi data diri dan tunggu verifikasi dari admin</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">2</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Ajukan Setoran</h4>
                                    <p class="text-gray-600 text-sm">Pilih metode setoran: transfer atau tunai dengan janji temu</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">3</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Admin Approve</h4>
                                    <p class="text-gray-600 text-sm">Admin akan memverifikasi dan menyetujui pengajuan Anda</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">✓</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1 border-2 border-green-200">
                                    <h4 class="font-bold text-green-600 mb-1">Saldo Bertambah</h4>
                                    <p class="text-gray-600 text-sm">Setoran masuk dan saldo tabungan Anda bertambah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Pinjaman Section -->
    <section id="pinjaman" class="py-24 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-[#faf9f6] to-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Pinjaman Process -->
                <div>
                    <div class="bg-white rounded-3xl p-8 shadow-2xl">
                        <h3 class="text-2xl font-bold text-primary mb-8 text-center">Proses Pengajuan Pinjaman</h3>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">1</div>
                                <div class="bg-[#674c1d]/5 rounded-xl p-4 flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Isi Form Pengajuan</h4>
                                    <p class="text-gray-600 text-sm">Input nominal dan durasi pinjaman (1-24 bulan)</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">2</div>
                                <div class="bg-[#674c1d]/5 rounded-xl p-4 flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Lihat Simulasi</h4>
                                    <p class="text-gray-600 text-sm">Sistem menampilkan tabel simulasi angsuran per bulan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">3</div>
                                <div class="bg-[#674c1d]/5 rounded-xl p-4 flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Review & Approve</h4>
                                    <p class="text-gray-600 text-sm">Admin review pengajuan dan set bunga otomatis</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">✓</div>
                                <div class="bg-green-50 rounded-xl p-4 flex-1 border-2 border-green-200">
                                    <h4 class="font-bold text-green-600 mb-1">Dana Cair</h4>
                                    <p class="text-gray-600 text-sm">Dana dicairkan dan bayar angsuran sesuai jadwal</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bunga Info -->
                        <div class="mt-8 p-4 bg-[#d4af37]/10 rounded-xl border-l-4 border-[#d4af37]">
                            <h4 class="font-bold text-[#674c1d] mb-2">💡 Info Bunga Pinjaman</h4>
                            <p class="text-sm text-gray-700 mb-2">Bunga otomatis disesuaikan berdasarkan durasi:</p>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">1-3 bulan:</span><span class="font-bold text-[#674c1d]">10%</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">4-6 bulan:</span><span class="font-bold text-[#674c1d]">12%</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">7-9 bulan:</span><span class="font-bold text-[#674c1d]">14%</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">10-12 bulan:</span><span class="font-bold text-[#674c1d]">16%</span></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">*Bunga dibagi ke setiap angsuran bulanan</p>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div>
                    <div class="relative">
                        <div class="absolute top-0 left-0 w-32 h-32 bg-[#d4af37]/20 rounded-full filter blur-2xl"></div>
                        <div class="relative bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                            <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center mb-6 icon-float">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-primary mb-4">Pinjaman</h3>
                            <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                                Solusi pinjaman dengan proses cepat, bunga ringan, dan syarat yang mudah untuk berbagai kebutuhan Anda.
                            </p>
                            
                            <div class="space-y-3 mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                                    <p class="text-gray-700">Durasi fleksibel 1-24 bulan</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                                    <p class="text-gray-700">Bunga transparan tanpa biaya tersembunyi</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                                    <p class="text-gray-700">Denda hanya 0,3% per hari jika telat</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                                    <p class="text-gray-700">Simulasi angsuran real-time</p>
                                </div>
                            </div>
                            
                            @if (auth()->check() && auth()->user()->role === 'nasabah')
                                <a href="{{ route('nasabah.pinjaman.index') }}" class="inline-flex items-center px-8 py-4 bg-[#8b6f2f] text-white rounded-xl hover:bg-[#674c1d] transition-all font-semibold">
                                    Ajukan Pinjaman
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-[#8b6f2f] text-white rounded-xl hover:bg-[#674c1d] transition-all font-semibold">
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
        </div>
    </section>
    
    <!-- Deposito Section -->
    <section id="deposito" class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block px-4 py-2 bg-[#d4af37]/10 rounded-full mb-6">
                        <span class="text-[#674c1d] font-semibold text-sm">📊 Layanan Deposito</span>
                    </div>
                    <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-6">
                        Investasi Aman dengan Return Menarik
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Investasi jangka panjang dengan bunga yang lebih tinggi dari tabungan. Pilih tenor yang sesuai dengan rencana investasi Anda.
                    </p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Return Investasi Tinggi</h4>
                                <p class="text-gray-600 text-sm">Bunga lebih tinggi dibanding tabungan reguler</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Pilihan Tenor Fleksibel</h4>
                                <p class="text-gray-600 text-sm">Berbagai pilihan jangka waktu sesuai kebutuhan</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Bunga Dihitung Harian</h4>
                                <p class="text-gray-600 text-sm">Maksimalkan keuntungan dengan perhitungan harian</p>
                            </div>
                        </div>
                    </div>
                    
                    @if (auth()->check() && auth()->user()->role === 'nasabah')
                        <a href="#" class="mt-8 inline-flex items-center px-8 py-4 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group">
                            Ajukan Deposito
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="mt-8 inline-flex items-center px-8 py-4 bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white rounded-xl hover:shadow-xl transition-all font-semibold group">
                            Daftar Sekarang
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                
                <div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center mb-6 icon-float">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-primary mb-4">Deposito</h3>
                        <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                            Investasi aman untuk masa depan dengan return yang menarik. Tersedia berbagai pilihan tenor sesuai rencana keuangan Anda.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-[#674c1d]/5 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-[#674c1d] mb-1">Tinggi</div>
                                <p class="text-sm text-gray-600">Return Investasi</p>
                            </div>
                            <div class="bg-[#d4af37]/5 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-[#d4af37] mb-1">100%</div>
                                <p class="text-sm text-gray-600">Aman</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Multiple pilihan tenor</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Pencairan mudah saat jatuh tempo</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Bunga kompetitif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Gadai Section -->
    <section id="gadai" class="py-24 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-white to-[#faf9f6]">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="w-20 h-20 gradient-primary rounded-2xl flex items-center justify-center mb-6 icon-float">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-primary mb-4">Gadai</h3>
                        <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                            Solusi cepat untuk kebutuhan dana mendesak dengan jaminan barang berharga. Proses mudah, bunga terjangkau, barang aman.
                        </p>
                        
                        <div class="p-4 bg-[#674c1d]/5 rounded-xl mb-6">
                            <h4 class="font-bold text-[#674c1d] mb-3">Jenis Barang yang Diterima:</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                                    <span class="text-gray-700">Emas & Perhiasan</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                                    <span class="text-gray-700">Elektronik</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                                    <span class="text-gray-700">Kendaraan</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                                    <span class="text-gray-700">Barang Berharga Lain</span>
                                </div>
                            </div>
                        </div>
                        
                        @if (auth()->check() && auth()->user()->role === 'nasabah')
                            <a href="#" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                                Ajukan Gadai
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:shadow-xl transition-all font-semibold">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                
                <div>
                    <div class="bg-linear-to-br from-[#8b6f2f]/5 to-[#d4af37]/5 rounded-3xl p-8">
                        <h3 class="text-2xl font-bold text-primary mb-8 text-center">Proses Gadai</h3>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">1</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Pilih Barang</h4>
                                    <p class="text-gray-600 text-sm">Tentukan jenis barang berharga yang akan digadaikan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">2</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Ajukan & Upload Foto</h4>
                                    <p class="text-gray-600 text-sm">Isi form dengan detail barang dan nominal yang diinginkan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">3</div>
                                <div class="bg-white rounded-xl p-4 shadow-md flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">Penilaian Admin</h4>
                                    <p class="text-gray-600 text-sm">Admin menilai barang dan approve pengajuan</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] text-white rounded-xl flex items-center justify-center font-bold shrink-0 text-lg">✓</div>
                                <div class="bg-green-50 rounded-xl p-4 flex-1 border-2 border-green-200">
                                    <h4 class="font-bold text-green-600 mb-1">Terima Dana</h4>
                                    <p class="text-gray-600 text-sm">Dana dicairkan, barang tersimpan aman</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Bandingkan Layanan Kami
                </h2>
                <p class="text-xl text-gray-600">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tabungan -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 hover:border-[#674c1d] transition-all">
                    <div class="w-14 h-14 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Tabungan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Bunga:</span>
                            <span class="font-semibold text-[#674c1d]">Harian</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Penarikan:</span>
                            <span class="font-semibold text-gray-900">Fleksibel</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Minimal:</span>
                            <span class="font-semibold text-gray-900">Bebas</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Cocok untuk:</span>
                            <span class="font-semibold text-gray-900">Harian</span>
                        </div>
                    </div>
                </div>

                <!-- Pinjaman -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-[#8b6f2f] hover:shadow-2xl transition-all">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-[#d4af37] text-white rounded-full text-xs font-bold">POPULER</span>
                    </div>
                    <div class="w-14 h-14 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Pinjaman</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Bunga:</span>
                            <span class="font-semibold text-[#8b6f2f]">10-24%</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-semibold text-gray-900">1-24 bln</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Minimal:</span>
                            <span class="font-semibold text-gray-900">100K</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Cocok untuk:</span>
                            <span class="font-semibold text-gray-900">Usaha</span>
                        </div>
                    </div>
                </div>

                <!-- Deposito -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 hover:border-[#d4af37] transition-all">
                    <div class="w-14 h-14 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Deposito</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Bunga:</span>
                            <span class="font-semibold text-[#d4af37]">Tinggi</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Tenor:</span>
                            <span class="font-semibold text-gray-900">Tertentu</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Minimal:</span>
                            <span class="font-semibold text-gray-900">Variatif</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Cocok untuk:</span>
                            <span class="font-semibold text-gray-900">Investasi</span>
                        </div>
                    </div>
                </div>

                <!-- Gadai -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 hover:border-[#4a3514] transition-all">
                    <div class="w-14 h-14 bg-linear-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Gadai</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Bunga:</span>
                            <span class="font-semibold text-[#4a3514]">Kompetitif</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Jangka:</span>
                            <span class="font-semibold text-gray-900">Fleksibel</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Barang:</span>
                            <span class="font-semibold text-gray-900">Aman</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Cocok untuk:</span>
                            <span class="font-semibold text-gray-900">Darurat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #4a3514 0%, #674c1d 50%, #8b6f2f 100%);">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Siap Memulai Perjalanan Finansial Anda?
            </h2>
            <p class="text-xl text-white/90 mb-8">
                Bergabunglah dengan ribuan anggota yang telah mempercayakan kebutuhan keuangan mereka kepada Koperasi Majakara
            </p>
            @if (!auth()->check())
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary rounded-xl font-semibold hover:bg-gray-100 transition shadow-lg hover:shadow-2xl">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition">
                        Masuk ke Akun
                    </a>
                </div>
            @else
                @php
                    $user = auth()->user();
                    $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                @endphp
                <a href="{{ $dashboardUrl }}" class="inline-block px-8 py-4 bg-white text-primary rounded-xl font-semibold hover:bg-gray-100 transition shadow-lg">
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
                
                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Layanan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#tabungan" class="hover:text-white transition">Tabungan</a></li>
                        <li><a href="#pinjaman" class="hover:text-white transition">Pinjaman</a></li>
                        <li><a href="#deposito" class="hover:text-white transition">Deposito</a></li>
                        <li><a href="#gadai" class="hover:text-white transition">Gadai</a></li>
                    </ul>
                </div>
                
                <!-- Perusahaan -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="{{ route('landing.keuntungan') }}" class="hover:text-white transition">Keuntungan</a></li>
                        <li><a href="{{ route('landing.testimoni') }}" class="hover:text-white transition">Testimoni</a></li>
                        <li><a href="{{ route('landing.faq') }}" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Tentang Kami</a></li>
                    </ul>
                </div>
                
                <!-- Hubungi Kami -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li>Email: info@majakara.com</li>
                        <li>Telp: (021) 1234-5678</li>
                        <li>WA: 0812-3456-7890</li>
                        <li>Senin-Jumat: 08:00-17:00</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/20 pt-8 text-center">
                <p class="text-white/80 text-sm">
                    Copyright © 2026 Koperasi Majakara. Hak cipta dilindungi undang-undang.
                </p>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
