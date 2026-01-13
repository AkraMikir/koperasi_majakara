<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Majakara - Solusi Keuangan Terpercaya</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
        </style>
    @endif
    
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
        
        .gradient-accent {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        
        .text-primary {
            color: #674c1d;
        }
        
        .bg-primary {
            background-color: #674c1d;
        }
        
        .border-primary {
            border-color: #674c1d;
        }
        
        .hover-primary:hover {
            background-color: #674c1d;
            color: white;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(103, 76, 29, 0.15);
        }
        
        .faq-item {
            transition: all 0.3s ease;
        }
        
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .faq-item.active .faq-content {
            max-height: 500px;
        }
        
        .testimonial-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(250, 249, 246, 0.9) 100%);
            backdrop-filter: blur(10px);
        }
        
        /* Animation Classes */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .fade-in {
            opacity: 0;
            transition: opacity 0.8s ease-out;
        }
        
        .fade-in.visible {
            opacity: 1;
        }
        
        .scale-in {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        
        .scale-in.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        /* Stagger animation delays */
        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
        .delay-400 { transition-delay: 0.4s; }
        .delay-500 { transition-delay: 0.5s; }
    </style>
    
</head>
<body class="bg-[#faf9f6] text-gray-800 antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Kospin Majakara" class="h-24 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                    <span class="text-xl font-bold text-primary">Kospin Majakara</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-700 hover:text-primary transition">Beranda</a>
                    <a href="#layanan" class="text-gray-700 hover:text-primary transition">Layanan</a>
                    <a href="#keuntungan" class="text-gray-700 hover:text-primary transition">Keuntungan</a>
                    <a href="#testimoni" class="text-gray-700 hover:text-primary transition">Testimoni</a>
                    <a href="#faq" class="text-gray-700 hover:text-primary transition">FAQ</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-primary hover:bg-primary hover:text-white rounded-lg transition">
                                Dashboard
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
                        @endauth
                    @else
                        <a href="#" class="px-5 py-2.5 text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">
                            Login
                        </a>
                        <a href="#" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition shadow-md font-medium">
                            Register
                        </a>
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
                <a href="#beranda" class="block text-gray-700 hover:text-primary">Beranda</a>
                <a href="#layanan" class="block text-gray-700 hover:text-primary">Layanan</a>
                <a href="#keuntungan" class="block text-gray-700 hover:text-primary">Keuntungan</a>
                <a href="#testimoni" class="block text-gray-700 hover:text-primary">Testimoni</a>
                <a href="#faq" class="block text-gray-700 hover:text-primary">FAQ</a>
                <div class="pt-4 border-t space-y-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-4 py-2.5 text-center text-primary border border-primary rounded-lg font-medium">
                                Dashboard
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
                        @endauth
                    @else
                        <a href="#" class="block px-4 py-2.5 text-center text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition font-medium">
                            Login
                        </a>
                        <a href="#" class="block px-4 py-2.5 text-center bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium shadow-md">
                            Register
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section id="beranda" class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left fade-in-up visible">
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6 leading-tight">
                        Solusi Keuangan<br>
                        <span class="gradient-text">Terpercaya</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Koperasi Majakara hadir untuk membantu mewujudkan impian finansial Anda dengan layanan terbaik dan terpercaya.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#layanan" class="px-8 py-4 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition shadow-lg hover:shadow-xl">
                            Jelajahi Layanan
                        </a>
                        <a href="#keuntungan" class="px-8 py-4 border-2 border-primary text-primary rounded-lg font-semibold hover:bg-primary hover:text-white transition">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                
                <!-- Hero Image/Illustration -->
                <div class="relative scale-in visible delay-200">
                    <div class="relative z-10 rounded-3xl p-8 backdrop-blur-sm" style="background: linear-gradient(135deg, rgba(103, 76, 29, 0.1) 0%, rgba(212, 175, 55, 0.1) 100%);">
                        <div class="bg-white rounded-2xl p-8 shadow-2xl">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="gradient-primary rounded-xl p-6 text-white">
                                    <div class="text-3xl font-bold mb-2">100%</div>
                                    <div class="text-sm opacity-90">Terpercaya</div>
                                </div>
                                <div class="gradient-accent rounded-xl p-6 text-white">
                                    <div class="text-3xl font-bold mb-2">24/7</div>
                                    <div class="text-sm opacity-90">Layanan</div>
                                </div>
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);" class="rounded-xl p-6 text-white col-span-2">
                                    <div class="text-3xl font-bold mb-2">10K+</div>
                                    <div class="text-sm opacity-90">Anggota Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-4 -right-4 w-64 h-64 rounded-full blur-3xl" style="background-color: rgba(103, 76, 29, 0.2);"></div>
                    <div class="absolute -bottom-4 -left-4 w-64 h-64 rounded-full blur-3xl" style="background-color: rgba(212, 175, 55, 0.2);"></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section id="layanan" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in-up animate-on-scroll">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Apa Saja yang Dapat Dilakukan di Koperasi Majakara?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Kami menyediakan berbagai layanan keuangan yang lengkap untuk memenuhi kebutuhan Anda
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Tabungan -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-100">
                    <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Tabungan</h3>
                    <p class="text-gray-600 mb-6">
                        Menabung dengan aman dan nyaman dengan bunga kompetitif untuk masa depan yang lebih baik.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary font-semibold hover:underline">
                        Selengkapnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Pinjaman -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-200">
                    <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Pinjaman</h3>
                    <p class="text-gray-600 mb-6">
                        Pinjaman dengan proses cepat, bunga ringan, dan syarat yang mudah untuk berbagai kebutuhan.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary font-semibold hover:underline">
                        Selengkapnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Deposito -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-300">
                    <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Deposito</h3>
                    <p class="text-gray-600 mb-6">
                        Investasi aman dengan return yang menarik untuk jangka waktu tertentu sesuai kebutuhan Anda.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary font-semibold hover:underline">
                        Selengkapnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Gadai -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-400">
                    <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4">Gadai</h3>
                    <p class="text-gray-600 mb-6">
                        Solusi cepat untuk kebutuhan dana dengan jaminan barang berharga yang Anda miliki.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary font-semibold hover:underline">
                        Selengkapnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Benefits Section -->
    <section id="keuntungan" class="py-20 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-primary/5 to-accent/5">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in-up animate-on-scroll">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Keuntungan Memakai Majakara Koperasi
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Mengapa memilih kami sebagai partner keuangan Anda?
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Benefit 1 -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg text-center fade-in-up animate-on-scroll delay-100">
                    <div class="w-20 h-20 gradient-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Aman & Terpercaya</h3>
                    <p class="text-gray-600">
                        Keamanan dana Anda adalah prioritas utama kami dengan sistem keamanan berlapis.
                    </p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg text-center fade-in-up animate-on-scroll delay-200">
                    <div class="w-20 h-20 gradient-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Proses Cepat</h3>
                    <p class="text-gray-600">
                        Layanan cepat dan efisien dengan proses yang mudah tanpa ribet.
                    </p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg text-center fade-in-up animate-on-scroll delay-300">
                    <div class="w-20 h-20 gradient-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Bunga Kompetitif</h3>
                    <p class="text-gray-600">
                        Bunga yang menarik dan kompetitif untuk tabungan dan investasi Anda.
                    </p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="card-hover bg-white rounded-2xl p-8 shadow-lg text-center fade-in-up animate-on-scroll delay-400">
                    <div class="w-20 h-20 gradient-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Layanan 24/7</h3>
                    <p class="text-gray-600">
                        Akses layanan kapan saja dan di mana saja melalui platform digital kami.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section id="testimoni" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in-up animate-on-scroll">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Bangun Kepercayaan untuk Koperasi Majakara
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Dengarkan pengalaman nyata dari anggota kami
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-100">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Ahmad Rizki" class="w-28 h-28 object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Ahmad Rizki</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2020</p>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        "Banyak keuntungan yang saya dapatkan sejak bergabung dengan Koperasi Majakara. Pelayanan yang ramah, proses cepat, dan bunga yang kompetitif membuat saya sangat puas."
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-200">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Siti Nurhaliza" class="w-28 h-28 object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Siti Nurhaliza</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2021</p>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        "Proses pinjaman sangat mudah dan cepat. Saya sangat terbantu dengan layanan Koperasi Majakara untuk kebutuhan usaha saya."
                    </p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-300">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Budi Santoso" class="w-20 h-20 object-contain">
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Budi Santoso</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2019</p>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        "Saya sudah menggunakan layanan deposito selama 3 tahun dan sangat puas dengan return yang diberikan. Koperasi Majakara memang terpercaya!"
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section id="faq" class="py-20 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-primary/5 to-accent/5">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 fade-in-up animate-on-scroll">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xl text-gray-600">
                    Temukan jawaban untuk pertanyaan Anda di sini
                </p>
            </div>
            
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara mendaftar menjadi anggota?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Anda dapat mendaftar dengan mengunjungi kantor kami atau melalui website. Siapkan KTP, NPWP (jika ada), dan dokumen pendukung lainnya. Proses pendaftaran biasanya selesai dalam 1-2 hari kerja.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-200">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Berapa bunga yang diberikan untuk tabungan?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Bunga tabungan kami sangat kompetitif dan dihitung berdasarkan saldo rata-rata. Silakan hubungi customer service kami untuk informasi detail mengenai suku bunga terkini.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-300">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Apa saja syarat untuk mengajukan pinjaman?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Syarat utama adalah menjadi anggota aktif minimal 6 bulan, memiliki penghasilan tetap, dan menyiapkan dokumen seperti slip gaji, KTP, dan dokumen pendukung lainnya sesuai jenis pinjaman.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-400">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Apakah ada biaya administrasi?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Ada biaya administrasi yang sangat terjangkau untuk berbagai layanan. Biaya ini transparan dan akan diinformasikan sebelum Anda menggunakan layanan. Untuk anggota aktif, beberapa biaya administrasi dapat dikurangi.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 5 -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100 fade-in-up animate-on-scroll delay-500">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara melakukan transaksi online?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Setelah menjadi anggota, Anda akan mendapatkan akses ke aplikasi mobile dan website. Login menggunakan nomor anggota dan password yang diberikan. Semua transaksi dapat dilakukan secara online 24/7.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-primary text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Logo & Description -->
                <div class="md:col-span-1 fade-in-up animate-on-scroll delay-100">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="h-24 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                        <span class="text-xl font-bold">Koperasi Majakara</span>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Solusi keuangan terpercaya untuk mewujudkan impian finansial Anda.
                    </p>
                </div>
                
                <!-- Syarat dan Ketentuan -->
                <div class="fade-in-up animate-on-scroll delay-200">
                    <h3 class="font-bold text-lg mb-4">Syarat dan Ketentuan</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#" class="hover:text-white transition">Ketentuan Umum</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat Keanggotaan</a></li>
                        <li><a href="#" class="hover:text-white transition">Ketentuan Layanan</a></li>
                    </ul>
                </div>
                
                <!-- Info Seabank -->
                <div class="fade-in-up animate-on-scroll delay-300">
                    <h3 class="font-bold text-lg mb-4">Info Seabank</h3>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Berita & Update</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Laporan Tahunan</a></li>
                    </ul>
                </div>
                
                <!-- Hubungi Kami -->
                <div class="fade-in-up animate-on-scroll delay-400">
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
        
        // FAQ Toggle
        function toggleFaq(button) {
            const faqItem = button.closest('.faq-item');
            const icon = button.querySelector('svg');
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
                item.querySelector('svg').classList.remove('rotate-180');
            });
            
            // Toggle current FAQ
            if (!isActive) {
                faqItem.classList.add('active');
                icon.classList.add('rotate-180');
            }
        }
        
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
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
        
        // Scroll Animation dengan Intersection Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Unobserve setelah animasi untuk performa
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe semua elemen dengan class animate-on-scroll
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            animateElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
