@extends('layouts.admin')

@section('title', 'Pengaturan Keamanan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#d4af37]/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-display">Keamanan Akun</h1>
            <p class="text-gray-500 mt-2">Ubah password dan PIN keamanan Anda secara berkala.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-start">
        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-start">
        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-red-800 text-sm font-medium">Terdapat kesalahan pada input Anda:</p>
                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Ubah Password Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
            <div class="p-6 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Ubah Password</h2>
                    <p class="text-xs text-gray-500">Gunakan kombinasi yang kuat</p>
                </div>
            </div>
            
            <form action="{{ route('admin.setting.update-password') }}" method="POST" class="p-6 flex-1 flex flex-col justify-between" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                @csrf
                @method('PUT')
                
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" name="current_password" id="current_password" required
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                            <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" name="password" id="password" required minlength="8"
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                            <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required minlength="8"
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    Simpan Password
                </button>
            </form>
        </div>

        <!-- Ubah PIN Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
            <div class="p-6 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                <div class="p-2 bg-[#d4af37]/10 text-[#d4af37] rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Ubah PIN Keamanan</h2>
                    <p class="text-xs text-gray-500">Gunakan 6 digit angka</p>
                </div>
            </div>
            
            <form action="{{ route('admin.setting.update-pin') }}" method="POST" class="p-6 flex-1 flex flex-col justify-between" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                @csrf
                @method('PUT')
                
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="current_pin" class="block text-sm font-medium text-gray-700 mb-1">PIN Saat Ini</label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" name="current_pin" id="current_pin" required maxlength="6" inputmode="numeric" pattern="\d{6}"
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow tracking-widest text-center" placeholder="••••••">
                            <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">PIN Baru</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" name="pin" id="pin" required maxlength="6" inputmode="numeric" pattern="\d{6}"
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow tracking-widest text-center" placeholder="••••••">
                            <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="pin_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi PIN Baru</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="pin_confirmation" id="pin_confirmation" required maxlength="6" inputmode="numeric" pattern="\d{6}"
                                class="w-full pr-10 rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow tracking-widest text-center" placeholder="••••••">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#674c1d]">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.088-5.528M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-[#674c1d] text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-[#8b6f2f] transition-colors">
                    Simpan PIN Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
