@extends('layouts.admin')

@section('title', 'Tambah Admin Operasional')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.master-data.admin-operasional.index') }}" class="text-gray-400 hover:text-[#674c1d] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Tambah Admin Operasional</h1>
            <p class="text-gray-600 mt-0.5">Buat akun baru untuk Admin Operasional koperasi</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg">Informasi Akun</h2>
                        <p class="text-white/70 text-sm">Isi data admin operasional yang akan ditambahkan</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('admin.master-data.admin-operasional.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                        placeholder="Masukkan nama lengkap..."
                        class="w-full px-4 py-2.5 border-2 rounded-xl outline-none transition-all text-sm
                            {{ $errors->has('nama') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}">
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="contoh@koperasi.com"
                        class="w-full px-4 py-2.5 border-2 rounded-xl outline-none transition-all text-sm
                            {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label for="nomor_hp" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nomor HP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 border-2 rounded-xl outline-none transition-all text-sm font-mono
                            {{ $errors->has('nomor_hp') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}">
                    @error('nomor_hp')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100 pt-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keamanan Akun</p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-2.5 pr-12 border-2 rounded-xl outline-none transition-all text-sm
                                {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}">
                        <button type="button" onclick="togglePassword('password', 'eye-password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password"
                            class="w-full px-4 py-2.5 pr-12 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all text-sm">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- PIN Keamanan -->
                <div>
                    <label for="pin" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        PIN Keamanan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="pin" name="pin" maxlength="6" inputmode="numeric" pattern="\d{6}"
                            placeholder="6 digit angka"
                            class="w-full px-4 py-2.5 pr-12 border-2 rounded-xl outline-none transition-all text-sm tracking-widest text-center
                                {{ $errors->has('pin') ? 'border-red-400 focus:border-red-500 bg-red-50' : 'border-gray-200 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20' }}">
                        <button type="button" onclick="togglePassword('pin', 'eye-pin')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-pin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('pin')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Konfirmasi PIN Keamanan -->
                <div>
                    <label for="pin_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Konfirmasi PIN Keamanan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="pin_confirmation" name="pin_confirmation" maxlength="6" inputmode="numeric" pattern="\d{6}"
                            placeholder="Ulangi 6 digit angka"
                            class="w-full px-4 py-2.5 pr-12 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none transition-all text-sm tracking-widest text-center">
                        <button type="button" onclick="togglePassword('pin_confirmation', 'eye-pin-confirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-pin-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-[#674c1d]/5 border border-[#674c1d]/20 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-[#674c1d] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-[#674c1d]">
                            <p class="font-semibold mb-1">Informasi Akun Admin Operasional</p>
                            <ul class="list-disc list-inside space-y-0.5 text-[#674c1d]/80 text-xs">
                                <li>Admin Operasional dapat melakukan approval tabungan & pinjaman</li>
                                <li>Tidak dapat membuat/menghapus data transaksi manual</li>
                                <li>Tidak dapat mengubah Master Data</li>
                                <li>Dapat mereset PIN dan password nasabah</li>
                                <li>Tidak dapat menghapus/membuat data transaksi manual nasabah</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] hover:shadow-lg transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
    }
}
</script>
@endpush
@endsection
