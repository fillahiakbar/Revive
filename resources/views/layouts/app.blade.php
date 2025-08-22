<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scrollbar-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
     <link rel="icon" href="{{ asset('/img/logo.png') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="font-cairo min-h-screen text-white relative overflow-x-hidden">
    
    <!-- Background Layer -->
    <div class="absolute top-0 left-0 w-full h-full -z-10">
        <!-- Gambar background -->
        <div class="absolute inset-0 bg-cover bg-top bg-no-repeat" 
             style="background-image: url('{{ asset('img/background.png') }}'); background-color: #1E1E1E;"></div>
    
        
        <!-- Gradient tambahan -->
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-black/10 to-black/20"></div>
    </div>
    
    <!-- Main Content Wrapper -->
    <div class="relative z-10 min-h-screen">
        <!-- Navigation Banner -->
        <x-banner />

        <!-- Navigation Menu -->
        <div class="min-h-screen">
            @livewire('navigation-menu')

            <!-- Page Content -->
            <main class="relative z-20">
                {{ $slot }}
            </main>
            <x-scroll-to-top-button />

        </div>

        <!-- Footer -->
        <x-footer :socialMedias="$socialMedias" />

        @stack('modals')
        @livewireScripts
        @stack('scripts')
    </div>
    
<script src="//unpkg.com/alpinejs" defer></script>

</body>
</html>
