@extends('layouts.auth')

@section('title', 'Lupa Password - Koperasi Majakara')

@section('content')
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
                        <h1 class="text-3xl font-bold text-majakara-brown font-display mb-2">Lupa Password</h1>
                        <p class="text-gray-600">Masukkan nomor WhatsApp terdaftar Anda untuk mengirim kode OTP verifikasi</p>
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

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" class="space-y-6">
                        @csrf
                        
                        <!-- WhatsApp Field -->
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor WhatsApp Terdaftar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" required
                                    class="w-full pl-11 pr-4 py-3 border @error('whatsapp_number') border-red-300 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-majakara-dark-gold focus:border-majakara-dark-gold transition-all outline-none bg-white/50"
                                    placeholder="Contoh: 081234567890" autofocus>
                            </div>
                            @error('whatsapp_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitButton"
                            class="w-full px-8 py-3.5 bg-linear-to-r from-majakara-brown via-majakara-dark-gold to-majakara-brown bg-size-200 bg-pos-0 hover:bg-pos-100 text-white rounded-xl transition-all duration-500 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span id="submitButtonText" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Kode OTP
                            </span>
                            <span id="submitButtonLoading" class="hidden flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-8 text-center">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Kembali</span>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-majakara-brown font-semibold hover:text-majakara-dark-gold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Login
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
    document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitButton');
        const textSpan = document.getElementById('submitButtonText');
        const loadingSpan = document.getElementById('submitButtonLoading');
        
        submitBtn.disabled = true;
        textSpan.classList.add('hidden');
        loadingSpan.classList.remove('hidden');
    });
</script>
@endpush
