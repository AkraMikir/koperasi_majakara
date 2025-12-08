<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full">
            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-6xl mx-auto">
                <div class="lg:grid lg:grid-cols-2">
                    <!-- Left Side - Form -->
                    <div class="p-8 lg:p-12">
                        <div class="mb-8">
                            <a href="{{ route('register') }}" class="inline-flex items-center text-[#674c1d] hover:underline mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Kembali ke Registrasi
                            </a>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                            <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                            @csrf
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="nama@email.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none"
                                        placeholder="Masukkan password">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="remember" id="remember" 
                                            class="w-4 h-4 text-[#674c1d] border-gray-300 rounded focus:ring-[#674c1d]">
                                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                                    </div>
                                    <a href="#" class="text-sm text-[#674c1d] hover:underline">Lupa password?</a>
                                </div>

                                <button type="submit" 
                                    class="w-full px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                    Masuk
                                </button>
                            </div>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-6 text-center">
                            <p class="text-gray-600">
                                Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-[#674c1d] font-medium hover:underline">Daftar di sini</a>
                            </p>
                        </div>
                    </div>

                    <!-- Right Side - Image Placeholder -->
                    <div class="hidden lg:block bg-gradient-to-br from-[#674c1d] to-[#4a3514] p-12 flex items-center justify-center">
                        <div class="text-center text-white">
                            <div class="mb-6">
                                <div class="w-64 h-64 mx-auto bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                                    <svg class="w-32 h-32 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Koperasi Majakara</h3>
                            <p class="text-white/80">Solusi Keuangan Terpercaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

