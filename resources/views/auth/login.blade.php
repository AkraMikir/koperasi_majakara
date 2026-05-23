<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #674c1d;
            --primary-light: #8b6f2f;
            --primary-dark: #4a3514;
            --gold: #d4af37;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Gradient animation */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .gradient-animate {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 25%, #d4af37 50%, #8b6f2f 75%, #674c1d 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        /* Float animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(212, 175, 55, 0.3); }
            50% { box-shadow: 0 0 40px rgba(212, 175, 55, 0.6); }
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* Fade in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Background pattern */
        .pattern-bg {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(103, 76, 29, 0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-linear-to-br from-gray-50 via-amber-50/30 to-gray-50 min-h-screen pattern-bg">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full">
            <!-- Main Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden max-w-5xl mx-auto border border-gray-200/50 fade-in-up">
                <div class="lg:grid lg:grid-cols-2">
                    <!-- Left Side - Form -->
                    <div class="p-8 lg:p-12">
                        <!-- Logo Section -->
                        <div class="text-center mb-8">
                            <div class="inline-block">
                                <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Koperasi Majakara" class="h-20 w-auto mx-auto mb-4 pulse-glow">
                            </div>
                            <h1 class="text-3xl font-bold text-[#674c1d] font-display mb-2">Selamat Datang</h1>
                            <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl fade-in-up">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- General Error Message -->
                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl fade-in-up">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login.submit') }}" id="loginForm" class="space-y-6">
                            @csrf
                            
                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full pl-11 pr-4 py-3 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] transition-all outline-none bg-white/50"
                                        placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg fade-in-up">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                        </div>
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input type="password" name="password" id="password" required
                                        class="w-full pl-11 pr-12 py-3 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] transition-all outline-none bg-white/50"
                                        placeholder="Masukkan password">
                                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg fade-in-up">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
                                        </div>
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" name="remember" id="remember" 
                                        class="w-4 h-4 text-[#674c1d] border-gray-300 rounded focus:ring-[#674c1d]">
                                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                                </div>
                                <a href="#" class="text-sm text-[#674c1d] hover:text-[#8b6f2f] font-medium transition-colors">Lupa password?</a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="loginButton"
                                class="w-full px-8 py-3.5 bg-linear-to-r from-[#674c1d] via-[#8b6f2f] to-[#674c1d] bg-size-200 bg-pos-0 hover:bg-pos-100 text-white rounded-xl transition-all duration-500 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <span id="loginButtonText" class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Masuk
                                </span>
                                <span id="loginButtonLoading" class="hidden flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-8 text-center">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-4 bg-white text-gray-500">Belum punya akun?</span>
                                </div>
                            </div>
                            <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-2 text-[#674c1d] font-semibold hover:text-[#8b6f2f] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>

                    <!-- Right Side - Illustration -->
                    <div class="hidden lg:block gradient-animate p-12 flex items-center justify-center relative overflow-hidden">
                        <!-- Decorative circles -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24 blur-3xl"></div>
                        
                        <div class="relative z-10 text-center text-white">
                            <div class="mb-8 float-animation">
                                <div class="w-72 h-72 mx-auto bg-white/10 rounded-3xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-2xl">
                                    <img src="{{ asset('images/logo/logo_putih.png') }}" alt="Logo Koperasi Majakara" class="w-56 h-auto">
                                </div>
                            </div>
                            <h3 class="text-3xl font-bold mb-3 font-display">Koperasi Majakara</h3>
                            <p class="text-white/90 text-lg mb-8">Solusi Keuangan Terpercaya</p>
                            
                            <!-- Features -->
                            <div class="space-y-4 text-left max-w-sm mx-auto">
                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">Aman & Terpercaya</p>
                                        <p class="text-xs text-white/70">Keamanan data terjamin</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">Proses Cepat</p>
                                        <p class="text-xs text-white/70">Layanan responsif 24/7</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">Bunga Kompetitif</p>
                                        <p class="text-xs text-white/70">Suku bunga terbaik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-8 text-gray-600 text-sm">
                <p>© 2026 Koperasi Majakara. Hak cipta dilindungi undang-undang.</p>
            </div>
        </div>
    </div>

    <!-- PIN Verification Modal -->
    <div id="pinModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 fade-in-up">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center mb-4 pulse-glow">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi PIN</h3>
                <p class="text-gray-600">Masukkan PIN 6 digit Anda untuk melanjutkan</p>
            </div>

            <form id="pinForm" method="POST" action="{{ route('login.verify-pin') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="pin" class="block text-sm font-semibold text-gray-700 mb-2">PIN</label>
                        <input type="password" name="pin" id="pin" maxlength="6" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono bg-gray-50"
                            placeholder="••••••" autocomplete="off" inputmode="numeric">
                        <div id="pinError" class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-red-700 font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closePinModal()" 
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-semibold">
                            Batal
                        </button>
                        <button type="submit" id="verifyPinButton"
                            class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:shadow-lg transition-all font-semibold">
                            <span id="verifyPinButtonText">Verifikasi</span>
                            <span id="verifyPinButtonLoading" class="hidden flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memverifikasi...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Handle login form submission with AJAX
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginButtonLoading = document.getElementById('loginButtonLoading');
            
            // Disable button and show loading
            loginButton.disabled = true;
            loginButtonText.classList.add('hidden');
            loginButtonLoading.classList.remove('hidden');
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    return response.json();
                } else {
                    // If not JSON, might be redirect (HTML response)
                    window.location.href = response.url || '/';
                    return null;
                }
            })
            .then(data => {
                if (!data) return;
                
                if (data.success) {
                    if (data.requires_pin) {
                        showPinModal();
                    } else {
                        window.location.href = data.redirect_url || '/';
                    }
                } else {
                    alert(data.message || 'Login gagal');
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginButtonLoading.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                form.submit();
            });
        });

        // Handle PIN form submission
        document.getElementById('pinForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const verifyButton = document.getElementById('verifyPinButton');
            const verifyButtonText = document.getElementById('verifyPinButtonText');
            const verifyButtonLoading = document.getElementById('verifyPinButtonLoading');
            const pinError = document.getElementById('pinError');
            
            // Clear previous error
            pinError.classList.add('hidden');
            pinError.querySelector('p').textContent = '';
            
            // Disable button and show loading
            verifyButton.disabled = true;
            verifyButtonText.classList.add('hidden');
            verifyButtonLoading.classList.remove('hidden');
            
            // Submit PIN verification via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url || '/';
                } else {
                    pinError.querySelector('p').textContent = data.message || 'PIN salah';
                    pinError.classList.remove('hidden');
                    verifyButton.disabled = false;
                    verifyButtonText.classList.remove('hidden');
                    verifyButtonLoading.classList.add('hidden');
                    document.getElementById('pin').value = '';
                    document.getElementById('pin').focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                pinError.querySelector('p').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                pinError.classList.remove('hidden');
                verifyButton.disabled = false;
                verifyButtonText.classList.remove('hidden');
                verifyButtonLoading.classList.add('hidden');
            });
        });

        // Show PIN modal
        function showPinModal() {
            const modal = document.getElementById('pinModal');
            modal.classList.remove('hidden');
            document.getElementById('pin').focus();
            
            // Reset login button
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginButtonLoading = document.getElementById('loginButtonLoading');
            loginButton.disabled = false;
            loginButtonText.classList.remove('hidden');
            loginButtonLoading.classList.add('hidden');
        }

        // Close PIN modal
        function closePinModal() {
            const modal = document.getElementById('pinModal');
            modal.classList.add('hidden');
            document.getElementById('pin').value = '';
            document.getElementById('pinError').classList.add('hidden');
            
            // Logout user
            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(() => {});
        }

        // PIN input: only numbers
        document.getElementById('pin').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            if (e.target.value.length === 6) {
                document.getElementById('pinForm').dispatchEvent(new Event('submit'));
            }
        });

        // Show PIN modal if session requires it
        const requiresPin = {{ session('requires_pin') ? 'true' : 'false' }};
        if (requiresPin) {
            document.addEventListener('DOMContentLoaded', function() {
                showPinModal();
            });
        }
    </script>

    <style>
        .bg-size-200 {
            background-size: 200% 100%;
        }
        .bg-pos-0 {
            background-position: 0% 0%;
        }
        .bg-pos-100 {
            background-position: 100% 0%;
        }
    </style>
</body>
</html>
