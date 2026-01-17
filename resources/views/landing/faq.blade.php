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
                    <a href="{{ route('landing.layanan') }}" class="text-gray-700 hover:text-primary transition">Layanan</a>
                    <a href="{{ route('landing.keuntungan') }}" class="text-gray-700 hover:text-primary transition">Keuntungan</a>
                    <a href="{{ route('landing.testimoni') }}" class="text-gray-700 hover:text-primary transition">Testimoni</a>
                    <a href="{{ route('landing.faq') }}" class="text-primary font-semibold">FAQ</a>
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
                <a href="{{ route('landing.layanan') }}" class="block text-gray-700 hover:text-primary">Layanan</a>
                <a href="{{ route('landing.keuntungan') }}" class="block text-gray-700 hover:text-primary">Keuntungan</a>
                <a href="{{ route('landing.testimoni') }}" class="block text-gray-700 hover:text-primary">Testimoni</a>
                <a href="{{ route('landing.faq') }}" class="block text-primary font-semibold">FAQ</a>
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
                Pertanyaan yang Sering Diajukan
            </h1>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                Temukan jawaban untuk pertanyaan Anda tentang Koperasi Majakara dan layanan yang kami sediakan
            </p>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-4xl mx-auto">
            <div class="space-y-4">
                <!-- FAQ 1: Pendaftaran -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara mendaftar menjadi anggota?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed mb-3">
                            Anda dapat mendaftar melalui website kami dengan mengikuti langkah-langkah berikut:
                        </p>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                            <li>Klik tombol "Daftar" di halaman utama</li>
                            <li>Isi formulir registrasi dengan data lengkap (nama, email, nomor HP, password)</li>
                            <li>Upload foto KTP untuk verifikasi (sistem akan otomatis membaca data dari KTP)</li>
                            <li>Verifikasi OTP yang dikirim ke nomor HP Anda</li>
                            <li>Buat PIN 6 digit untuk keamanan akun</li>
                            <li>Tunggu verifikasi dari admin (biasanya 1-2 hari kerja)</li>
                        </ol>
                        <p class="text-gray-600 leading-relaxed mt-3">
                            Setelah verifikasi selesai, Anda akan mendapatkan akses penuh ke semua layanan koperasi.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 2: Bunga Tabungan -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Berapa bunga yang diberikan untuk tabungan?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Bunga tabungan kami sangat kompetitif dan dihitung berdasarkan saldo rata-rata harian. Bunga akan ditambahkan ke saldo tabungan Anda secara berkala. Untuk informasi detail mengenai suku bunga terkini, silakan hubungi customer service kami atau login ke dashboard untuk melihat informasi terbaru.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 3: Syarat Pinjaman -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Apa saja syarat untuk mengajukan pinjaman?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed mb-3">
                            Syarat utama untuk mengajukan pinjaman:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Menjadi anggota aktif minimal 6 bulan</li>
                            <li>Memiliki penghasilan tetap</li>
                            <li>Menyiapkan dokumen: slip gaji, KTP, dan dokumen pendukung lainnya</li>
                            <li>Memiliki riwayat tabungan yang baik</li>
                            <li>Mengisi form pengajuan dengan lengkap</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-3">
                            Setelah pengajuan, admin akan melakukan review dan verifikasi. Proses approval biasanya memakan waktu 2-3 hari kerja.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 4: Biaya Administrasi -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Apakah ada biaya administrasi?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Ada biaya administrasi yang sangat terjangkau untuk berbagai layanan. Biaya ini transparan dan akan diinformasikan sebelum Anda menggunakan layanan. Untuk anggota aktif dengan riwayat transaksi yang baik, beberapa biaya administrasi dapat dikurangi atau dihapuskan. Detail biaya administrasi dapat dilihat di dashboard setelah login.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 5: Transaksi Online -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara melakukan transaksi online?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed mb-3">
                            Setelah menjadi anggota dan verifikasi selesai, Anda dapat melakukan transaksi online melalui dashboard:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li><strong>Setoran Tabungan:</strong> Pilih metode transfer (upload bukti) atau tunai (janji temu)</li>
                            <li><strong>Penarikan Tabungan:</strong> Ajukan penarikan melalui dashboard, admin akan approve</li>
                            <li><strong>Pengajuan Pinjaman:</strong> Isi form pengajuan online dengan nominal dan durasi</li>
                            <li><strong>Deposito:</strong> Ajukan deposito dengan pilihan tenor yang tersedia</li>
                            <li><strong>Gadai:</strong> Ajukan gadai dengan detail barang yang akan digadaikan</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-3">
                            Semua transaksi dapat dilakukan secara online 24/7 melalui dashboard Anda. Status pengajuan dapat dilacak secara real-time.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 6: Deposito -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara kerja deposito?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Deposito adalah investasi dengan jangka waktu tertentu. Anda menyetor sejumlah dana untuk periode tertentu (tenor), dan akan mendapatkan bunga yang lebih tinggi dibanding tabungan biasa. Bunga dihitung harian dan dapat dicairkan saat jatuh tempo. Tersedia berbagai pilihan tenor sesuai kebutuhan investasi Anda.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 7: Gadai -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana sistem gadai bekerja?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed mb-3">
                            Sistem gadai memungkinkan Anda mendapatkan pinjaman cepat dengan jaminan barang berharga:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Pilih jenis barang yang akan digadaikan</li>
                            <li>Ajukan pengajuan dengan nominal yang diinginkan</li>
                            <li>Admin akan menilai barang dan menentukan nilai pinjaman</li>
                            <li>Setelah approve, barang disimpan aman di tempat kami</li>
                            <li>Dana dicairkan ke rekening Anda</li>
                            <li>Bayar bunga sesuai jadwal atau lunasi sebelum jatuh tempo</li>
                            <li>Barang dapat ditebus setelah pelunasan</li>
                        </ul>
                    </div>
                </div>
                
                <!-- FAQ 8: Keamanan -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana keamanan data dan dana saya?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed mb-3">
                            Keamanan adalah prioritas utama kami:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Semua data dienkripsi dengan standar keamanan tinggi</li>
                            <li>Sistem verifikasi multi-layer untuk setiap transaksi penting</li>
                            <li>PIN verification untuk login dan transaksi sensitif</li>
                            <li>OTP verification untuk registrasi dan perubahan data penting</li>
                            <li>Monitoring sistem 24/7 untuk mendeteksi aktivitas mencurigakan</li>
                            <li>Backup data secara berkala untuk mencegah kehilangan data</li>
                        </ul>
                    </div>
                </div>
                
                <!-- FAQ 9: Angsuran -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana sistem angsuran pinjaman?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Sistem angsuran kami sangat fleksibel. Anda dapat memilih antara angsuran bulanan atau mingguan sesuai kemampuan finansial. Setiap angsuran terdiri dari pokok pinjaman dan bunga. Jadwal angsuran dapat dilihat di dashboard, dan Anda akan mendapat notifikasi sebelum jatuh tempo. Pembayaran dapat dilakukan melalui transfer atau datang langsung ke kantor.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 10: Kontak -->
                <div class="faq-item bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <button class="faq-toggle w-full flex justify-between items-center text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-lg text-primary">Bagaimana cara menghubungi customer service?</span>
                        <svg class="w-6 h-6 text-primary transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content mt-4">
                        <p class="text-gray-600 leading-relaxed">
                            Anda dapat menghubungi customer service kami melalui berbagai cara:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 mt-3">
                            <li>Email: support@koperasimajakara.com</li>
                            <li>Telepon: (021) 1234-5678</li>
                            <li>WhatsApp: 0812-3456-7890</li>
                            <li>Kunjungi kantor kami di jam kerja (Senin-Jumat: 08:00-17:00)</li>
                            <li>Chat support melalui dashboard (jika sudah menjadi anggota)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 relative" style="background: linear-gradient(135deg, #4a3514 0%, #674c1d 100%);">
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-6">
                Masih Ada Pertanyaan?
            </h2>
            <p class="text-xl text-white mb-8">
                Hubungi customer service kami untuk mendapatkan jawaban lebih detail
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:support@koperasimajakara.com" class="px-8 py-4 bg-white text-primary rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                    Hubungi Kami
                </a>
                @if (!auth()->check())
                    <a href="{{ route('register') }}" class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white/20 transition">
                        Daftar Sekarang
                    </a>
                @endif
            </div>
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
    </script>
</body>
</html>
