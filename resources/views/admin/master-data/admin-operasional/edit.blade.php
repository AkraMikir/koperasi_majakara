@extends('layouts.admin')

@section('title', 'Edit Admin Operasional')

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
            <h1 class="text-3xl font-bold text-gray-900 font-display">Edit Admin Operasional</h1>
            <p class="text-gray-600 mt-0.5">Perbarui informasi akun <span class="font-semibold text-[#674c1d]">{{ $adminOp->user->nama }}</span></p>
        </div>
    </div>

    <div class="max-w-2xl space-y-5">
        <!-- Profile Preview Card -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                    {{ strtoupper(substr($adminOp->user->nama ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $adminOp->user->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $adminOp->user->email }}</p>
                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $adminOp->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $adminOp->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-xs text-gray-400">ID Admin</p>
                    <p class="text-sm font-mono font-bold text-gray-600">#{{ $adminOp->id }}</p>
                    <p class="text-xs text-gray-400 mt-1">Bergabung {{ $adminOp->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg">Edit Informasi Akun</h2>
                        <p class="text-white/70 text-sm">Perbarui data akun Admin Operasional</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('admin.master-data.admin-operasional.update', $adminOp->id) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $adminOp->user->nama) }}"
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
                    <input type="email" id="email" name="email" value="{{ old('email', $adminOp->user->email) }}"
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
                    <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $adminOp->user->nomor_hp) }}"
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
                    <div class="flex items-center gap-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ganti Password</p>
                        <span class="text-xs text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
                    </div>
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password Baru
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
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password baru"
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

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <!-- Delete action — hanya Admin Utama -->
                    @canCrudMasterData
                    <form action="{{ route('admin.master-data.admin-operasional.destroy', $adminOp->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus akun ini? Aksi ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 text-red-600 border border-red-200 bg-red-50 rounded-xl hover:bg-red-100 transition-all text-sm font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Akun
                        </button>
                    </form>
                    @else
                    <div></div>
                    @endcanCrudMasterData

                    <div class="flex gap-3">
                        <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] hover:shadow-lg transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
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
