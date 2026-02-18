<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testimoni - Koperasi Majakara</title>
    
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
        
        .testimonial-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 249, 246, 0.95) 100%);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .testimonial-card:hover {
            transform: translateY(-12px) rotate(-1deg);
            box-shadow: 0 30px 60px -15px rgba(103, 76, 29, 0.3);
        }
        
        .star-rating {
            display: flex;
            gap: 4px;
        }
        
        .quote-icon {
            opacity: 0.1;
            position: absolute;
            font-size: 120px;
            font-family: Georgia, serif;
            color: var(--primary);
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
                    <a href="{{ route('landing.testimoni') }}" class="text-primary font-semibold border-b-2 border-primary">Testimoni</a>
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
                <a href="{{ route('landing.keuntungan') }}" class="block text-gray-700 hover:text-primary">Keuntungan</a>
                <a href="{{ route('landing.testimoni') }}" class="block text-primary font-semibold">Testimoni</a>
                <a href="{{ route('landing.faq') }}" class="block text-gray-700 hover:text-primary">FAQ</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #faf9f6 0%, #ffffff 100%);">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 right-10 w-96 h-96 bg-primary rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-96 h-96 bg-accent rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 bg-primary/10 rounded-full mb-6">
                    <span class="text-primary font-semibold text-sm">💬 Kata Mereka</span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6">
                    Testimoni<br>
                    <span style="background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Anggota</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    Dengarkan pengalaman nyata dari anggota kami yang telah mempercayakan kebutuhan keuangan mereka kepada Koperasi Majakara
                </p>
            </div>

            <!-- Rating Summary -->
            <div class="bg-white rounded-3xl p-12 shadow-2xl border border-gray-100 mb-16">
                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-6xl font-bold mb-3" style="background: linear-gradient(135deg, #674c1d 0%, #d4af37 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">4.9/5.0</div>
                        <div class="flex justify-center mb-3">
                            <div class="flex gap-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600">Rating Rata-rata</p>
                    </div>
                    <div>
                        <div class="text-6xl font-bold text-[#674c1d] mb-3">1,250+</div>
                        <p class="text-gray-600 font-semibold mb-1">Testimoni Positif</p>
                        <p class="text-sm text-gray-500">Dari anggota kami</p>
                    </div>
                    <div>
                        <div class="text-6xl font-bold text-[#d4af37] mb-3">98%</div>
                        <p class="text-gray-600 font-semibold mb-1">Akan Merekomendasikan</p>
                        <p class="text-sm text-gray-500">Kepada teman & keluarga</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Grid -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            AR
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Ahmad Rizki</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2020</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Banyak keuntungan yang saya dapatkan sejak bergabung dengan Koperasi Majakara. Pelayanan yang ramah, proses cepat, dan bunga yang kompetitif membuat saya sangat puas."
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Tabungan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#8b6f2f] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Pinjaman</span>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            SN
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Siti Nurhaliza</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2021</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Proses pinjaman sangat mudah dan cepat. Saya sangat terbantu dengan layanan Koperasi Majakara untuk kebutuhan usaha saya. Bunga ringan dan angsuran fleksibel!"
                    </p>
                    <div class="flex items-center pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Pinjaman</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            BS
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Budi Santoso</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2019</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Saya sudah menggunakan layanan deposito selama 3 tahun dan sangat puas dengan return yang diberikan. Koperasi Majakara memang terpercaya! Proses pencairan juga mudah dan cepat."
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Deposito</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Tabungan</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#674c1d] to-[#d4af37] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            RW
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Rina Wati</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2022</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Layanan gadai sangat membantu saat saya butuh dana cepat. Prosesnya mudah, bunga terjangkau, dan barang saya aman tersimpan. Sangat direkomendasikan!"
                    </p>
                    <div class="flex items-center pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Gadai</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#8b6f2f] to-[#674c1d] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            DK
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Dedi Kurniawan</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2020</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Sistem online yang sangat memudahkan. Saya bisa cek saldo, ajukan pinjaman, dan lihat riwayat transaksi kapan saja. Pelayanan admin juga sangat responsif dan membantu."
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Tabungan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#8b6f2f] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Pinjaman</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#d4af37] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Deposito</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 6 -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#d4af37] to-[#674c1d] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            MS
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Maya Sari</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2023</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Sebagai anggota baru, saya sangat terkesan dengan kemudahan proses registrasi dan verifikasi. Sistemnya user-friendly dan admin sangat membantu menjelaskan setiap langkahnya."
                    </p>
                    <div class="flex items-center pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Tabungan</span>
                        </div>
                    </div>
                </div>

                <!-- More Testimonials -->
                <div class="testimonial-card rounded-2xl p-8 shadow-lg border-2 border-[#d4af37] relative">
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-[#d4af37] text-white rounded-full text-xs font-bold">TOP REVIEW</span>
                    </div>
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            LH
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Linda Hartono</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2018</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Sudah 5 tahun menjadi anggota dan tidak pernah kecewa! Tabungan berkembang pesat, deposito memberikan return yang memuaskan. Sistem digital juga memudahkan semua proses."
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Semua Layanan</span>
                        </div>
                        <span class="text-xs text-gray-500">5+ tahun</span>
                    </div>
                </div>

                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#8b6f2f] to-[#674c1d] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            AP
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Agus Pratama</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2021</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Fitur janji temu sangat membantu untuk transaksi tunai. Admin selalu tepat waktu dan profesional. Sangat recommended untuk yang suka bertransaksi tunai!"
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#674c1d] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Tabungan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#8b6f2f] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Pinjaman</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card rounded-2xl p-8 shadow-lg border border-gray-100 relative">
                    <div class="quote-icon">"</div>
                    <div class="flex items-center mb-6 relative z-10">
                        <div class="w-16 h-16 bg-linear-to-br from-[#d4af37] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-2xl shrink-0">
                            FN
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg text-primary">Fitri Nurjanah</h4>
                            <p class="text-gray-600 text-sm">Anggota Sejak 2023</p>
                            <div class="flex mt-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-6 relative z-10 text-lg">
                        "Dashboard yang informatif dengan simulasi angsuran membuat saya mudah merencanakan keuangan. Feature tabel simulasi pinjaman sangat membantu dalam pengambilan keputusan."
                    </p>
                    <div class="flex items-center pt-4 border-t border-gray-200 relative z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#8b6f2f] rounded-full"></div>
                            <span class="text-sm font-semibold text-primary">Pinjaman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Indicators -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-linear-to-br from-[#faf9f6] to-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-primary mb-4">
                    Dipercaya Ribuan Anggota
                </h2>
                <p class="text-xl text-gray-600">Bukti nyata kepuasan dan kepercayaan anggota kami</p>
            </div>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center border border-gray-100">
                    <div class="text-5xl font-bold mb-3" style="background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">10K+</div>
                    <p class="text-gray-700 font-semibold mb-1">Anggota Aktif</p>
                    <p class="text-sm text-gray-500">Bergabung bersama kami</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center border border-gray-100">
                    <div class="text-5xl font-bold mb-3" style="background: linear-gradient(135deg, #8b6f2f 0%, #d4af37 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">98%</div>
                    <p class="text-gray-700 font-semibold mb-1">Tingkat Kepuasan</p>
                    <p class="text-sm text-gray-500">Rating dari anggota</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center border border-gray-100">
                    <div class="text-5xl font-bold mb-3" style="background: linear-gradient(135deg, #d4af37 0%, #8b6f2f 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">5+</div>
                    <p class="text-gray-700 font-semibold mb-1">Tahun Pengalaman</p>
                    <p class="text-sm text-gray-500">Melayani dengan baik</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-lg text-center border border-gray-100">
                    <div class="text-5xl font-bold mb-3" style="background: linear-gradient(135deg, #674c1d 0%, #d4af37 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">24/7</div>
                    <p class="text-gray-700 font-semibold mb-1">Layanan Digital</p>
                    <p class="text-sm text-gray-500">Tersedia setiap saat</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #4a3514 0%, #674c1d 50%, #8b6f2f 100%);">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Bergabunglah dengan Ribuan Anggota yang Puas
            </h2>
            <p class="text-xl text-white/90 mb-10">
                Dapatkan pengalaman terbaik dalam mengelola keuangan Anda bersama Koperasi Majakara
            </p>
            @if (!auth()->check())
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="px-10 py-5 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-2xl text-lg">
                        Daftar & Rasakan Sendiri
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-5 border-2 border-white text-white rounded-xl font-bold hover:bg-white/10 transition text-lg">
                        Login Sekarang
                    </a>
                </div>
            @else
                @php
                    $user = auth()->user();
                    $dashboardUrl = $user->role === 'nasabah' ? route('nasabah.dashboard') : '/admin/dashboard';
                @endphp
                <a href="{{ $dashboardUrl }}" class="inline-block px-10 py-5 bg-white text-primary rounded-xl font-bold hover:bg-gray-100 transition shadow-2xl text-lg">
                    Masuk ke Dashboard
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
    </script>
</body>
</html>
