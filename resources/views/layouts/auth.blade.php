<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Koperasi Majakara')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Animate.css for SweetAlert transition -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --color-majakara-brown: #674c1d;
            --color-majakara-dark-gold: #8b6f2f;
            --color-majakara-gold: #d4af37;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Gradient animation */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .gradient-animate {
            background: linear-gradient(135deg, var(--color-majakara-brown) 0%, var(--color-majakara-dark-gold) 25%, var(--color-majakara-gold) 50%, var(--color-majakara-dark-gold) 75%, var(--color-majakara-brown) 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }

        /* Float animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(212, 175, 55, 0.3); }
            50% { box-shadow: 0 0 40px rgba(212, 175, 55, 0.6); }
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* Fade in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Background pattern */
        .pattern-bg {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(103, 76, 29, 0.1) 0%, transparent 50%);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-linear-to-br from-gray-50 via-amber-50/30 to-gray-50 min-h-screen pattern-bg">
    
    @yield('content')
    
    <x-alert-swal />
    
    @stack('scripts')
</body>
</html>
