@extends('layouts.nasabah')

@section('title', 'Pengaturan Keamanan')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white font-display mb-1">Keamanan & Privasi</h1>
                    <p class="text-white/90 text-sm">Kelola password dan PIN Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-md">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('success_pin'))
    <div class="mx-4 mb-6">
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-md">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-green-700 font-semibold">{{ session('success_pin') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-4 mb-6">
        <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-md">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-2 border border-gray-100">
            <div class="flex gap-2">
                <button onclick="switchTab('password')" id="tab-password" class="flex-1 py-3 px-4 rounded-xl font-semibold transition-all bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Password
                </button>
                <button onclick="switchTab('pin')" id="tab-pin" class="flex-1 py-3 px-4 rounded-xl font-semibold transition-all text-gray-600 hover:bg-gray-50">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    PIN
                </button>
            </div>
        </div>
    </div>

    <!-- Password Tab Content -->
    <div id="content-password" class="mx-4 space-y-6">
        <!-- Method 1: Change Password (Ingat Password Lama) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 font-display">Ubah Password</h2>
                    <p class="text-sm text-gray-600">Jika Anda masih ingat password lama</p>
                </div>
            </div>

            <form method="POST" action="{{ route('nasabah.setting.change-password') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama *</label>
                    <div class="relative">
                        <input type="password" name="password_lama" id="password_lama" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none pr-12">
                        <button type="button" onclick="togglePassword('password_lama')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
                    <div class="relative">
                        <input type="password" name="password_baru" id="password_baru" required minlength="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none pr-12">
                        <button type="button" onclick="togglePassword('password_baru')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                    <div class="relative">
                        <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" required minlength="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none pr-12">
                        <button type="button" onclick="togglePassword('password_baru_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]">
                    Ubah Password
                </button>
            </form>
        </div>

        <!-- Method 2: Reset Password dengan OTP (Lupa Password) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 font-display">Reset Password (Lupa Password)</h2>
                    <p class="text-sm text-gray-600">Gunakan OTP WhatsApp untuk reset password</p>
                </div>
            </div>

            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Penting:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Kode OTP akan dikirim ke: <strong>{{ substr($user->nomor_hp, 0, 4) }}****{{ substr($user->nomor_hp, -3) }}</strong></li>
                            <li>Kode berlaku selama 1 menit</li>
                            <li>Jangan berikan kode OTP kepada siapapun</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="step-send-otp-pwd" class="space-y-4">
                <button type="button" onclick="sendOtpPasswordReset()" id="btn-send-otp-pwd" class="w-full py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Kirim Kode OTP WhatsApp
                </button>
                <p id="countdown-pwd" class="text-center text-sm text-gray-500 hidden"></p>
            </div>

            <form id="form-reset-password" class="hidden space-y-4 mt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode OTP *</label>
                    <input type="text" id="otp_code_pwd" maxlength="6" placeholder="••••••" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <p class="text-xs text-gray-500 mt-1 text-center">Masukkan 6 digit kode OTP</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
                    <div class="relative">
                        <input type="password" id="new_password_otp" required minlength="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none pr-12">
                        <button type="button" onclick="togglePassword('new_password_otp')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                    <div class="relative">
                        <input type="password" id="new_password_otp_confirmation" required minlength="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none pr-12">
                        <button type="button" onclick="togglePassword('new_password_otp_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="cancelResetPassword()" class="flex-1 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                    <button type="button" onclick="submitResetPassword()" class="flex-1 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PIN Tab Content -->
    <div id="content-pin" class="mx-4 space-y-6 hidden">
        <!-- Change PIN (Ingat PIN Lama) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 font-display">Ubah PIN Transaksi</h2>
                    <p class="text-sm text-gray-600">Jika Anda masih ingat PIN lama</p>
                </div>
            </div>

            <form method="POST" action="{{ route('nasabah.setting.change-pin') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">PIN Lama *</label>
                    <input type="password" name="pin_lama" id="pin_lama" maxlength="6" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="••••••">
                    <p class="text-xs text-gray-500 mt-1 text-center">Masukkan PIN 6 digit</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">PIN Baru *</label>
                    <input type="password" name="pin_baru" id="pin_baru" maxlength="6" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="••••••">
                    <p class="text-xs text-gray-500 mt-1 text-center">6 digit angka</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi PIN Baru *</label>
                    <input type="password" name="pin_baru_confirmation" id="pin_baru_confirmation" maxlength="6" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="••••••">
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]">
                    Ubah PIN
                </button>
            </form>
        </div>

        <!-- Info Lupa PIN -->
        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl shadow-lg p-6 border-2 border-orange-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-orange-900 mb-2">Lupa PIN?</h3>
                    <p class="text-sm text-orange-800 mb-4">Jika Anda lupa PIN, silakan hubungi admin koperasi untuk mereset PIN Anda.</p>
                    
                    <a href="https://wa.me/628139552626?text=Halo%20Admin%20Koperasi%2C%0A%0ASaya%20lupa%20PIN%20transaksi%20saya.%0A%0ANama%3A%20{{ urlencode($user->nama) }}%0AEmail%3A%20{{ urlencode($user->email) }}%0ANo%20HP%3A%20{{ urlencode($user->nomor_hp) }}%0A%0AMohon%20bantuannya%20untuk%20reset%20PIN.%0ATerima%20kasih." 
                        target="_blank"
                        class="w-full sm:w-auto inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Hubungi Admin via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab Switching
    function switchTab(tab) {
        // Update tab buttons
        const tabs = ['password', 'pin'];
        tabs.forEach(t => {
            const btn = document.getElementById('tab-' + t);
            const content = document.getElementById('content-' + t);
            
            if (t === tab) {
                btn.className = 'flex-1 py-3 px-4 rounded-xl font-semibold transition-all bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white shadow-md';
                content.classList.remove('hidden');
            } else {
                btn.className = 'flex-1 py-3 px-4 rounded-xl font-semibold transition-all text-gray-600 hover:bg-gray-50';
                content.classList.add('hidden');
            }
        });
    }

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    // ======================================
    // PASSWORD RESET WITH OTP
    // ======================================
    
    let countdownInterval;

    async function sendOtpPasswordReset() {
        const btn = document.getElementById('btn-send-otp-pwd');
        const countdown = document.getElementById('countdown-pwd');
        
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...';

        try {
            const response = await fetch('{{ route("nasabah.setting.send-otp-password-reset") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show form
                document.getElementById('step-send-otp-pwd').classList.add('hidden');
                document.getElementById('form-reset-password').classList.remove('hidden');
                
                // Start countdown
                startCountdown(60, countdown, btn);
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>Kirim Kode OTP WhatsApp';
            }
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>Kirim Kode OTP WhatsApp';
        }
    }

    async function submitResetPassword() {
        const otpCode = document.getElementById('otp_code_pwd').value;
        const newPassword = document.getElementById('new_password_otp').value;
        const confirmPassword = document.getElementById('new_password_otp_confirmation').value;

        if (otpCode.length !== 6) {
            showAlert('error', 'Kode OTP harus 6 digit');
            return;
        }

        if (newPassword.length < 8) {
            showAlert('error', 'Password baru minimal 8 karakter');
            return;
        }

        if (newPassword !== confirmPassword) {
            showAlert('error', 'Konfirmasi password tidak cocok');
            return;
        }

        try {
            const response = await fetch('{{ route("nasabah.setting.verify-otp-reset-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    otp_code: otpCode,
                    password_baru: newPassword,
                    password_baru_confirmation: confirmPassword
                })
            });

            const data = await response.json();

            if (data.success) {
                showAlert('success', data.message);
                // Reset form
                setTimeout(() => {
                    document.getElementById('form-reset-password').classList.add('hidden');
                    document.getElementById('step-send-otp-pwd').classList.remove('hidden');
                    document.getElementById('form-reset-password').reset();
                    document.getElementById('otp_code_pwd').value = '';
                }, 2000);
            } else {
                showAlert('error', data.message);
            }
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    function cancelResetPassword() {
        document.getElementById('form-reset-password').classList.add('hidden');
        document.getElementById('step-send-otp-pwd').classList.remove('hidden');
        document.getElementById('form-reset-password').reset();
        document.getElementById('otp_code_pwd').value = '';
    }

    function startCountdown(seconds, element, button) {
        let remaining = seconds;
        element.classList.remove('hidden');
        
        countdownInterval = setInterval(() => {
            element.textContent = `Kirim ulang dalam ${remaining} detik`;
            remaining--;
            
            if (remaining < 0) {
                clearInterval(countdownInterval);
                element.classList.add('hidden');
                button.disabled = false;
                button.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Kirim Ulang OTP';
            }
        }, 1000);
    }

    function showAlert(type, message) {
        const alertContainer = document.createElement('div');
        alertContainer.className = `mx-4 mb-6 ${type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'} border-l-4 rounded-r-xl p-4 shadow-md`;
        alertContainer.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 ${type === 'success' ? 'text-green-600' : 'text-red-600'} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    }
                </svg>
                <div class="flex-1">
                    <p class="${type === 'success' ? 'text-green-700' : 'text-red-700'} font-semibold">${message}</p>
                </div>
            </div>
        `;
        
        // Insert after header
        const header = document.querySelector('.mx-4.mt-4.mb-6');
        header.parentNode.insertBefore(alertContainer, header.nextSibling);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alertContainer.remove();
        }, 5000);
    }
</script>
@endpush
@endsection
