@props(['user' => null, 'dummyNasabah' => null])

@php
    // Frontend only - use dummy data
    if (!$user) {
        $user = (object)[
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'nomor_hp' => '081234567890',
            'foto' => null
        ];
    }
    
    // Use dummy data for frontend preview
    $pekerjaan = $dummyNasabah->pekerjaanTemp ?? null;
    $dataRek = $dummyNasabah->dataRekTemp ?? null;
@endphp

<section class="bg-white rounded-2xl shadow-lg p-4 mb-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Profile Card (Left) -->
        <div class="lg:col-span-1 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl p-4 text-white">
            <div class="flex flex-col items-center text-center">
                <!-- Foto Profil -->
                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 mb-3 overflow-hidden">
                    @if($user->foto && $user->foto !== 'default-profile.jpg')
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-3xl font-bold">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Nama -->
                <h2 class="text-lg font-bold mb-1">{{ $user->nama }}</h2>
                <p class="text-white/80 text-xs">Nasabah Aktif</p>
            </div>
        </div>
        
        <!-- Data Akun Cards (Right) -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Card 1: Email -->
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-0.5">Email</p>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Nomor HP -->
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-0.5">Nomor HP</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->nomor_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Pekerjaan -->
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-0.5">Pekerjaan</p>
                        <p class="text-sm font-semibold text-gray-900">{{ isset($pekerjaan) && isset($pekerjaan->pekerjaan) ? $pekerjaan->pekerjaan : '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Card 4: Rekening -->
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-0.5">No. Rekening</p>
                        <p class="text-sm font-semibold text-gray-900">{{ isset($dataRek) && isset($dataRek->no_rekening) ? $dataRek->no_rekening : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
