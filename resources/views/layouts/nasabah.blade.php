<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #674c1d;
            --primary-light: #8b6f2f;
            --primary-dark: #4a3514;
            --accent: #d4af37;
            --bg-light: #faf9f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            padding-bottom: 80px; /* Space for bottom navbar */
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #674c1d 0%, #8b6f2f 100%);
        }
        
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#faf9f6]">
    <!-- Header -->
    <x-nasabah.header />
    
    <!-- Main Content -->
    <main class="w-full overflow-x-hidden">
        @yield('content')
    </main>
    
    <!-- Bottom Navbar -->
    <x-nasabah.bottom-navbar />
    
    <!-- Universal Photo Preview Modal -->
    <x-photo-preview-modal />
    
    @stack('scripts')
    
    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const options = { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            };
            const dateString = now.toLocaleDateString('id-ID', options);
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            }).replace(/:/g, '.');
            
            const dateElement = document.getElementById('currentDate');
            const timeElement = document.querySelector('.time-display');
            
            if (dateElement) {
                dateElement.textContent = dateString;
            }
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        // Update immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
