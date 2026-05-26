@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#d4af37]/20 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-display">Profil Admin</h1>
            <p class="text-gray-500 mt-2">Kelola informasi pribadi dan foto profil Anda.</p>
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

    <!-- Profile Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
         x-data="profileForm()">
        
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Foto Profil Column -->
                <div class="w-full md:w-1/3 flex flex-col items-center">
                    <div class="relative group">
                        <!-- Preview Container -->
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 relative">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" alt="Preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!photoPreview">
                                @if(auth()->user()->foto && Storage::disk('public')->exists(auth()->user()->foto) && auth()->user()->foto !== 'default-avatar.jpg')
                                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white text-4xl font-bold">
                                        {{ substr(auth()->user()->nama ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                            </template>
                            
                            <!-- Overlay Upload -->
                            <label for="foto" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-white">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-xs font-medium">Ubah Foto</span>
                            </label>
                        </div>
                    </div>
                    
                    <input type="file" name="foto" id="foto" class="hidden" accept="image/jpeg,image/png,image/jpg" @change="updatePreview">
                    
                    <div class="mt-4 text-center">
                        <button type="button" @click="$refs.fotoInput.click()" onclick="document.getElementById('foto').click()" class="text-[#674c1d] text-sm font-medium hover:text-[#8b6f2f] transition-colors bg-[#674c1d]/10 px-4 py-2 rounded-lg">
                            Pilih Foto Baru
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Maksimal 2MB. Format: JPG, PNG.</p>
                    </div>
                </div>
                
                <!-- Data Profil Column -->
                <div class="w-full md:w-2/3 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', auth()->user()->nama) }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                        </div>
                        
                        <!-- No HP -->
                        <div>
                            <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp', auth()->user()->nomor_hp) }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
                        </div>
                        
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" value="{{ auth()->user()->username }}" disabled
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">Username tidak dapat diubah.</p>
                        </div>
                        
                        <!-- Peran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peran Akses</label>
                            @php
                                $permissionService = app(\App\Services\AdminPermissionService::class);
                                $roleDisplay = $permissionService->getRoleDisplayName(auth()->user());
                            @endphp
                            <input type="text" value="{{ $roleDisplay }}" disabled
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed font-semibold">
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-[#674c1d]/20 hover:shadow-[#674c1d]/40 transition-all transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profileForm', () => ({
            photoPreview: null,
            
            updatePreview(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }));
    });
</script>
@endpush
@endsection
