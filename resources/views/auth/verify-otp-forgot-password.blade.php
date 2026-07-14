@extends('layouts.auth')

@section('title', 'Verifikasi OTP & Reset Password - Koperasi Majakara')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl w-full">
        <!-- Main Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden max-w-5xl mx-auto border border-gray-200/50 fade-in-up">
            <div class="lg:grid lg:grid-cols-2">
                <!-- Left Side - Form -->
                <div class="p-8 lg:p-12">
                    <!-- Logo Section -->
                    <div class="text-center mb-6">
                        <div class="inline-block">
                            <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo Koperasi Majakara" class="h-20 w-auto mx-auto mb-4 pulse-glow">
                        </div>
                        <h1 class="text-3xl font-bold text-majakara-brown font-display mb-2">Verifikasi OTP</h1>
                        <p class="text-gray-600">Masukkan kode OTP yang dikirim ke <strong>email</strong> Anda untuk mengatur ulang password</p>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- OTP & Password Reset Form -->
                    <form method="POST" action="{{ route('password.update') }}" id="resetForm" class="space-y-5">
                        @csrf
                        
                        <!-- OTP Code Field -->
                        <div>
                            <label for="otp_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode OTP Email (6 Digit) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="otp_code" id="otp_code" required maxlength="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-majakara-dark-gold focus:border-majakara-dark-gold text-center text-2xl tracking-widest font-mono bg-white/50 outline-none transition-all"
                                placeholder="••••••" autocomplete="off" inputmode="numeric" autofocus>
                            @error('otp_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password Field -->
                        <div>
                            <label for="password_baru" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_baru" id="password_baru" required
                                    class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-majakara-dark-gold focus:border-majakara-dark-gold outline-none transition-all bg-white/50"
                                    placeholder="Password minimal 8 karakter">
                                <button type="button" onclick="togglePasswordVisibility('password_baru', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg id="eyeIcon1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password_baru')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation Field -->
                        <div>
                            <label for="password_baru_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" required
                                    class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-majakara-dark-gold focus:border-majakara-dark-gold outline-none transition-all bg-white/50"
                                    placeholder="Masukkan kembali password baru">
                                <button type="button" onclick="togglePasswordVisibility('password_baru_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg id="eyeIcon2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password_baru_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn"
                            class="w-full px-8 py-3.5 bg-linear-to-r from-majakara-brown via-majakara-dark-gold to-majakara-brown bg-size-200 bg-pos-0 hover:bg-pos-100 text-white rounded-xl transition-all duration-500 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span id="submitBtnText" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-6.837 3.328 11.954 11.954 0 00.73 3.96c.747 2.05 2.036 3.823 3.595 5.236a11.96 11.96 0 005.023 2.532 11.96 11.96 0 005.023-2.532c1.56-1.413 2.848-3.186 3.595-5.236a11.954 11.954 0 00.73-3.96 11.951 11.951 0 01-6.837-3.328z"></path>
                                </svg>
                                Perbarui Password
                            </span>
                            <span id="submitBtnLoading" class="hidden flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>

                    <!-- Resend section and Back -->
                    <div class="mt-6 flex flex-col items-center gap-4 text-center">
                        <div class="text-sm text-gray-600">
                            Tidak menerima OTP? 
                            <button type="button" id="resendBtn" class="text-majakara-brown hover:text-majakara-dark-gold font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Kirim Ulang OTP
                            </button>
                            <span id="timerContainer" class="hidden text-xs text-gray-500 font-medium">
                                (Tunggu <span id="timerText" class="font-bold"></span> detik)
                            </span>
                        </div>
                        
                        <div class="w-full border-t border-gray-300 my-2"></div>
                        
                        <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-gray-600 font-semibold hover:text-majakara-brown transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Input Email
                        </a>
                    </div>
                </div>

                <!-- Right Side - Illustration -->
                <div class="hidden lg:block gradient-animate p-12 flex items-center justify-center relative overflow-hidden">
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
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(fieldId, eyeIconId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(eyeIconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
            `;
        } else {
            input.type = 'password';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    }

    // Submit handler
    document.getElementById('resetForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const textSpan = document.getElementById('submitBtnText');
        const loadingSpan = document.getElementById('submitBtnLoading');
        
        submitBtn.disabled = true;
        textSpan.classList.add('hidden');
        loadingSpan.classList.remove('hidden');
    });

    // Cooldown logic
    async function updateCooldown() {
        try {
            const response = await fetch('{{ route('password.otp-cooldown') }}');
            const data = await response.json();
            
            const resendBtn = document.getElementById('resendBtn');
            const timerContainer = document.getElementById('timerContainer');
            const timerText = document.getElementById('timerText');
            
            if (data.remaining > 0) {
                resendBtn.disabled = true;
                resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
                timerContainer.classList.remove('hidden');
                
                let seconds = data.remaining;
                timerText.innerText = seconds;
                
                clearInterval(window.cooldownInterval);
                window.cooldownInterval = setInterval(() => {
                    seconds--;
                    timerText.innerText = seconds;
                    if (seconds <= 0) {
                        clearInterval(window.cooldownInterval);
                        resendBtn.disabled = false;
                        resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        timerContainer.classList.add('hidden');
                    }
                }, 1000);
            } else {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                timerContainer.classList.add('hidden');
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Resend handler
    document.getElementById('resendBtn').addEventListener('click', async function(e) {
        e.preventDefault();
        const resendBtn = this;
        resendBtn.disabled = true;
        
        try {
            const response = await fetch('{{ route('password.resend-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    confirmButtonColor: '#674c1d'
                });
                updateCooldown();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#674c1d'
                });
                resendBtn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            resendBtn.disabled = false;
        }
    });

    // Check cooldown on load
    document.addEventListener('DOMContentLoaded', function() {
        updateCooldown();
    });
</script>
@endpush
