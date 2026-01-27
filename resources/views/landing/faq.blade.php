<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ - Koperasi Majakara</title>
    
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        .faq-item {
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            transform: translateX(8px);
        }
        
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
        }
        
        .faq-item.active .faq-content {
            max-height: 800px;
            padding-top: 1rem;
        }
        
        .faq-item.active {
            background: linear-gradient(135deg, rgba(103, 76, 29, 0.05) 0%, rgba(212, 175, 55, 0.05) 100%);
            border-color: var(--primary);
        }
        
        .category-btn.active {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(103, 76, 29, 0.3);
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
                    <a href="{{ route('landing.keuntungan') }}" class="text-gray-700 hover:text-primary transition">Keuntungan</a>
                    <a href="{{ route('landing.testimoni') }}" class="text-gray-700 hover:text-primary transition">Testimoni</a>
                    <a href="{{ route('landing.faq') }}" class="text-primary font-semibold border-b-2 border-primary">FAQ</a>
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
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#4a3514] hover:to-[#674c1d] transition shadow-md font-medium">Register</a>
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
                <a href="{{ route('landing.keuntungan') }}" class="block text-gray-700 hover:text-primary">Keuntungan</a>
                <a href="{{ route('landing.testimoni') }}" class="block text-gray-700 hover:text-primary">Testimoni</a>
                <a href="{{ route('landing.faq') }}" class="block text-primary font-semibold">FAQ</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #faf9f6 0%, #ffffff 100%);">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 left-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-6">
                    <span class="text-primary font-semibold text-sm">❓ Pertanyaan Umum</span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6">
                    Pertanyaan yang<br>
                    <span style="background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Sering Diajukan</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    Temukan jawaban untuk pertanyaan Anda tentang Koperasi Majakara dan layanan yang kami sediakan
                </p>
            </div>

            <!-- Search Box -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <input type="text" id="searchFaq" placeholder="Cari pertanyaan..." 
                        class="w-full px-6 py-4 pl-14 border-2 border-gray-200 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-lg shadow-lg">
                    <svg class="w-6 h-6 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button onclick="filterCategory('all')" class="category-btn active px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="all">
                    Semua
                </button>
                <button onclick="filterCategory('pendaftaran')" class="category-btn px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="pendaftaran">
                    📝 Pendaftaran
                </button>
                <button onclick="filterCategory('layanan')" class="category-btn px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="layanan">
                    🏦 Layanan
                </button>
                <button onclick="filterCategory('keamanan')" class="category-btn px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="keamanan">
                    🛡️ Keamanan
                </button>
                <button onclick="filterCategory('teknis')" class="category-btn px-6 py-3 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-[#674c1d] transition-all" data-category="teknis">
                    ⚙️ Teknis
                </button>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="pb-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="space-y-4" id="faqContainer">
                <!-- FAQ 1: Pendaftaran -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="pendaftaran" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana cara mendaftar menjadi anggota?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Anda dapat mendaftar melalui website kami dengan mengikuti langkah-langkah berikut:
                            </p>
                            <ol class="list-decimal list-inside space-y-3 text-gray-600 mb-4">
                                <li class="pl-2">Klik tombol "Register" di halaman utama</li>
                                <li class="pl-2">Isi formulir dengan data lengkap (nama, email, nomor HP, password)</li>
                                <li class="pl-2">Upload foto KTP - sistem akan otomatis membaca data KTP (OCR)</li>
                                <li class="pl-2">Verifikasi nomor HP dengan OTP WhatsApp</li>
                                <li class="pl-2">Buat PIN 6 digit untuk keamanan transaksi</li>
                                <li class="pl-2">Tunggu verifikasi admin (biasanya 1-2 hari kerja)</li>
                            </ol>
                            <div class="p-4 bg-[#674c1d]/5 rounded-xl">
                                <p class="text-sm text-gray-700">
                                    💡 <strong>Tips:</strong> Pastikan foto KTP jelas dan tidak blur agar proses OCR berhasil!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 2: Bunga Tabungan -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Berapa bunga yang diberikan untuk tabungan?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Bunga tabungan kami sangat kompetitif dan dihitung berdasarkan saldo rata-rata harian. Keuntungan bunga tabungan:
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 mb-4">
                                <li class="pl-2">Dihitung setiap hari untuk maksimalkan return</li>
                                <li class="pl-2">Otomatis ditambahkan ke saldo Anda</li>
                                <li class="pl-2">Transparan dan dapat dilihat di dashboard</li>
                                <li class="pl-2">Tanpa biaya administrasi bulanan</li>
                            </ul>
                            <p class="text-sm text-gray-600">
                                Untuk informasi detail suku bunga terkini, silakan login ke dashboard atau hubungi customer service kami.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 3: Syarat Pinjaman -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Apa saja syarat untuk mengajukan pinjaman?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Syarat untuk mengajukan pinjaman:</p>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-[#674c1d]/5 rounded-xl p-4">
                                    <h4 class="font-semibold text-[#674c1d] mb-2">✓ Syarat Umum</h4>
                                    <ul class="space-y-1 text-sm text-gray-600">
                                        <li>• Anggota aktif Koperasi</li>
                                        <li>• Memiliki KTP aktif</li>
                                        <li>• Memiliki penghasilan tetap</li>
                                    </ul>
                                </div>
                                <div class="bg-[#674c1d]/5 rounded-xl p-4">
                                    <h4 class="font-semibold text-[#674c1d] mb-2">✓ Dokumen</h4>
                                    <ul class="space-y-1 text-sm text-gray-600">
                                        <li>• Foto KTP</li>
                                        <li>• Slip gaji (jika ada)</li>
                                        <li>• Dokumen pendukung lain</li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                                ⏱️ Proses approval biasanya 1-2 hari kerja setelah pengajuan lengkap.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 4: Biaya Administrasi -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Apakah ada biaya administrasi?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 leading-relaxed mb-4">
                                Kami menerapkan biaya administrasi yang transparan dan terjangkau:
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-gray-700"><strong>Tabungan:</strong> Tanpa biaya admin bulanan</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-gray-700"><strong>Pinjaman:</strong> Biaya diinformasikan di awal, tidak ada biaya tersembunyi</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-gray-700"><strong>Deposito:</strong> Sesuai dengan jenis dan tenor yang dipilih</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 5: Transaksi Online -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="teknis" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana cara melakukan transaksi online?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Setelah verifikasi selesai, Anda dapat melakukan transaksi melalui dashboard:</p>
                            <div class="grid gap-4">
                                <div class="flex items-start space-x-3 bg-[#674c1d]/5 p-4 rounded-xl">
                                    <div class="w-8 h-8 bg-[#674c1d] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 mb-1">Setoran Tabungan</h5>
                                        <p class="text-sm text-gray-600">Via transfer (upload bukti) atau tunai (janji temu)</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 bg-[#8b6f2f]/5 p-4 rounded-xl">
                                    <div class="w-8 h-8 bg-[#8b6f2f] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 mb-1">Pengajuan Pinjaman</h5>
                                        <p class="text-sm text-gray-600">Isi form, lihat simulasi, submit dengan PIN</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 bg-[#d4af37]/5 p-4 rounded-xl">
                                    <div class="w-8 h-8 bg-[#d4af37] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900 mb-1">Deposito</h5>
                                        <p class="text-sm text-gray-600">Pilih tenor dan nominal, ajukan online</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-4 bg-green-50 p-3 rounded-lg">
                                ✅ Semua transaksi dapat dilacak status-nya secara real-time di dashboard!
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 6: Keamanan -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="keamanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana keamanan data dan dana saya?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Keamanan adalah prioritas utama kami:</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">Enkripsi SSL/TLS 256-bit</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">PIN verification 6 digit</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">OTP WhatsApp verification</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">Admin approval untuk transaksi</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">Monitoring sistem 24/7</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-700">Backup data berkala</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 7: Deposito -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#d4af37] to-[#8b6f2f] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana cara kerja deposito?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">
                                Deposito adalah investasi dengan jangka waktu tertentu (tenor) yang memberikan return lebih tinggi:
                            </p>
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center space-x-3 p-3 bg-[#d4af37]/10 rounded-lg">
                                    <div class="text-2xl font-bold text-[#d4af37]">1</div>
                                    <p class="text-gray-700">Pilih tenor (jangka waktu) yang sesuai</p>
                                </div>
                                <div class="flex items-center space-x-3 p-3 bg-[#d4af37]/10 rounded-lg">
                                    <div class="text-2xl font-bold text-[#d4af37]">2</div>
                                    <p class="text-gray-700">Setor dana sesuai nominal yang dipilih</p>
                                </div>
                                <div class="flex items-center space-x-3 p-3 bg-[#d4af37]/10 rounded-lg">
                                    <div class="text-2xl font-bold text-[#d4af37]">3</div>
                                    <p class="text-gray-700">Bunga dihitung harian selama tenor</p>
                                </div>
                                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                    <div class="text-2xl font-bold text-green-600">✓</div>
                                    <p class="text-gray-700">Cairkan saat jatuh tempo dengan bunga</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 8: Sistem Gadai -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#674c1d] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana sistem gadai bekerja?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Sistem gadai kami mudah dan aman:</p>
                            <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-4">
                                <li class="pl-2">Pilih jenis barang yang akan digadaikan</li>
                                <li class="pl-2">Ajukan dengan nominal yang diinginkan + upload foto barang</li>
                                <li class="pl-2">Admin menilai barang dan approve</li>
                                <li class="pl-2">Barang disimpan aman di tempat kami</li>
                                <li class="pl-2">Dana dicairkan ke rekening Anda</li>
                                <li class="pl-2">Bayar bunga sesuai jadwal atau lunasi lebih awal</li>
                                <li class="pl-2">Tebus barang setelah pelunasan</li>
                            </ol>
                            <div class="p-4 bg-[#674c1d]/5 rounded-xl border-l-4 border-[#674c1d]">
                                <p class="text-sm text-gray-700">
                                    <strong>Keamanan Barang:</strong> Semua barang gadai disimpan di tempat yang aman dan diasuransikan. Anda dapat tebus kapan saja setelah pelunasan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 9: Angsuran Pinjaman -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="layanan" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana sistem angsuran pinjaman?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Sistem angsuran bulanan yang fleksibel dan transparan:</p>
                            <div class="grid gap-3 mb-4">
                                <div class="p-4 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5 rounded-xl">
                                    <h5 class="font-semibold text-[#674c1d] mb-2">💵 Bunga Tidak Dipotong Di Awal</h5>
                                    <p class="text-sm text-gray-600">Nominal yang Anda terima = nominal yang diajukan. Bunga dibagi ke setiap angsuran bulanan.</p>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-[#8b6f2f]/5 to-[#d4af37]/5 rounded-xl">
                                    <h5 class="font-semibold text-[#8b6f2f] mb-2">📊 Simulasi Real-time</h5>
                                    <p class="text-sm text-gray-600">Lihat tabel simulasi angsuran per bulan sebelum mengajukan.</p>
                                </div>
                                <div class="p-4 bg-gradient-to-r from-[#d4af37]/5 to-[#8b6f2f]/5 rounded-xl">
                                    <h5 class="font-semibold text-[#d4af37] mb-2">⏰ Denda Ringan</h5>
                                    <p class="text-sm text-gray-600">Hanya 0,3% per hari jika telat, dan denda berhenti jika sudah ada pembayaran.</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                                💡 Jadwal angsuran dapat dilihat di dashboard dan Anda akan mendapat notifikasi sebelum jatuh tempo.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ 10: Kontak Customer Service -->
                <div class="faq-item bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 cursor-pointer" data-category="teknis" onclick="toggleFaq(this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#d4af37] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-primary pr-4">Bagaimana cara menghubungi customer service?</span>
                        </div>
                        <svg class="w-6 h-6 text-primary transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-content">
                        <div class="pl-16">
                            <p class="text-gray-600 mb-4">Kami siap membantu Anda melalui berbagai kanal:</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.881 11.881 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">WhatsApp</p>
                                            <p class="text-sm text-gray-600">0812-3456-7890</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Email</p>
                                            <p class="text-sm text-gray-600">support@majakara.com</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Telepon</p>
                                            <p class="text-sm text-gray-600">(021) 1234-5678</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Kantor</p>
                                            <p class="text-sm text-gray-600">Sen-Jum: 08:00-17:00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Help Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-[#faf9f6] to-white">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-3xl p-12 text-white text-center shadow-2xl">
                <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">
                    Tidak Menemukan Jawaban?
                </h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Tim customer service kami siap membantu menjawab pertanyaan Anda
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://wa.me/6281234567890" class="px-8 py-4 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-lg inline-flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.881 11.881 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                        </svg>
                        <span>Hubungi via WhatsApp</span>
                    </a>
                    <a href="mailto:support@majakara.com" class="px-8 py-4 border-2 border-white text-white rounded-xl font-bold hover:bg-white/10 transition inline-flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Kirim Email</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-primary text-white py-16 px-4 sm:px-6 lg:px-8" style="background-color: #4a3514;">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="h-16 w-auto">
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
                        <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Beranda</a></li>
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
        
        // FAQ Toggle
        function toggleFaq(button) {
            const faqItem = button;
            const icon = button.querySelector('svg:last-child');
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
                item.querySelectorAll('svg:last-child').forEach(svg => svg.classList.remove('rotate-180'));
            });
            
            // Toggle current FAQ
            if (!isActive) {
                faqItem.classList.add('active');
                icon.classList.add('rotate-180');
            }
        }

        // Category Filter
        function filterCategory(category) {
            const faqs = document.querySelectorAll('.faq-item');
            const buttons = document.querySelectorAll('.category-btn');
            
            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category === category || (category === 'all' && !btn.dataset.category)) {
                    btn.classList.add('active');
                }
            });
            
            // Filter FAQs
            faqs.forEach(faq => {
                if (category === 'all' || faq.dataset.category === category) {
                    faq.style.display = 'block';
                } else {
                    faq.style.display = 'none';
                }
                faq.classList.remove('active');
                faq.querySelectorAll('svg:last-child').forEach(svg => svg.classList.remove('rotate-180'));
            });
        }

        // Search FAQ
        document.getElementById('searchFaq').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const faqs = document.querySelectorAll('.faq-item');
            
            faqs.forEach(faq => {
                const text = faq.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    faq.style.display = 'block';
                } else {
                    faq.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
