<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIP-S') - Sistem Informasi Pengelolaan Skripsi</title>
    <meta name="description" content="Sistem Informasi Pengelolaan Skripsi Fakultas Pertanian">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Sidebar Master (role-based) -->
    <x-navigation.sidebar />

    <!-- Main Content -->
    <main class="p-4 sm:ml-64 mt-14 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    {{-- <x-partials.footer-auth /> --}}

    @livewireScripts
    @fluxScripts
    @stack('scripts')

    {{-- Flowbite JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
