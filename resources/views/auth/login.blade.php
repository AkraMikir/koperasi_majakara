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

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                                {{ session('error') }}
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

                                <button type="submit" id="loginButton"
                                    class="w-full px-8 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium shadow-md">
                                    <span id="loginButtonText">Masuk</span>
                                    <span id="loginButtonLoading" class="hidden">Memproses...</span>
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

    <!-- PIN Verification Modal -->
    <div id="pinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto bg-[#674c1d]/10 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                        <input type="password" name="pin" id="pin" maxlength="6" required autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] transition-all outline-none text-center text-2xl tracking-widest font-mono"
                            placeholder="000000" autocomplete="off" inputmode="numeric">
                        <div id="pinError" class="mt-2 text-sm text-red-600 hidden"></div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closePinModal()" 
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                            Batal
                        </button>
                        <button type="submit" id="verifyPinButton"
                            class="flex-1 px-4 py-3 bg-[#674c1d] text-white rounded-lg hover:bg-[#4a3514] transition-all font-medium">
                            <span id="verifyPinButtonText">Verifikasi</span>
                            <span id="verifyPinButtonLoading" class="hidden">Memverifikasi...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
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
                    // This means login succeeded but no PIN required
                    window.location.href = response.url || '/';
                    return null;
                }
            })
            .then(data => {
                if (!data) return; // Already redirected
                
                if (data.success) {
                    if (data.requires_pin) {
                        // Show PIN modal
                        showPinModal();
                    } else {
                        // No PIN required, redirect
                        window.location.href = data.redirect_url || '/';
                    }
                } else {
                    // Show error
                    alert(data.message || 'Login gagal');
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginButtonLoading.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback: submit form normally (for non-AJAX browsers)
                // But first check if it's a validation error
                if (error.message && error.message.includes('JSON')) {
                    // Response was HTML (validation error), submit normally
                    form.submit();
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginButtonLoading.classList.add('hidden');
                }
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
            pinError.textContent = '';
            
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
                    // PIN verified, redirect
                    window.location.href = data.redirect_url || '/';
                } else {
                    // Show error
                    pinError.textContent = data.message || 'PIN salah';
                    pinError.classList.remove('hidden');
                    verifyButton.disabled = false;
                    verifyButtonText.classList.remove('hidden');
                    verifyButtonLoading.classList.add('hidden');
                    
                    // Clear PIN input
                    document.getElementById('pin').value = '';
                    document.getElementById('pin').focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                pinError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
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
            
            // Clear PIN input
            document.getElementById('pin').value = '';
            document.getElementById('pinError').classList.add('hidden');
            
            // Logout user if already logged in
            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(() => {});
        }

        // PIN input: only numbers, auto-focus next on 6 digits
        document.getElementById('pin').addEventListener('input', function(e) {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Auto submit if 6 digits entered
            if (e.target.value.length === 6) {
                document.getElementById('pinForm').dispatchEvent(new Event('submit'));
            }
        });

        // Show PIN modal if session requires it
        @if(session('requires_pin'))
        document.addEventListener('DOMContentLoaded', function() {
            showPinModal();
        });
        @endif
    </script>
</body>
</html>

